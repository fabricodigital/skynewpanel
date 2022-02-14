<?php

namespace App\Policies\Admin;

use App\Models\Admin\User;
use App\Models\Admin\Widget;
use Illuminate\Auth\Access\HandlesAuthorization;

class WidgetPolicy
{
    use HandlesAuthorization;

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     */
    public function view_index(User $user)
    {
        return $user->hasPermissionTo('view_all widgets')
            || $user->hasPermissionTo('view_own widgets');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     * @throws \Exception
     */
    public function view_all(User $user)
    {
        return $user->hasPermissionTo('view_all widgets');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\Widget $widget
     * @return bool
     */
    public function view(User $user, Widget $widget)
    {
        if (!empty($widget->deleted_at) && !$this->view_deleted($user)) {
            return false;
        }

        if($user->hasPermissionTo('view_all widgets')) {
            return true;
        }

        if($user->hasPermissionTo('view_all widgets')) {
            //TODO implement custom logic to define if @param Widget $widget is owned by @param User $user
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
        return $user->hasPermissionTo('view_deleted widgets');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\Widget $widget
     * @param bool $viewAll
     * @param bool $viewOwn
     * @return bool
     */
    public function dt_view(User $user, Widget $widget, bool $viewAll, bool $viewOwn)
    {
        if($viewAll) {
            return true;
        }

        if($viewOwn) {
            //TODO implement custom logic to define if @param Widget $widget is owned by @param User $user
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
        return $user->hasPermissionTo('create widgets');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param Widget $widget
     * @return bool
     * @throws \Exception
     */
    public function update(User $user, Widget $widget)
    {
        if($user->hasPermissionTo('update_all widgets')) {
            return true;
        }

        if($user->hasPermissionTo('update_own widgets')) {
            //TODO implement custom logic to define if @param Widget $widget is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @param Widget $widget
     * @param bool $updateAll
     * @param bool $updateOwn
     * @return bool
     */
    public function dt_update(User $user, Widget $widget, bool $updateAll, bool $updateOwn)
    {
        if ($widget->deleted) {
            return false;
        }

        if($updateAll) {
            return true;
        }

        if($updateOwn) {
            //TODO implement custom logic to define if @param Widget $widget is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\Widget $widget
     * @return bool
     * @throws \Exception
     */
    public function delete(User $user, Widget $widget)
    {
        if ($widget->deleted && !$this->delete_forever($user)) {
            return false;
        }

        if($user->hasPermissionTo('delete_all widgets')) {
            return true;
        }

        if($user->hasPermissionTo('delete_own widgets')) {
            //TODO implement custom logic to define if @param Widget $widget is owned by @param User $user
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
        return $user->hasPermissionTo('delete_forever widgets');
    }

    /**
     * @param User $user
     * @param Widget $widget
     * @param $deleteAll
     * @param $deleteOwn
     * @return bool
     */
    public function dt_delete(User $user, Widget $widget, $deleteAll, $deleteOwn)
    {
        if ($widget->deleted && !$this->delete_forever($user)) {
            return false;
        }

        if($deleteAll) {
            return true;
        }

        if($deleteOwn) {
            //TODO implement custom logic to define if @param Widget $widget is owned by @param User $user
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
     * @param Widget $widget
     * @return bool
     */
    public function delete_media(User $user, Widget $widget)
    {
        return true;
    }

    /**
     * @param User $user
     * @param Widget $widget
     * @return bool
     */
    public function restore(User $user, Widget $widget)
    {
        if($user->hasPermissionTo('restore_all widgets')) {
            return true;
        }

        if($user->hasPermissionTo('restore_own widgets')) {
            //TODO implement custom logic to define if @param Widget $widget is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @param Widget $widget
     * @param $restoreAll
     * @param $restoreOwn
     * @return bool
     */
    public function dt_restore(User $user, Widget $widget, $restoreAll, $restoreOwn)
    {
        if (!$widget->deleted) {
            return false;
        }

        if($restoreAll) {
            return true;
        }

        if($restoreOwn) {
            //TODO implement custom logic to define if @param Widget $widget is owned by @param User $user
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
        return $user->hasPermissionTo('export widgets');
    }
}
