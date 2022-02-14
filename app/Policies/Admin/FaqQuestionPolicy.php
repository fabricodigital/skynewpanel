<?php

namespace App\Policies\Admin;

use App\Models\Admin\User;
use App\Models\Admin\FaqQuestion;
use Illuminate\Auth\Access\HandlesAuthorization;

class FaqQuestionPolicy
{
    use HandlesAuthorization;

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     */
    public function view_index(User $user)
    {
        return $user->hasPermissionTo('view_all faq_questions')
            || $user->hasPermissionTo('view_own faq_questions');
    }

    /**
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    public function view_all(User $user)
    {
        return $user->hasPermissionTo('view_all faq_categories');
    }

    /**
     * @param User $user
     * @param FaqQuestion $faqQuestion
     * @return bool
     */
    public function view(User $user, FaqQuestion $faqQuestion)
    {
        if (!empty($faqQuestion->deleted_at) && !$this->view_deleted($user)) {
            return false;
        }

        if($user->hasPermissionTo('view_all faq_questions')) {
            return true;
        }

        if($user->hasPermissionTo('view_all faq_questions')) {
            //TODO implement custom logic to define if @param FaqQuestion $faqQuestion is owned by @param User $user
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
        return $user->hasPermissionTo('view_deleted faq_questions');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param FaqQuestion $faqQuestion
     * @param bool $viewAll
     * @param bool $viewOwn
     * @return bool
     */
    public function dt_view(User $user, FaqQuestion $faqQuestion, bool $viewAll, bool $viewOwn)
    {
        if($viewAll) {
            return true;
        }

        if($viewOwn) {
            //TODO implement custom logic to define if @param FaqCategory $faqCategory is owned by @param User $user
            return false;
        }
    }

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     * @throws \Exception
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create faq_questions');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @param FaqQuestion $faqQuestion
     * @return bool
     * @throws \Exception
     */
    public function update(User $user, FaqQuestion $faqQuestion)
    {
        if($user->hasPermissionTo('update_all faq_questions')) {
            return true;
        }

        if($user->hasPermissionTo('update_own faq_questions')) {
            //TODO implement custom logic to define if @param FaqQuestion $faqQuestion is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @param FaqQuestion $faqQuestion
     * @param bool $updateAll
     * @param bool $updateOwn
     * @return bool
     */
    public function dt_update(User $user, FaqQuestion $faqQuestion, bool $updateAll, bool $updateOwn)
    {
        if ($faqQuestion->deleted) {
            return false;
        }

        if($updateAll) {
            return true;
        }

        if($updateOwn) {
            //TODO implement custom logic to define if @param FaqQuestion $faqQuestion is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @param FaqQuestion $faqQuestion
     * @return bool
     * @throws \Exception
     */
    public function delete(User $user, FaqQuestion $faqQuestion)
    {
        if ($faqQuestion->deleted && !$this->delete_forever($user)) {
            return false;
        }

        if($user->hasPermissionTo('delete_all faq_questions')) {
            return true;
        }

        if($user->hasPermissionTo('delete_own faq_questions')) {
            //TODO implement custom logic to define if @param FaqQuestion $faqQuestion is owned by @param User $user
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
        return $user->hasPermissionTo('delete_forever faq_questions');
    }

    /**
     * @param User $user
     * @param FaqQuestion $faqQuestion
     * @param $deleteAll
     * @param $deleteOwn
     * @return bool
     */
    public function dt_delete(User $user, FaqQuestion $faqQuestion, $deleteAll, $deleteOwn)
    {
        if ($faqQuestion->deleted && !$this->delete_forever($user)) {
            return false;
        }

        if($deleteAll) {
            return true;
        }

        if($deleteOwn) {
            //TODO implement custom logic to define if @param FaqQuestion $faqQuestion is owned by @param User $user
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
     * @param FaqQuestion $faqQuestion
     * @return bool
     */
    public function delete_media(User $user, FaqQuestion $faqQuestion)
    {
        if($user->hasPermissionTo('update_all faq_questions')) {
            return true;
        }

        if($user->hasPermissionTo('update_own faq_questions')) {
            //TODO implement custom logic to define if @param FaqQuestion $faqQuestion is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @param FaqQuestion $faqQuestion
     * @return bool
     */
    public function restore(User $user, FaqQuestion $faqQuestion)
    {
        if($user->hasPermissionTo('restore_all faq_questions')) {
            return true;
        }

        if($user->hasPermissionTo('restore_own faq_questions')) {
            //TODO implement custom logic to define if @param FaqQuestion $faqQuestion is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @param FaqQuestion $faqQuestion
     * @param $restoreAll
     * @param $restoreOwn
     * @return bool
     */
    public function dt_restore(User $user, FaqQuestion $faqQuestion, $restoreAll, $restoreOwn)
    {
        if (!$faqQuestion->deleted) {
            return false;
        }

        if($restoreAll) {
            return true;
        }

        if($restoreOwn) {
            //TODO implement custom logic to define if @param FaqQuestion $faqQuestion is owned by @param User $user
            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    public function export(User $user)
    {
        return $user->hasPermissionTo('export faq_questions');
    }
}
