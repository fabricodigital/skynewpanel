<?php

namespace App\Policies\Admin;

use App\Models\Admin\JobsLog;
use App\Models\Admin\User;
use App\Models\Admin\Export;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExportPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    public function view_all(User $user)
    {
        return $user->hasPermissionTo('view_all exports');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function view_index(User $user)
    {
        return $user->hasPermissionTo('view_all exports')
            || $user->hasPermissionTo('view_own exports');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\Export $export
     * @return bool
     * @throws \Exception
     */
    public function view(User $user, Export $export)
    {
        if (!empty($export->deleted_at) && !$this->view_deleted($user)) {
            return false;
        }

        if($user->hasPermissionTo('view_all exports')) {
            return true;
        }

        if($user->hasPermissionTo('view_own exports')) {
            //TODO implement custom logic to define if @param Export $export is owned by @param User $user
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
        return $user->hasPermissionTo('view_deleted exports');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param Export $export
     * @param bool $viewAll
     * @param bool $viewOwn
     * @return bool
     */
    public function dt_view(User $user, Export $export, bool $viewAll, bool $viewOwn)
    {
        if($viewAll) {
            return true;
        }

        if($viewOwn) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create exports.
     *
     * @param  \App\Models\Admin\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the Export.
     *
     * @param  \App\Models\Admin\User  $user
     * @param  \App\Models\Admin\Export  $export
     * @return mixed
     */
    public function update(User $user, Export $export)
    {
        return false;
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param Export $export
     * @param bool $updateAll
     * @param bool $updateOwn
     * @return bool
     */
    public function dt_update(User $user, Export $export, bool $updateAll, bool $updateOwn)
    {
        if($updateAll) {
            return true;
        }

        if($updateOwn) {
            //TODO implement custom logic to define if @param Export $export is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @param Export $export
     * @return bool
     * @throws \Exception
     */
    public function delete(User $user, Export $export)
    {
        if ($export->state == 'in_progress' || ($export->deleted && !$this->delete_forever($user))) {
            return false;
        }

        if($user->hasPermissionTo('delete_all exports')) {
            return true;
        }

        if($user->hasPermissionTo('delete_own exports')) {
            return $export->creator_id == $user->id;
        }

        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function delete_forever(User $user)
    {
        return $user->hasPermissionTo('delete_forever faq_categories');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\Export $export
     * @param $deleteAll
     * @param $deleteOwn
     * @return bool
     */
    public function dt_delete(User $user, Export $export, $deleteAll, $deleteOwn)
    {
        if ($export->deleted && !$this->delete_forever($user)) {
            return false;
        }

        if($deleteAll) {
            return true;
        }

        if($deleteOwn) {
            return $export->creator_id == $user->id;
            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @param Export $export
     * @return bool
     */
    public function restore(User $user, Export $export)
    {
        if($user->hasPermissionTo('restore_all exports')) {
            return true;
        }

        if($user->hasPermissionTo('restore_own exports')) {
            //TODO implement custom logic to define if @param Export $export is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @param Export $export
     * @param $restoreAll
     * @param $restoreOwn
     * @return bool
     */
    public function dt_restore(User $user, Export $export, $restoreAll, $restoreOwn)
    {
        if (!$export->deleted) {
            return false;
        }

        if($restoreAll) {
            return true;
        }

        if($restoreOwn) {
            //TODO implement custom logic to define if @param Export $export is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the Export.
     *
     * @param  \App\Models\Admin\User  $user
     * @param  \App\Models\Admin\Export  $export
     * @return mixed
     */
    public function forceDelete(User $user, Export $export)
    {
        //
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\Export $export
     * @return bool
     * @throws \Exception
     */
    public function download(User $user, Export $export)
    {
        if($export->state != 'completed') {
            return false;
        }

        if($user->hasPermissionTo('download_all exports')) {
            return true;
        }

        if($user->hasPermissionTo('download_own exports')) {
            return $export->creator_id == $user->id;
        }

        return false;
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\Export $export
     * @return bool
     * @throws \Exception
     */
    public function dt_download(User $user, Export $export, $downloadAll, $downloadOwn)
    {
        if($export->state != 'completed') {
            return false;
        }

        if($downloadAll) {
            return true;
        }

        if($downloadOwn) {
            return $export->creator_id == $user->id;
        }

        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function clear_old(User $user)
    {
        return $user->hasPermissionTo('clear_old exports');
    }
}
