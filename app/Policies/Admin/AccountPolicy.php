<?php

namespace App\Policies\Admin;

use App\Models\Admin\User;
use App\Models\Admin\Account;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountPolicy
{
    use HandlesAuthorization;

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     */
    public function view_index(User $user)
    {
        return $user->hasPermissionTo('view_all accounts')
            || $user->hasPermissionTo('view_own accounts');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     * @throws \Exception
     */
    public function view_all(User $user)
    {
        return $user->hasPermissionTo('view_all accounts');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\Account $account
     * @return bool
     */
    public function view(User $user, Account $account)
    {
        if (!empty($account->deleted_at) && !$this->view_deleted($user)) {
            return false;
        }

        if($user->hasPermissionTo('view_all accounts')) {
            return true;
        }

        if($user->hasPermissionTo('view_all accounts')) {
            //TODO implement custom logic to define if @param Account $account is owned by @param User $user
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
        return $user->hasPermissionTo('view_deleted accounts');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\Account $account
     * @param bool $viewAll
     * @param bool $viewOwn
     * @return bool
     */
    public function dt_view(User $user, Account $account, bool $viewAll, bool $viewOwn)
    {
        if($viewAll) {
            return true;
        }

        if($viewOwn) {
            //TODO implement custom logic to define if @param Account $account is owned by @param User $user
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
        return $user->hasPermissionTo('create accounts');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param Account $account
     * @return bool
     * @throws \Exception
     */
    public function update(User $user, Account $account)
    {
        if($user->hasPermissionTo('update_all accounts')) {
            return true;
        }

        if($user->hasPermissionTo('update_own accounts')) {
            //TODO implement custom logic to define if @param Account $account is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @param Account $account
     * @param bool $updateAll
     * @param bool $updateOwn
     * @return bool
     */
    public function dt_update(User $user, Account $account, bool $updateAll, bool $updateOwn)
    {
        if ($account->deleted) {
            return false;
        }

        if($updateAll) {
            return true;
        }

        if($updateOwn) {
            //TODO implement custom logic to define if @param Account $account is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\Account $account
     * @return bool
     * @throws \Exception
     */
    public function delete(User $user, Account $account)
    {
        if ($account->deleted && !$this->delete_forever($user)) {
            return false;
        }

        if($user->hasPermissionTo('delete_all accounts')) {
            return true;
        }

        if($user->hasPermissionTo('delete_own accounts')) {
            //TODO implement custom logic to define if @param Account $account is owned by @param User $user
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
        return $user->hasPermissionTo('delete_forever accounts');
    }

    /**
     * @param User $user
     * @param Account $account
     * @param $deleteAll
     * @param $deleteOwn
     * @return bool
     */
    public function dt_delete(User $user, Account $account, $deleteAll, $deleteOwn)
    {
        if ($account->deleted && !$this->delete_forever($user)) {
            return false;
        }

        if($deleteAll) {
            return true;
        }

        if($deleteOwn) {
            //TODO implement custom logic to define if @param Account $account is owned by @param User $user
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
     * @param Account $account
     * @return bool
     */
    public function delete_media(User $user, Account $account)
    {
        return true;
    }

    /**
     * @param User $user
     * @param Account $account
     * @return bool
     */
    public function restore(User $user, Account $account)
    {
        if($user->hasPermissionTo('restore_all accounts')) {
            return true;
        }

        if($user->hasPermissionTo('restore_own accounts')) {
            //TODO implement custom logic to define if @param Account $account is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @param Account $account
     * @param $restoreAll
     * @param $restoreOwn
     * @return bool
     */
    public function dt_restore(User $user, Account $account, $restoreAll, $restoreOwn)
    {
        if (!$account->deleted) {
            return false;
        }

        if($restoreAll) {
            return true;
        }

        if($restoreOwn) {
            //TODO implement custom logic to define if @param Account $account is owned by @param User $user
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
        return $user->hasPermissionTo('export accounts');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function switch_account(User $user)
    {
        return $user->hasPermissionTo('switch_account accounts');
    }
}
