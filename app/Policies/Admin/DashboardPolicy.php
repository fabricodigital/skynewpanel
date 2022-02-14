<?php

namespace App\Policies\Admin;

use App\Models\Admin\User;
use App\Models\Admin\Dashboard;
use Illuminate\Auth\Access\HandlesAuthorization;

class DashboardPolicy
{
    use HandlesAuthorization;

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     */
    public function view_index(User $user)
    {
        return $user->hasPermissionTo('view_all dashboards')
            || $user->hasPermissionTo('view_own dashboards');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     * @throws \Exception
     */
    public function view_all(User $user)
    {
        return $user->hasPermissionTo('view_all dashboards');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\Dashboard $dashboard
     * @return bool
     */
    public function view(User $user, Dashboard $dashboard)
    {
        if (!empty($dashboard->deleted_at) && !$this->view_deleted($user)) {
            return false;
        }

        if($user->hasPermissionTo('view_all dashboards')) {
            return true;
        }

        if($user->hasPermissionTo('view_all dashboards')) {
            //TODO implement custom logic to define if @param Dashboard $dashboard is owned by @param User $user
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
        return $user->hasPermissionTo('view_deleted dashboards');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\Dashboard $dashboard
     * @param bool $viewAll
     * @param bool $viewOwn
     * @return bool
     */
    public function dt_view(User $user, Dashboard $dashboard, bool $viewAll, bool $viewOwn)
    {
        if($viewAll) {
            return true;
        }

        if($viewOwn) {
            //TODO implement custom logic to define if @param Dashboard $dashboard is owned by @param User $user
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
        return $user->hasPermissionTo('create dashboards');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param Dashboard $dashboard
     * @return bool
     * @throws \Exception
     */
    public function update(User $user, Dashboard $dashboard)
    {
        if($user->hasPermissionTo('update_all dashboards')) {
            return true;
        }

        if($user->hasPermissionTo('update_own dashboards')) {
            //TODO implement custom logic to define if @param Dashboard $dashboard is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @param Dashboard $dashboard
     * @param bool $updateAll
     * @param bool $updateOwn
     * @return bool
     */
    public function dt_update(User $user, Dashboard $dashboard, bool $updateAll, bool $updateOwn)
    {
        if ($dashboard->deleted) {
            return false;
        }

        if($updateAll) {
            return true;
        }

        if($updateOwn) {
            //TODO implement custom logic to define if @param Dashboard $dashboard is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\Dashboard $dashboard
     * @return bool
     * @throws \Exception
     */
    public function delete(User $user, Dashboard $dashboard)
    {
        if ($dashboard->deleted && !$this->delete_forever($user)) {
            return false;
        }

        if($user->hasPermissionTo('delete_all dashboards')) {
            return true;
        }

        if($user->hasPermissionTo('delete_own dashboards')) {
            //TODO implement custom logic to define if @param Dashboard $dashboard is owned by @param User $user
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
        return $user->hasPermissionTo('delete_forever dashboards');
    }

    /**
     * @param User $user
     * @param Dashboard $dashboard
     * @param $deleteAll
     * @param $deleteOwn
     * @return bool
     */
    public function dt_delete(User $user, Dashboard $dashboard, $deleteAll, $deleteOwn)
    {
        if ($dashboard->deleted && !$this->delete_forever($user)) {
            return false;
        }

        if($deleteAll) {
            return true;
        }

        if($deleteOwn) {
            //TODO implement custom logic to define if @param Dashboard $dashboard is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     */
    public function mass_delete(User $user)
    {
        return false;
    }

    /**
     * @param User $user
     * @param Dashboard $dashboard
     * @return bool
     */
    public function delete_media(User $user, Dashboard $dashboard)
    {
        return true;
    }

    /**
     * @param User $user
     * @param Dashboard $dashboard
     * @return bool
     */
    public function restore(User $user, Dashboard $dashboard)
    {
        if($user->hasPermissionTo('restore_all dashboards')) {
            return true;
        }

        if($user->hasPermissionTo('restore_own dashboards')) {
            //TODO implement custom logic to define if @param Dashboard $dashboard is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @param Dashboard $dashboard
     * @param $restoreAll
     * @param $restoreOwn
     * @return bool
     */
    public function dt_restore(User $user, Dashboard $dashboard, $restoreAll, $restoreOwn)
    {
        if (!$dashboard->deleted) {
            return false;
        }

        if($restoreAll) {
            return true;
        }

        if($restoreOwn) {
            //TODO implement custom logic to define if @param Dashboard $dashboard is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     * @throws \Exception
     */
    public function export(User $user)
    {
        return $user->hasPermissionTo('export dashboards');
    }
}
