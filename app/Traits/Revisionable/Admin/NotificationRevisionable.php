<?php

namespace App\Traits\Revisionable\Admin;

use App\Models\Admin\Role;
use App\Traits\Revisionable\Revisionable;

trait NotificationRevisionable
{
    use Revisionable;

    protected $revisionableAttributes = ['title', 'text', 'start', 'end'];

    protected $revisionableMediaCollections = ['attachments'];

    protected $revisionableManyToManyRelations = [
        [
            'input_name' => 'roles',
            'related_title_attributes' => ['name'],
            'class' => Role::class,
            'relation' => 'roles',
        ],
    ];

    public static $revisionableRoute = 'admin.notifications.show';

    public static $revisionableTags = [
        'roles',
        'roles-form-label'
    ];
}

