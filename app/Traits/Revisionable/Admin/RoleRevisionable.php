<?php

namespace App\Traits\Revisionable\Admin;

use App\Models\Admin\Role;
use App\Traits\Revisionable\Revisionable;

trait RoleRevisionable
{
    use Revisionable;

    protected $revisionableAttributes = [
        'role_name',
        'level'
    ];

    protected $revisionableManyToManyRelations = [
        [
            'input_name' => 'sub_roles',
            'related_title_attributes' => ['name'],
            'class' => Role::class,
            'relation' => 'subRoles',
        ],
    ];

    public static $revisionableRoute = 'admin.roles.show';
}

