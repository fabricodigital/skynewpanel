<?php

namespace App\Policies\Admin;

use App\Models\Admin\User;
use App\Models\Admin\MessengerTopic;
use App\Models\Admin\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessengerTopicPolicy
{
    use HandlesAuthorization;

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     * @throws \Exception
     */
    public function view_all(User $user)
    {
        return $user->hasPermissionTo('view_all messages');
    }

    /**
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    public function view_index(User $user)
    {
        return $user->hasPermissionTo('view_all messages')
            || $user->hasPermissionTo('view_own messages');
    }

    /**
     * Determine whether the user can view the messenger topic.
     *
     * @param  \App\Models\Admin\User  $user
     * @param  \App\Models\Admin\MessengerTopic  $messengerTopic
     * @return mixed
     */
    public function view(User $user, MessengerTopic $messengerTopic)
    {
        if($messengerTopic->sender_id == $user->id) {
            return true;
        }
        if($messengerTopic->type == 'direct' && $messengerTopic->receiver_id == $user->id) {
            return true;
        }

        $userRolesIds = $user->roles->pluck('id')->toArray();

        if($messengerTopic->type == 'help' && in_array($messengerTopic->role_id, $userRolesIds)) {
            return true;
        }

        return false;
    }

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     */
    public function create_direct(User $user)
    {
        if($user->hasPermissionTo('create_direct messages')) {
            //TODO probably we should check if user can send to all users or can send only to given users
            return true;
        }

        return false;
    }

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     */
    public function create_help(User $user)
    {
        return $user->hasPermissionTo('create_help messages');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create_direct messages') || $user->hasPermissionTo('create_help messages');
    }

    /**
     * Determine whether the user can update the messenger topic.
     *
     * @param  \App\Models\Admin\User  $user
     * @param  \App\Models\Admin\MessengerTopic  $messengerTopic
     * @return mixed
     */
    public function update(User $user, MessengerTopic $messengerTopic)
    {
        if($messengerTopic->sender_id == $user->id) {
            return true;
        }
        if($messengerTopic->type == 'direct' && $messengerTopic->receiver_id == $user->id) {
            return true;
        }

        $userRolesIds = $user->roles->pluck('id')->toArray();

        if($messengerTopic->type == 'help' && in_array($messengerTopic->role_id, $userRolesIds)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the messenger topic.
     *
     * @param  \App\Models\Admin\User  $user
     * @param  \App\Models\Admin\MessengerTopic  $messengerTopic
     * @return mixed
     */
    public function delete(User $user, MessengerTopic $messengerTopic)
    {
        return false;
    }
}
