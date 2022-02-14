<?php

namespace App\Policies\Admin;

use App\Models\Admin\User;
use App\Models\Admin\FaqCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class FaqCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     */
    public function view_index(User $user)
    {
        return $user->hasPermissionTo('view_all faq_categories')
            || $user->hasPermissionTo('view_own faq_categories');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     * @throws \Exception
     */
    public function view_all(User $user)
    {
        return $user->hasPermissionTo('view_all faq_categories');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\FaqCategory $faqCategory
     * @return bool
     */
    public function view(User $user, FaqCategory $faqCategory)
    {
        if (!empty($faqCategory->deleted_at) && !$this->view_deleted($user)) {
            return false;
        }

        if($user->hasPermissionTo('view_all faq_categories')) {
            return true;
        }

        if($user->hasPermissionTo('view_own faq_categories')) {
            //TODO implement custom logic to define if @param FaqCategory $faqCategory is owned by @param User $user
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
        return $user->hasPermissionTo('view_deleted faq_categories');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\FaqCategory $faqCategory
     * @param bool $viewAll
     * @param bool $viewOwn
     * @return bool
     */
    public function dt_view(User $user, FaqCategory $faqCategory, bool $viewAll, bool $viewOwn)
    {
        if($viewAll) {
            return true;
        }

        if($viewOwn) {
            //TODO implement custom logic to define if @param FaqCategory $faqCategory is owned by @param User $user
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
        return $user->hasPermissionTo('create faq_categories');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param FaqCategory $faqCategory
     * @return bool
     * @throws \Exception
     */
    public function update(User $user, FaqCategory $faqCategory)
    {
        if($user->hasPermissionTo('update_all faq_categories')) {
            return true;
        }

        if($user->hasPermissionTo('update_own faq_categories')) {
            //TODO implement custom logic to define if @param FaqCategory $faqCategory is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @param FaqCategory $faqCategory
     * @param bool $updateAll
     * @param bool $updateOwn
     * @return bool
     */
    public function dt_update(User $user, FaqCategory $faqCategory, bool $updateAll, bool $updateOwn)
    {
        if ($faqCategory->deleted) {
            return false;
        }

        if($updateAll) {
            return true;
        }

        if($updateOwn) {
            //TODO implement custom logic to define if @param FaqCategory $faqCategory is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param \App\Models\Admin\FaqCategory $faqCategory
     * @return bool
     * @throws \Exception
     */
    public function delete(User $user, FaqCategory $faqCategory)
    {
        if ($faqCategory->deleted && !$this->delete_forever($user)) {
            return false;
        }

        if($user->hasPermissionTo('delete_all faq_categories')) {
            return true;
        }

        if($user->hasPermissionTo('delete_own faq_categories')) {
            //TODO implement custom logic to define if @param FaqCategory $faqCategory is owned by @param User $user
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
        return $user->hasPermissionTo('delete_forever faq_categories');
    }

    /**
     * @param User $user
     * @param FaqCategory $faqCategory
     * @param $deleteAll
     * @param $deleteOwn
     * @return bool
     */
    public function dt_delete(User $user, FaqCategory $faqCategory, $deleteAll, $deleteOwn)
    {
        if ($faqCategory->deleted && !$this->delete_forever($user)) {
            return false;
        }

        if($deleteAll) {
            return true;
        }

        if($deleteOwn) {
            //TODO implement custom logic to define if @param FaqCategory $faqCategory is owned by @param User $user
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
     * @param FaqCategory $faqCategory
     * @return bool
     */
    public function delete_media(User $user, FaqCategory $faqCategory)
    {
        return false;
    }

    /**
     * @param User $user
     * @param FaqCategory $faqCategory
     * @return bool
     */
    public function restore(User $user, FaqCategory $faqCategory)
    {
        if($user->hasPermissionTo('restore_all faq_categories')) {
            return true;
        }

        if($user->hasPermissionTo('restore_own faq_categories')) {
            //TODO implement custom logic to define if @param FaqCategory $faqCategory is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @param FaqCategory $faqCategory
     * @param $restoreAll
     * @param $restoreOwn
     * @return bool
     */
    public function dt_restore(User $user, FaqCategory $faqCategory, $restoreAll, $restoreOwn)
    {
        if (!$faqCategory->deleted) {
            return false;
        }

        if($restoreAll) {
            return true;
        }

        if($restoreOwn) {
            //TODO implement custom logic to define if @param FaqCategory $faqCategory is owned by @param User $user
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
        return $user->hasPermissionTo('export faq_categories');
    }
}
