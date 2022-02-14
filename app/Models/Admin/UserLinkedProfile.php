<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use function foo\func;

class UserLinkedProfile extends Model
{
    use SoftDeletes;

    protected $table = 'users_linked_profiles';

    protected $guarded = [];

    /**
     * @var array
     */
    protected $dates = ['hash_expired_at'];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * @param bool $onlyActive
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getLinkedProfiles($onlyActive = false)
    {
        $query = self::query()
            ->select([
                'users_linked_profiles.active',
                'user_id AS user_id',
                'u.email AS user_email',
                'u.name AS user_name',
                'u.surname AS user_surname',
                'a.name AS user_account_name',
                'linked_user_id',
                'u2.email AS linked_user_email',
                'u2.name AS linked_user_name',
                'u2.surname AS linked_user_surname',
                'a2.name AS linked_user_account_name',
            ])
            ->join('users AS u', 'users_linked_profiles.user_id', 'u.id')
            ->join('accounts AS a', 'u.account_id', 'a.id')
            ->join('users as u2', 'users_linked_profiles.linked_user_id', 'u2.id')
            ->join('accounts AS a2', 'u2.account_id', 'a2.id')
            ->where(function ($q) {
                $q->where('user_id', Auth::id())
                    ->orWhere('linked_user_id', Auth::id());
            });

        if ($onlyActive) {
            $query->where('users_linked_profiles.active', 1);
        }

        return $query->get();
    }

    /**
     * @param $userId
     * @return mixed
     */
    public static function getLinkedProfile($userId)
    {
        return self::where(function ($query) {
                $query->where('user_id', Auth::id())
                    ->orWhere('linked_user_id', Auth::id());
            })
            ->where(function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->orWhere('linked_user_id', $userId);
            })
            ->first();
    }
}
