<?php

namespace App\Traits\Revisionable\Admin;

use App\Models\Admin\Role;
use App\Models\Admin\User;
use App\Traits\Revisionable\Revisionable;

trait EventRevisionable
{
    use Revisionable;

    protected $revisionableAttributes = ['title', 'color', 'description', 'start', 'end'];

    protected $revisionableMediaCollections = ['attachments'];

    protected $revisionableBelongsToRelations = [
        [
            'attribute' => 'creator_id',
            'relation' => 'creator',
            'related_title_attributes' => ['name', 'surname'],
            'class' => User::class,
        ],
    ];

    protected $revisionableManyToManyRelations = [
        [
            'input_name' => 'roles',
            'related_title_attributes' => ['name'],
            'class' => Role::class,
            'relation' => 'roles',
        ],
        [
            'input_name' => 'users',
            'related_title_attributes' => ['name', 'surname'],
            'class' => User::class,
            'relation' => 'users',
        ],

    ];

    public static $revisionableTags = [
        'roles',
        'roles-form-label'
    ];

    public static $revisionableColor = [
        'color',
        'color-form-label'
    ];
}

