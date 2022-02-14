<?php

namespace App\Models\Admin;

use App\Traits\Translations\Admin\PermissionTranslation;

class Permission extends \Spatie\Permission\Models\Permission
{
    use PermissionTranslation;
}
