<?php

namespace App\Policies\Admin;

use App\Models\Admin\User;
use App\Models\Admin\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy
{
    use HandlesAuthorization;

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     */
    public function view_index(User $user)
    {
        return $user->hasRole('Super Administrator');
    }
    /**
     * Determine whether the user can view the permission.
     *
     * @param  \App\Models\Admin\User  $user
     * @param  \App\Models\Admin\Permission  $permission
     * @return mixed
     */
    public function view(User $user, Permission $permission)
    {
        return false;
    }

    /**
     * Determine whether the user can create permissions.
     *
     * @param  \App\Models\Admin\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the permission.
     *
     * @param  \App\Models\Admin\User  $user
     * @param  \App\Models\Admin\Permission  $permission
     * @return mixed
     */
    public function update(User $user, Permission $permission)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the permission.
     *
     * @param  \App\Models\Admin\User  $user
     * @param  \App\Models\Admin\Permission  $permission
     * @return mixed
     */
    public function delete(User $user, Permission $permission)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the permission.
     *
     * @param  \App\Models\Admin\User  $user
     * @param  \App\Models\Admin\Permission  $permission
     * @return mixed
     */
    public function restore(User $user, Permission $permission)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the permission.
     *
     * @param  \App\Models\Admin\User  $user
     * @param  \App\Models\Admin\Permission  $permission
     * @return mixed
     */
    public function forceDelete(User $user, Permission $permission)
    {
        return false;
    }
}
