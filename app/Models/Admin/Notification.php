<?php

namespace App\Models\Admin;

use App\Traits\MultiTenant\AccountTenant;
use App\Traits\Translations\Admin\NotificationTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Traits\Revisionable\Admin\NotificationRevisionable;
use App\Traits\DataTables\Admin\NotificationDataTable;
use DateTimeInterface;

class Notification extends Model implements HasMedia
{
    use HasMediaTrait;
    use NotificationRevisionable;
    use NotificationDataTable;
    use NotificationTranslation;
    use AccountTenant;
    use SoftDeletes;

    protected $guarded = ['attachments', 'roles'];

    protected $appends = ['view_route', 'read_route', 'start_formatted', 'end_formatted'];

    protected $dates = ['start', 'end', 'created_at', 'updated_at'];

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'notification_role');
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('attachments');
    }

    public function getViewRouteAttribute()
    {
        return route('admin.notifications.show', [$this]);
    }

    public function getReadRouteAttribute()
    {
        return route('admin.ajax.notifications.read', [$this]);
    }

    public function getStartFormattedAttribute()
    {
        return $this->start ? $this->start->format('d/m/Y') : null;
    }

    public function getEndFormattedAttribute()
    {
        return $this->end ? $this->end->format('d/m/Y') : null;
    }

    public function read()
    {
        $readNotifications = Auth::user()->read_notifications;
        $tmp =  [
            'id' => $this->id,
            'end' => $this->end->toDateTimeString(),
        ];
        if(empty($readNotifications)) {
            $readNotifications = [
                $tmp,
            ];
        }else{
            $readNotifications []= $tmp;
        }
        $now = Carbon::now();
        foreach ($readNotifications as $index => $readNotification) {
            if($now->gt($readNotification['end'])) {
                unset($readNotifications[$index]);
            }
        }

        Auth::user()->update(['read_notifications' => array_values($readNotifications)]);
    }
}
