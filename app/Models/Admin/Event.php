<?php

namespace App\Models\Admin;

use App\Traits\MultiTenant\AccountTenant;
use App\Traits\Translations\Admin\EventTranslation;
use Illuminate\Database\Eloquent\Model;
use function route;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Traits\Revisionable\Admin\EventRevisionable;
use DateTimeInterface;

class Event extends Model implements HasMedia
{
    use HasMediaTrait;
    use EventRevisionable;
    use EventTranslation;
    use AccountTenant;

    protected $guarded = ['roles', 'users', 'attachments'];

    protected $hidden = ['created_at', 'updated_at'];

    protected $dates = [
        'start',
        'end',
    ];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $appends = ['read', 'datatables_revisions_route'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'event_user');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'event_role');
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('attachments');
    }

    public function getReadAttribute()
    {
        $readEvents = Auth::user()->read_events ? array_pluck(Auth::user()->read_events, 'id') : [];
        return in_array($this->id, $readEvents);
    }

    public function read()
    {
        $readEvents = Auth::user()->read_events;

        $tmp =  [
            'id' => $this->id,
            'end' => $this->end,
        ];
        if(empty($readEvents)) {
            $readEvents = [
                $tmp,
            ];
        }else{
            $readEvents []= $tmp;
        }
        $now = Carbon::now();
        foreach ($readEvents as $index => $readEvent) {
            if($now->gt($readEvent['end'])) {
                unset($readEvents[$index]);
            }
        }

        Auth::user()->update(['read_events' => array_values($readEvents)]);
    }

    public static function getForPeriod()
    {
        $start = Carbon::createFromTimestamp(request('start'))->toDateString();
        $end = Carbon::createFromTimestamp(request('end'))->toDateString();

        $user = Auth::user();

        if($user->can('view_all', Event::class)) {
            return self::getAll($start, $end);
        }else{
            return self::getForUser($start, $end);
        }
    }

    /**
     * @param $start
     * @param $end
     * @return mixed
     */
    private static function getAll($start, $end)
    {
        $events = self::where(function ($q) use($start, $end) { // Between period
            $q->where('start', '>=', $start)
                ->where('start', '<=', $end)
                ->where('end', '>=', $start)
                ->where('end', '<=', $end);
        })
            ->orWhere(function ($q) use($start, $end) { // Start in the period
                $q->where('start', '>=', $start)
                    ->where('start', '<=', $end)
                    ->where('end', '>=', $end);
            })
            ->orWhere(function ($q) use($start, $end) { // End in the period
                $q->where('start', '<=', $start)
                    ->where('end', '>=', $start)
                    ->where('end', '<=', $end);
            })
            ->orWhere(function ($q) use($start, $end) { // Bigger than period, but cover it
                $q->where('start', '<=', $start)
                    ->where('end', '>=', $end);
            })
            ->with('roles')
            ->with('users')
            ->with('media')
            ->get();

        foreach ($events as $event) {
            $event->media_template = view('partials._attachments', ['item' => $event, 'canMediaBeenDeleted' => true])->render();
        }

        return $events;
    }

    /**
     * @param $start
     * @param $end
     * @return mixed
     */
    private static function getForUser($start, $end)
    {
        $rolesIds = Auth::user()->roles->pluck('id')->toArray();

        $events = self::where(function ($query) use($start, $end){
            $query->where(function ($q) use($start, $end) { // Between period
                $q->where('start', '>=', $start)
                    ->where('start', '<=', $end)
                    ->where('end', '>=', $start)
                    ->where('end', '<=', $end);
            })
                ->orWhere(function ($q) use($start, $end) { // Start in the period
                    $q->where('start', '>=', $start)
                        ->where('start', '<=', $end)
                        ->where('end', '>=', $end);
                })
                ->orWhere(function ($q) use($start, $end) { // End in the period
                    $q->where('start', '<=', $start)
                        ->where('end', '>=', $start)
                        ->where('end', '<=', $end);
                })
                ->orWhere(function ($q) use($start, $end) { // Bigger than period, but cover it
                    $q->where('start', '<=', $start)
                        ->where('end', '>=', $end);
                });
        })
            ->where(function ($query) use($rolesIds) {
                $query->where(function ($query) use($rolesIds) {
                    $query->whereHas('roles', function ($query) use($rolesIds) {
                        $query->whereIn('id', $rolesIds);
                    });
                })->orWhere(function ($query) {
                    $query->whereHas('users', function ($query) {
                        $query->where('id', Auth::id());
                    });
                });
            })
            ->with('media')
            ->get();

        foreach ($events as $event) {
            $event->media_template = view('partials._attachments', ['item' => $event, 'canMediaBeenDeleted' => true])->render();
        }

        return $events;
    }

    public function getDatatablesRevisionsRouteAttribute()
    {
        return route('admin.datatables.revisions', ['model_type' => get_class($this), 'model_id' => $this->id]);
    }
}
