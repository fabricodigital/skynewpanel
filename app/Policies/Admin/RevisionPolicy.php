<?php

namespace App\Policies\Admin;

use App\Models\Admin\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RevisionPolicy
{
    use HandlesAuthorization;

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     */
    public function view_index(User $user)
    {
        return $user->hasPermissionTo('view_all revisions');
    }

    /**
     * @param \App\Models\Admin\User $user
     * @return bool
     */
    public function view_all(User $user)
    {
        return $user->hasPermissionTo('view_all revisions');
    }
}
