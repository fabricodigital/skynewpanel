<?php

namespace App\Policies\Admin;

use App\Models\Admin\User;
use App\Models\Admin\Promotion;
use Illuminate\Auth\Access\HandlesAuthorization;

class PromotionPolicy
{
    use HandlesAuthorization;

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     */
    public function view_index(User $user)
    {
        return $user->hasPermissionTo('view_all promotions')
            || $user->hasPermissionTo('view_own promotions');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     * @throws \Exception
     */
    public function view_all(User $user)
    {
        return $user->hasPermissionTo('view_all promotions');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\Promotion $promotion
     * @return bool
     */
    public function view(User $user, Promotion $promotion)
    {
        if (!empty($promotion->deleted_at) && !$this->view_deleted($user)) {
            return false;
        }

        if($user->hasPermissionTo('view_all promotions')) {
            return true;
        }

        if($user->hasPermissionTo('view_all promotions')) {
            //TODO implement custom logic to define if @param Promotion $promotion is owned by @param User $user
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
        return $user->hasPermissionTo('view_deleted promotions');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\Promotion $promotion
     * @param bool $viewAll
     * @param bool $viewOwn
     * @return bool
     */
    public function dt_view(User $user, Promotion $promotion, bool $viewAll, bool $viewOwn)
    {
        if($viewAll) {
            return true;
        }

        if($viewOwn) {
            //TODO implement custom logic to define if @param Promotion $promotion is owned by @param User $user
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
        return $user->hasPermissionTo('create promotions');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param Promotion $promotion
     * @return bool
     * @throws \Exception
     */
    public function update(User $user, Promotion $promotion)
    {
        if($user->hasPermissionTo('update_all promotions')) {
            return true;
        }

        if($user->hasPermissionTo('update_own promotions')) {
            //TODO implement custom logic to define if @param Promotion $promotion is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @param Promotion $promotion
     * @param bool $updateAll
     * @param bool $updateOwn
     * @return bool
     */
    public function dt_update(User $user, Promotion $promotion, bool $updateAll, bool $updateOwn)
    {
        if ($promotion->deleted) {
            return false;
        }

        if($updateAll) {
            return true;
        }

        if($updateOwn) {
            //TODO implement custom logic to define if @param Promotion $promotion is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\Promotion $promotion
     * @return bool
     * @throws \Exception
     */
    public function delete(User $user, Promotion $promotion)
    {
        if ($promotion->deleted && !$this->delete_forever($user)) {
            return false;
        }

        if($user->hasPermissionTo('delete_all promotions')) {
            return true;
        }

        if($user->hasPermissionTo('delete_own promotions')) {
            //TODO implement custom logic to define if @param Promotion $promotion is owned by @param User $user
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
        return $user->hasPermissionTo('delete_forever promotions');
    }

    /**
     * @param User $user
     * @param Promotion $promotion
     * @param $deleteAll
     * @param $deleteOwn
     * @return bool
     */
    public function dt_delete(User $user, Promotion $promotion, $deleteAll, $deleteOwn)
    {
        if ($promotion->deleted && !$this->delete_forever($user)) {
            return false;
        }

        if($deleteAll) {
            return true;
        }

        if($deleteOwn) {
            //TODO implement custom logic to define if @param Promotion $promotion is owned by @param User $user
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
     * @param Promotion $promotion
     * @return bool
     */
    public function delete_media(User $user, Promotion $promotion)
    {
        return true;
    }

    /**
     * @param User $user
     * @param Promotion $promotion
     * @return bool
     */
    public function restore(User $user, Promotion $promotion)
    {
        if($user->hasPermissionTo('restore_all promotions')) {
            return true;
        }

        if($user->hasPermissionTo('restore_own promotions')) {
            //TODO implement custom logic to define if @param Promotion $promotion is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @param Promotion $promotion
     * @param $restoreAll
     * @param $restoreOwn
     * @return bool
     */
    public function dt_restore(User $user, Promotion $promotion, $restoreAll, $restoreOwn)
    {
        if (!$promotion->deleted) {
            return false;
        }

        if($restoreAll) {
            return true;
        }

        if($restoreOwn) {
            //TODO implement custom logic to define if @param Promotion $promotion is owned by @param User $user
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
        return $user->hasPermissionTo('export promotions');
    }
}
