<?php

namespace App\Policies\Admin;

use App\Models\Admin\User;
use App\Models\Admin\Event;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     */
    public function view_index(User $user)
    {
        return $user->hasPermissionTo('view_all events')
            || $user->hasPermissionTo('view_own events');
    }

    /**
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    public function view_all(User $user)
    {
        return $user->hasPermissionTo('view_all events');
    }

    /**
     * Determine whether the user can view the event.
     *
     * @param  \App\Models\Admin\User  $user
     * @param  \App\Models\Admin\Event  $event
     * @return mixed
     */
    public function view(User $user, Event $event)
    {
        if($user->hasPermissionTo('view_all events')) {
            return true;
        }

        if($user->hasPermissionTo('view_all events')) {
            //TODO implement custom logic to define if @param Event $event is owned by @param User $user
            return false;
        }
    }

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     * @throws \Exception
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create events');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param Event $event
     * @return bool
     * @throws \Exception
     */
    public function update(User $user, Event $event)
    {
        if($user->hasPermissionTo('update_all events')) {
            return true;
        }

        if($user->hasPermissionTo('update_own events')) {
            return $user->id == $event->creator_id;
        }

        return false;
    }

    /**
     * @param User $user
     * @param \App\Models\Admin\Event $event
     * @return bool
     * @throws \Exception
     */
    public function delete(User $user, Event $event)
    {
        if($user->hasPermissionTo('delete_all events')) {
            return true;
        }

        if($user->hasPermissionTo('delete_own events')) {
            return $user->id == $event->creator_id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the event.
     *
     * @param  \App\Models\Admin\User  $user
     * @param  \App\Models\Admin\Event  $event
     * @return mixed
     */
    public function restore(User $user, Event $event)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the event.
     *
     * @param  \App\Models\Admin\User  $user
     * @param  \App\Models\Admin\Event  $event
     * @return mixed
     */
    public function forceDelete(User $user, Event $event)
    {
        return false;
    }

    /**
     * @param User $user
     * @param Event $event
     * @return bool
     * @throws \Exception
     */
    public function delete_media(User $user, Event $event)
    {
        if($user->hasPermissionTo('update_all events')) {
            return true;
        }

        if($user->hasPermissionTo('update_own events')) {
            return $user->id == $event->creator_id;
        }

        return false;
    }
}
