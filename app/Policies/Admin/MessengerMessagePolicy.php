<?php

namespace App\Policies\Admin;

use App\Models\Admin\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Admin\MessengerMessage;
use App\Models\Admin\Role;

class MessengerMessagePolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param \App\Models\Admin\MessengerMessage $messengerMessage
     * @return bool
     */
    public function delete_media(User $user, MessengerMessage $messengerMessage)
    {
        return false;
    }
}
