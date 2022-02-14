<?php

namespace App\Traits\Revisionable\Admin;

use App\Models\Admin\Role;
use App\Traits\Revisionable\Revisionable;

trait UserRevisionable
{
    use Revisionable;

    protected $revisionableAttributes = ['name', 'surname', 'email', 'state', 'password'];

    protected $revisionableMediaCollections = ['profile-image'];

    protected $revisionableManyToManyRelations = [
        [
            'input_name' => 'roles',
            'related_title_attributes' => ['role_name'],
            'class' => Role::class,
            'relation' => 'roles',
        ],
    ];

    public static $revisionableRoute = 'admin.users.show';

    public static $revisionableEnums = [
        'state',
    ];

    public static $revisionableTags = [
        'roles',
    ];
}

