<?php

namespace App\Policies\Admin;

use App\Models\Admin\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Admin\Notification;

class NotificationPolicy
{
    use HandlesAuthorization;

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     * @throws \Exception
     */
    public function view_index(User $user)
    {
        return $user->hasPermissionTo('view_all notifications') || $user->hasPermissionTo('view_own notifications');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     * @throws \Exception
     */
    public function view_all(User $user)
    {
        return $user->hasPermissionTo('view_all notifications');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param Notification $notification
     * @return bool
     * @throws \Exception
     */
    public function view(User $user, Notification $notification)
    {
        if (!empty($notification->deleted_at) && !$this->view_deleted($user)) {
            return false;
        }

        if($user->hasPermissionTo('view_all notifications')) {
            return true;
        }

        if($user->hasPermissionTo('view_own notifications')) {
            $userRolesIds = $user->roles->pluck('id')->toArray();

            if(!!$notification->roles()->whereIn('id', $userRolesIds)->count()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function view_deleted(User $user)
    {
        return $user->hasPermissionTo('view_deleted notifications');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\Notification $notification
     * @param bool $viewAll
     * @param bool $viewOwn
     * @return bool
     */
    public function dt_view(User $user, Notification $notification, bool $viewAll, bool $viewOwn)
    {
        if($viewAll) {
            return true;
        }

        if($viewOwn) {
            $userRolesIds = $user->roles->pluck('id')->toArray();

            if(!!$notification->roles()->whereIn('id', $userRolesIds)->count()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     * @throws \Exception
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create notifications');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\Notification $notification
     * @return bool
     * @throws \Exception
     */
    public function update(User $user, Notification $notification)
    {
        if($user->hasPermissionTo('update_all notifications')) {
            return true;
        }

        if($user->hasPermissionTo('update_own notifications')) {
            //TODO implement custom logic to define if @param Notification $notification is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\Notification $notification
     * @param bool $updateAll
     * @param bool $updateOwn
     * @return bool
     */
    public function dt_update(User $user, Notification $notification, bool $updateAll, bool $updateOwn)
    {
        if ($notification->deleted) {
            return false;
        }

        if($updateAll) {
            return true;
        }

        if($updateOwn) {
            //TODO implement custom logic to define if @param Notification $notification is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param Notification $notification
     * @return bool
     * @throws \Exception
     */
    public function delete(User $user, Notification $notification)
    {
        if ($notification->deleted && !$this->delete_forever($user)) {
            return false;
        }

        if($user->hasPermissionTo('delete_all notifications')) {
            return true;
        }

        if($user->hasPermissionTo('delete_own notifications')) {
            //TODO implement custom logic to define if @param Notification $notification is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function delete_forever(User $user)
    {
        return $user->hasPermissionTo('delete_forever notifications');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\Notification $notification
     * @param $deleteAll
     * @param $deleteOwn
     * @return bool
     */
    public function dt_delete(User $user, Notification $notification, $deleteAll, $deleteOwn)
    {
        if ($notification->deleted && !$this->delete_forever($user)) {
            return false;
        }

        if($deleteAll) {
            return true;
        }

        if($deleteOwn) {
            //TODO implement custom logic to define if @param Notification $notification is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * Determine whether the user can mass delete the internal notification.
     *
     * @param  \App\Models\Admin\User  $user
     * @return mixed
     */
    public function mass_delete(User $user)
    {
        return false;
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\Notification $notification
     * @return bool
     */
    public function delete_media(User $user, Notification $notification)
    {
        if($user->hasPermissionTo('update_all notifications')) {
            return true;
        }

        if($user->hasPermissionTo('update_own notifications')) {
            //TODO implement custom logic to define if @param Notification $notification is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @param Notification $notification
     * @return bool
     */
    public function restore(User $user, Notification $notification)
    {
        if($user->hasPermissionTo('restore_all notifications')) {
            return true;
        }

        if($user->hasPermissionTo('restore_own notifications')) {
            //TODO implement custom logic to define if @param Notification $notification is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @param Notification $notification
     * @param $restoreAll
     * @param $restoreOwn
     * @return bool
     */
    public function dt_restore(User $user, Notification $notification, $restoreAll, $restoreOwn)
    {
        if (!$notification->deleted) {
            return false;
        }

        if($restoreAll) {
            return true;
        }

        if($restoreOwn) {
            //TODO implement custom logic to define if @param Notification $notification is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    public function export(User $user)
    {
        return $user->hasPermissionTo('export notifications');
    }
}
