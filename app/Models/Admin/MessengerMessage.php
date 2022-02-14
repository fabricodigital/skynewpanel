<?php

namespace App\Models\Admin;

use App\Traits\MultiTenant\AccountTenant;
use App\Traits\Translations\Admin\MessengerMessageTranslation;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use DateTimeInterface;

class MessengerMessage extends Model implements HasMedia
{
    use HasMediaTrait;
    use MessengerMessageTranslation;
    use AccountTenant;

    public static function boot()
    {
        parent::boot();

        self::created(function($model) {
            $model->topic->sent_at = $model->sent_at;
            $model->topic->save();
        });
    }

    protected $guarded = [];

    protected $dates = [
        'sent_at'
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

    public function topic()
    {
        return $this->belongsTo(MessengerTopic::class);
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

    public function makeAsRead($readAt = null)
    {
        if( is_null($readAt) ) {
            $readAt = Carbon::now();
        }
        $this->read_at = $readAt;
        return $this->save();
    }

    public function readByUser($user = null)
    {
        if(is_null($user)) {
            $user = Auth::user();
        }

        if(is_null($this->read_at) && $this->receiver_model == User::class && $this->receiver_id == $user->id) {
            return false;
        }

        if(is_null($this->read_at) && $this->receiver_model == Role::class && in_array($this->role_id, $user->roles->pluck('id')->toArray())) {
            return false;
        }

        return true;
    }

    public function isLoggedUserSender()
    {
        return $this->sender_id == Auth::id();
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('attachments');
    }
}
