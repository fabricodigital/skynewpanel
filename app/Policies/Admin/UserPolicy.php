<?php

namespace App\Policies\Admin;

use App\Models\Admin\User;
use Illuminate\Support\Facades\Auth;
use function dd;
use Illuminate\Auth\Access\HandlesAuthorization;
use function is_null;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    public function view_all(User $user)
    {
        return $user->hasPermissionTo('view_all users');
    }

    /**
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    public function view_own(User $user)
    {
        return $user->hasPermissionTo('view_own users');
    }

    /**
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    public function view_index(User $user)
    {
        return $user->hasPermissionTo('view_all users')
            || $user->hasPermissionTo('view_own users');
    }

    /**
     * @param User $user
     * @param \App\Models\Admin\User $model
     * @return bool
     * @throws \Exception
     */
    public function view(User $user, User $model)
    {
        if (!empty($model->deleted_at) && !$this->view_deleted($user)) {
            return false;
        }

        if($user->hasPermissionTo('view_all users')) {
            return true;
        }

        if($user->hasPermissionTo('view_own users')) {
            if (Auth::user()->hasPermissionToUser($model->id)) {
                return true;
            }
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
        return $user->hasPermissionTo('view_deleted users');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\User $model
     * @param bool $viewAll
     * @param bool $viewOwn
     * @return bool
     */
    public function dt_view(User $user, User $model, bool $viewAll, bool $viewOwn)
    {
        if($viewAll) {
            return true;
        }

        if($viewOwn) {
            //TODO implement custom logic to define if @param User $model is owned by @param User $user
            return true;
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
        return $user->hasPermissionTo('create users');
    }

    /**
     * @param User $user
     * @param User $model
     * @return bool
     * @throws \Exception
     */
    public function update(User $user, User $model)
    {
        if($user->hasPermissionTo('update_all users')) {
            return true;
        }

        if($user->hasPermissionTo('update_own users')) {
            if (Auth::user()->hasPermissionToUser($model->id)) {
                return true;
            }
            return false;
        }

        return false;
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param User $model
     * @param bool $updateAll
     * @param bool $updateOwn
     * @return bool
     */
    public function dt_update(User $user, User $model, bool $updateAll, bool $updateOwn)
    {
        if ($model->deleted) {
            return false;
        }

        if($updateAll) {
            return true;
        }

        if($updateOwn) {
            //TODO implement custom logic to define if @param User $model is owned by @param User $user
            return true;
        }

        return false;
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\User $model
     * @return bool
     * @throws \Exception
     */
    public function delete(User $user, User $model)
    {
        if ($model->deleted && !$this->delete_forever($user)) {
            return false;
        }

        if($user->hasPermissionTo('delete_all users')) {
            return true;
        }

        if($user->hasPermissionTo('delete_own users')) {
            if (Auth::user()->hasPermissionToUser($model->id)) {
                return true;
            }
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
        return $user->hasPermissionTo('delete_forever users');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\User $model
     * @param $deleteAll
     * @param $deleteOwn
     * @return bool
     */
    public function dt_delete(User $user, User $model, $deleteAll, $deleteOwn)
    {
        if ($model->deleted && !$this->delete_forever($user)) {
            return false;
        }

        if($deleteAll) {
            return true;
        }

        if($deleteOwn) {
            //TODO implement custom logic to define if @param User $model is owned by @param User $user
            return true;
        }

        return false;
    }

    /**
     * @param User $user
     * @param User $model
     * @return bool
     */
    public function restore(User $user, User $model)
    {
        if($user->hasPermissionTo('restore_all users')) {
            return true;
        }

        if($user->hasPermissionTo('restore_own users')) {
            //TODO implement custom logic to define if @param User $model is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @param User $model
     * @param $restoreAll
     * @param $restoreOwn
     * @return bool
     */
    public function dt_restore(User $user, User $model, $restoreAll, $restoreOwn)
    {
        if (!$model->deleted) {
            return false;
        }

        if($restoreAll) {
            return true;
        }

        if($restoreOwn) {
            //TODO implement custom logic to define if @param User $model is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Admin\User  $user
     * @param  \App\Models\Admin\User  $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        return false;
    }

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     * @throws \Exception
     */
    public function assign_roles(User $user)
    {
        return $user->hasPermissionTo('update_sensitive_data users');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     * @throws \Exception
     */
    public function change_state(User $user)
    {
        return $user->hasPermissionTo('update_sensitive_data users');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     * @throws \Exception
     */
    public function view_sensitive_data(User $user)
    {
        return $user->hasPermissionTo('view_sensitive_data users');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     * @throws \Exception
     */
    public function update_sensitive_data(User $user)
    {
        return $user->hasPermissionTo('update_sensitive_data users');
    }

    /**
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    public function export(User $user)
    {
        return $user->hasPermissionTo('export users');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function view_permissions(User $user)
    {
        return $user->hasRole('Super Administrator');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     */
    public function update_permissions(User $user)
    {
        return $user->hasRole('Super Administrator');
    }

    /**
     * @param User $user
     * @param User $model
     * @return bool
     */
    public function impersonate(User $user, User $model)
    {
        $userHighestRole = $user->getHighestLevelRole();
        $modelHighestRole = $model->getHighestLevelRole();

        if($userHighestRole->level >= $modelHighestRole->level) {
            return false;
        }

        return $user->hasPermissionTo('impersonate users')
            && $user->can('update', $model)
            && is_null($model->impersonated_by_id)
            && $model->state == 'activated';
    }

    /**
     * @param User $user
     * @param User $model
     * @param $impersonatePermission
     * @param $updateAllPermission
     * @param $updateOwnPermission
     * @return bool
     */
    public function dt_impersonate(User $user, User $model, $impersonatePermission, $updateAllPermission, $updateOwnPermission)
    {
        $userHighestRole = $user->getHighestLevelRole();
        $modelHighestRole = $model->getHighestLevelRole();

        if($userHighestRole->level >= $modelHighestRole->level) {
            return false;
        }

        return $impersonatePermission
            && $user->can('dt_update', [$model, $updateAllPermission, $updateOwnPermission])
            && $model->state == 'activated';
    }

    /**
     * @param User $user
     * @param User|null $model
     * @return bool
     */
    public function impersonate_back(User $user)
    {
        return session()->get('impersonated_by');
    }

    /**
     * @param User $user
     * @param User $model
     * @return bool
     */
    public function get_settings(User $user, User $model)
    {
        return $user->id == $model->id
            || session()->get('impersonated_by');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function link_profiles(User $user)
    {
        return $user->hasPermissionTo('link_profiles users');
    }
}
