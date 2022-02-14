<?php

namespace App\Policies\Admin;

use App\Models\Admin\User;
use App\Models\Admin\Note;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotePolicy
{
    use HandlesAuthorization;

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     */
    public function view_index(User $user)
    {
        return $user->hasPermissionTo('view_all notes')
            || $user->hasPermissionTo('view_own notes');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     * @throws \Exception
     */
    public function view_all(User $user)
    {
        return $user->hasPermissionTo('view_all notes');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\Note $note
     * @return bool
     */
    public function view(User $user, Note $note)
    {
        if (!empty($note->deleted_at) && !$this->view_deleted($user)) {
            return false;
        }

        if($user->hasPermissionTo('view_all notes')) {
            return true;
        }

        if($user->hasPermissionTo('view_all notes')) {
            //TODO implement custom logic to define if @param Note $note is owned by @param User $user
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
        return $user->hasPermissionTo('view_deleted notes');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\Note $note
     * @param bool $viewAll
     * @param bool $viewOwn
     * @return bool
     */
    public function dt_view(User $user, Note $note, bool $viewAll, bool $viewOwn)
    {
        if($viewAll) {
            return true;
        }

        if($viewOwn) {
            //TODO implement custom logic to define if @param Note $note is owned by @param User $user
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
        return $user->hasPermissionTo('create notes');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param Note $note
     * @return bool
     * @throws \Exception
     */
    public function update(User $user, Note $note)
    {
        if($user->hasPermissionTo('update_all notes')) {
            return true;
        }

        if($user->hasPermissionTo('update_own notes')) {
            //TODO implement custom logic to define if @param Note $note is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @param Note $note
     * @param bool $updateAll
     * @param bool $updateOwn
     * @return bool
     */
    public function dt_update(User $user, Note $note, bool $updateAll, bool $updateOwn)
    {
        if ($note->deleted) {
            return false;
        }

        if($updateAll) {
            return true;
        }

        if($updateOwn) {
            //TODO implement custom logic to define if @param Note $note is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\Note $note
     * @return bool
     * @throws \Exception
     */
    public function delete(User $user, Note $note)
    {
        if ($note->deleted && !$this->delete_forever($user)) {
            return false;
        }

        if($user->hasPermissionTo('delete_all notes')) {
            return true;
        }

        if($user->hasPermissionTo('delete_own notes')) {
            //TODO implement custom logic to define if @param Note $note is owned by @param User $user
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
        return $user->hasPermissionTo('delete_forever notes');
    }

    /**
     * @param User $user
     * @param Note $note
     * @param $deleteAll
     * @param $deleteOwn
     * @return bool
     */
    public function dt_delete(User $user, Note $note, $deleteAll, $deleteOwn)
    {
        if ($note->deleted && !$this->delete_forever($user)) {
            return false;
        }

        if($deleteAll) {
            return true;
        }

        if($deleteOwn) {
            //TODO implement custom logic to define if @param Note $note is owned by @param User $user
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
     * @param Note $note
     * @return bool
     */
    public function delete_media(User $user, Note $note)
    {
        return true;
    }

    /**
     * @param User $user
     * @param Note $note
     * @return bool
     */
    public function restore(User $user, Note $note)
    {
        if($user->hasPermissionTo('restore_all notes')) {
            return true;
        }

        if($user->hasPermissionTo('restore_own notes')) {
            //TODO implement custom logic to define if @param Note $note is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @param Note $note
     * @param $restoreAll
     * @param $restoreOwn
     * @return bool
     */
    public function dt_restore(User $user, Note $note, $restoreAll, $restoreOwn)
    {
        if (!$note->deleted) {
            return false;
        }

        if($restoreAll) {
            return true;
        }

        if($restoreOwn) {
            //TODO implement custom logic to define if @param Note $note is owned by @param User $user
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
        return $user->hasPermissionTo('export notes');
    }
}
