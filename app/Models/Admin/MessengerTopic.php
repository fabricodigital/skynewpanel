<?php

namespace App\Models\Admin;

use App\Traits\MultiTenant\AccountTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DateTimeInterface;

class MessengerTopic extends Model
{
    use AccountTenant;

    protected $fillable = [
        'subject',
        'sender_id',
        'receiver_id',
        'type',
        'role_id',
        'sent_at',
    ];
    protected $dates = [
        'sent_at',
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

    protected $appends = [
        'view_route', 'sent_at_formatted'
    ];

    public function messages()
    {
        return $this->hasMany(MessengerMessage::class, 'topic_id')->orderBy('sent_at', 'desc');
    }

    public function sender()
    {
        return $this->hasOne(User::class, 'id', 'sender_id');
    }

    public function userReceiver()
    {
        return $this->hasOne(User::class, 'id', 'receiver_id');
    }

    public function roleReceiver()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function getSentAtFormattedAttribute()
    {
        return $this->sent_at->diffForHumans();
    }

    public function getLastMessageAsReceiver()
    {
        foreach ($this->messages as $message) {
            // If message is sent to User
            if($message->receiver_model == User::class && $message->receiver_id == Auth::id()) {
                return $message;
            }
            // If message is sent to Role
            if($message->receiver_model == Role::class && in_array($message->role_id, Auth::user()->roles->pluck('id')->toArray())) {
                return $message;
            }
        }

        return null;
    }

    public function makeMessagesAsRead()
    {
        $now = Carbon::now();
        $user = Auth::user();

        $this->messages
            ->where('receiver_model', User::class)
            ->where('receiver_id', $user->id)
            ->where('read_at', null)
            ->each(function ($item, $key) use($now) {
                $item->makeAsRead($now);
            });
        $this->messages
            ->where('receiver_model', Role::class)
            ->whereIn('role_id', $user->roles->pluck('id')->toArray())
            ->where('read_at', null)
            ->each(function ($item, $key) use($now) {
                $item->makeAsRead($now);
            });

        return $this;
    }

    public function isLoggedUserSender()
    {
        return $this->sender_id == Auth::id();
    }

    public function getNewMessageReceiverModel()
    {
        // Always will be "App\Models\Admin\User" if topic is direct type
        if(is_null($this->role_id) && $this->type == 'direct') {
            return User::class;
        }

        if(!is_null($this->role_id) && $this->isLoggedUserSender()){
            return Role::class;
        }

        return User::class;
    }

    public function replay($content)
    {
        $data = [
            'content' => $content,
            'receiver_model' => $this->getNewMessageReceiverModel(),
            'role_id' => $this->role_id,
            'sender_id' => Auth::id(),
            'sent_at' => Carbon::now(),
        ];
        if(!$this->isLoggedUserSender()) {
            $data['receiver_id'] = $this->sender_id;
            return $this->messages()->create($data);
        }
        if(!is_null($this->role_id)) {
            $data['receiver_id'] = null;
            return $this->messages()->create($data);
        }
        $data['receiver_id'] = $this->receiver_id;
        return $this->messages()->create($data);
    }

    public function scopeHelp($query)
    {
        return $query->where('type', 'help');
    }

    public function scopeDirect($query)
    {
        return $query->where('type', 'direct');
    }

    public function scopeInboxAll($query)
    {
        return $query->whereHas('messages', function ($query) {
            $query->where(function ($query) {
                $query->where('receiver_model', User::class)
                    ->where('receiver_id', Auth::id());
            })->orWhere(function ($query) {
                $query->where('receiver_model', Role::class)
                    ->whereIn('role_id', Auth::user()->roles->pluck('id')->toArray());
            });
        });
    }

    public function scopeOutboxAll($query)
    {
        return $query->where('sender_id', Auth::id());
    }

    public function scopeWithSheets($query)
    {
        $query->with(['messages' => function($query) {
            $query->with('media');
            $query->with(['sender' => function($query) {
                $query->setEagerLoads([]);
            }]);
        }]);
        $query->with(['roleReceiver' => function($query) {
            $query->setEagerLoads([]);
        }]);
        $query->with(['userReceiver' => function($query) {
            $query->setEagerLoads([]);
        }]);
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('attachments');
    }

    public function getViewRouteAttribute()
    {
        return route('admin.messenger.show', [$this]);
    }
}
