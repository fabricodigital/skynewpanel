<?php

namespace App\Policies\Admin;

use App\Models\Admin\User;
use App\Models\Admin\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     * @throws \Exception
     */
    public function view_all(User $user)
    {
        return $user->hasPermissionTo('view_all roles');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     * @throws \Exception
     */
    public function view_index(User $user)
    {
        return $user->hasPermissionTo('view_all roles') || $user->hasPermissionTo('view_own roles');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\Role $role
     * @return bool
     * @throws \Exception
     */
    public function view(User $user, Role $role)
    {
        if (!empty($role->deleted_at) && !$this->view_deleted($user)) {
            return false;
        }

        if($user->hasPermissionTo('view_all roles')) {
            return true;
        }

        if($user->hasPermissionTo('view_own roles')) {
            //TODO implement custom logic to define if @param Role $role is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function view_deleted(User $user)
    {
        return $user->hasPermissionTo('view_deleted roles');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\Role $role
     * @param bool $viewAll
     * @param bool $viewOwn
     * @return bool
     */
    public function dt_view(User $user, Role $role, bool $viewAll, bool $viewOwn)
    {
        if($viewAll) {
            return true;
        }

        if($viewOwn) {
            //TODO implement custom logic to define if @param Role $role is owned by @param User $user
            return false;
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
        return $user->hasPermissionTo('create roles');
    }

    /**
     * @param User $user
     * @param \App\Models\Admin\Role $role
     * @return bool
     * @throws \Exception
     */
    public function update(User $user, Role $role)
    {
        if($user->hasPermissionTo('update_all roles')) {
            return true;
        }

        if($user->hasPermissionTo('update_own roles')) {
            //TODO implement custom logic to define if @param Role $role is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @param Role $role
     * @param bool $updateAll
     * @param bool $updateOwn
     * @return bool
     */
    public function dt_update(User $user, Role $role, bool $updateAll, bool $updateOwn)
    {
        if ($role->deleted) {
            return false;
        }

        if($updateAll) {
            return true;
        }

        if($updateOwn) {
            //TODO implement custom logic to define if @param Role $role is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\Role $role
     * @return bool
     * @throws \Exception
     */
    public function delete(User $user, Role $role)
    {
        if ($role->deleted && !$this->delete_forever($user)) {
            return false;
        }

        if($user->hasPermissionTo('delete_all roles')) {
            return true;
        }

        if($user->hasPermissionTo('delete_own roles')) {
            //TODO implement custom logic to define if @param Role $role is owned by @param User $user
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
        return $user->hasPermissionTo('delete_forever roles');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\Role $role
     * @param $deleteAll
     * @param $deleteOwn
     * @return bool
     */
    public function dt_delete(User $user, Role $role, $deleteAll, $deleteOwn)
    {
        if ($role->deleted && !$this->delete_forever($user)) {
            return false;
        }

        if($deleteAll) {
            return true;
        }

        if($deleteOwn) {
            //TODO implement custom logic to define if @param Role $role is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @param Role $role
     * @return bool
     */
    public function restore(User $user, Role $role)
    {
        if($user->hasPermissionTo('restore_all roles')) {
            return true;
        }

        if($user->hasPermissionTo('restore_own roles')) {
            //TODO implement custom logic to define if @param Role $role is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @param Role $role
     * @param $restoreAll
     * @param $restoreOwn
     * @return bool
     */
    public function dt_restore(User $user, Role $role, $restoreAll, $restoreOwn)
    {
        if (!$role->deleted) {
            return false;
        }

        if($restoreAll) {
            return true;
        }

        if($restoreOwn) {
            //TODO implement custom logic to define if @param Role $role is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\Role $role
     * @return bool
     */
    public function forceDelete(User $user, Role $role)
    {
        return false;
    }

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     * @throws \Exception
     */
    public function export(User $user)
    {
        return $user->hasPermissionTo('export roles');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     */
    public function update_permissions(User $user)
    {
        return $user->hasRole('Super Administrator');
    }
}
