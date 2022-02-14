<?php

return [
    [
        'label' => "__('Settings')",
        'children' => [
            [
                'label' => "__('Accounts')",
                'permission_target' => 'accounts',
                'permissions' => [
                    'create' => "__('create-permission')",
                    'view_all' => "__('view_all-permission')",
                    'view_own' => "__('view_own-permission')",
                    'view_deleted' => "__('view_deleted-permission')",
                    'update_all' => "__('update_all-permission')",
                    'update_own' => "__('update_own-permission')",
                    'delete_all' => "__('delete_all-permission')",
                    'delete_own' => "__('delete_own-permission')",
                    'delete_forever' => "__('delete_forever-permission')",
                    'restore_all' => "__('restore_all-permission')",
                    'restore_own' => "__('restore_own-permission')",
                    'export' => "__('export-permission')",
                    'switch_account' => "__('switch_account-permission')",
                ]
            ],
            [
                'label' => "__('Users')",
                'permission_target' => 'users',
                'permissions' => [
                    'create' => "__('create-permission')",
                    'view_all' => "__('view_all-permission')",
                    'view_own' => "__('view_own-permission')",
                    'view_deleted' => "__('view_deleted-permission')",
                    'update_all' => "__('update_all-permission')",
                    'update_own' => "__('update_own-permission')",
                    'delete_all' => "__('delete_all-permission')",
                    'delete_own' => "__('delete_own-permission')",
                    'delete_forever' => "__('delete_forever-permission')",
                    'restore_all' => "__('restore_all-permission')",
                    'restore_own' => "__('restore_own-permission')",
                    'view_sensitive_data' => "__('view_sensitive_data-permission')",
                    'update_sensitive_data' => "__('update_sensitive_data-permission')",
                    'export' => "__('export-permission')",
                    'impersonate' => "__('impersonate-permission')",
                    'link_profiles' => "__('link_profiles-permission')",
                ]
            ],
            [
                'label' => "__('Roles')",
                'permission_target' => 'roles',
                'permissions' => [
                    'create' => "__('create-permission')",
                    'view_all' => "__('view_all-permission')",
                    'view_own' => "__('view_own-permission')",
                    'view_deleted' => "__('view_deleted-permission')",
                    'update_all' => "__('update_all-permission')",
                    'update_own' => "__('update_own-permission')",
                    'delete_all' => "__('delete_all-permission')",
                    'delete_own' => "__('delete_own-permission')",
                    'delete_forever' => "__('delete_forever-permission')",
                    'restore_all' => "__('restore_all-permission')",
                    'restore_own' => "__('restore_own-permission')",
                    'export' => "__('export-permission')",
                ]
            ],
            [
                'label' => "__('Audit log')",
                'permission_target' => 'revisions',
                'permissions' => [
                    'view_all' => "__('view_all-permission')",
                ]
            ],
        ],
    ],
    [
        'label' => "__('FAQ')",
        'children' => [
            [
                'label' => "__('Categories')",
                'permission_target' => 'faq_categories',
                'permissions' => [
                    'create' => "__('create-permission')",
                    'view_all' => "__('view_all-permission')",
                    'view_own' => "__('view_own-permission')",
                    'view_deleted' => "__('view_deleted-permission')",
                    'update_all' => "__('update_all-permission')",
                    'update_own' => "__('update_own-permission')",
                    'delete_all' => "__('delete_all-permission')",
                    'delete_own' => "__('delete_own-permission')",
                    'delete_forever' => "__('delete_forever-permission')",
                    'restore_all' => "__('restore_all-permission')",
                    'restore_own' => "__('restore_own-permission')",
                    'export' => "__('export-permission')",
                ]
            ],
            [
                'label' => "__('Questions')",
                'permission_target' => 'faq_questions',
                'permissions' => [
                    'create' => "__('create-permission')",
                    'view_all' => "__('view_all-permission')",
                    'view_own' => "__('view_own-permission')",
                    'view_deleted' => "__('view_deleted-permission')",
                    'update_all' => "__('update_all-permission')",
                    'update_own' => "__('update_own-permission')",
                    'delete_all' => "__('delete_all-permission')",
                    'delete_own' => "__('delete_own-permission')",
                    'delete_forever' => "__('delete_forever-permission')",
                    'restore_all' => "__('restore_all-permission')",
                    'restore_own' => "__('restore_own-permission')",
                    'export' => "__('export-permission')",
                ]
            ],
        ],
    ],
    [
        'label' => "__('Widgets')",
        'permission_target' => 'widgets',
        'permissions' => [
            'create' => "__('create-permission')",
            'view_all' => "__('view_all-permission')",
            'view_own' => "__('view_own-permission')",
            'view_deleted' => "__('view_deleted-permission')",
            'update_all' => "__('update_all-permission')",
            'update_own' => "__('update_own-permission')",
            'delete_all' => "__('delete_all-permission')",
            'delete_own' => "__('delete_own-permission')",
            'delete_forever' => "__('delete_forever-permission')",
            'restore_all' => "__('restore_all-permission')",
            'restore_own' => "__('restore_own-permission')",
            'export' => "__('export-permission')",
        ]
    ],
    [
        'label' => "__('Dashboards')",
        'permission_target' => 'dashboards',
        'permissions' => [
            'create' => "__('create-permission')",
            'view_all' => "__('view_all-permission')",
            'view_own' => "__('view_own-permission')",
            'view_deleted' => "__('view_deleted-permission')",
            'update_all' => "__('update_all-permission')",
            'update_own' => "__('update_own-permission')",
            'delete_all' => "__('delete_all-permission')",
            'delete_own' => "__('delete_own-permission')",
            'delete_forever' => "__('delete_forever-permission')",
            'restore_all' => "__('restore_all-permission')",
            'restore_own' => "__('restore_own-permission')",
            'export' => "__('export-permission')",
        ]
    ],
    [
        'label' => "__('Messages')",
        'permission_target' => 'messages',
        'permissions' => [
            'view_all' => "__('view_all-permission')",
            'view_own' => "__('view_own-permission')",
            'create_direct' => "__('create_direct-permission')",
            'create_help' => "__('create_help-permission')",
        ]
    ],
    [
        'label' => "__('Notifications')",
        'permission_target' => 'notifications',
        'permissions' => [
            'create' => "__('create-permission')",
            'view_all' => "__('view_all-permission')",
            'view_own' => "__('view_own-permission')",
            'view_deleted' => "__('view_deleted-permission')",
            'update_all' => "__('update_all-permission')",
            'update_own' => "__('update_own-permission')",
            'delete_all' => "__('delete_all-permission')",
            'delete_own' => "__('delete_own-permission')",
            'delete_forever' => "__('delete_forever-permission')",
            'restore_all' => "__('restore_all-permission')",
            'restore_own' => "__('restore_own-permission')",
            'export' => "__('export-permission')",
        ]
    ],
    [
        'label' => "__('Calendar')",
        'permission_target' => 'events',
        'permissions' => [
            'create' => "__('create-permission')",
            'view_all' => "__('view_all-permission')",
            'view_own' => "__('view_own-permission')",
            'update_all' => "__('update_all-permission')",
            'update_own' => "__('update_own-permission')",
            'delete_all' => "__('delete_all-permission')",
            'delete_own' => "__('delete_own-permission')",
            'export' => "__('export-permission')",
        ]
    ],
    [
        'label' => "__('Exports')",
        'permission_target' => 'exports',
        'permissions' => [
            'view_all' => "__('view_all-permission')",
            'view_own' => "__('view_own-permission')",
            'view_deleted' => "__('view_deleted-permission')",
            'download_all' => "__('download_all-permission')",
            'download_own' => "__('download_own-permission')",
            'delete_all' => "__('delete_all-permission')",
            'delete_own' => "__('delete_own-permission')",
            'delete_forever' => "__('delete_forever-permission')",
            'restore_all' => "__('restore_all-permission')",
            'restore_own' => "__('restore_own-permission')",
            'clear_old' => "__('clear_old-permission')",
        ]
    ],
    /* crud:create add section */
    [
        'label' => "__('Notes')",
        'permission_target' => 'notes',
        'permissions' => [
            'create' => "__('create-permission')",
            'view_all' => "__('view_all-permission')",
            'view_own' => "__('view_own-permission')",
            'view_deleted' => "__('view_deleted-permission')",
            'update_all' => "__('update_all-permission')",
            'update_own' => "__('update_own-permission')",
            'delete_all' => "__('delete_all-permission')",
            'delete_own' => "__('delete_own-permission')",
            'delete_forever' => "__('delete_forever-permission')",
            'restore_all' => "__('restore_all-permission')",
            'restore_own' => "__('restore_own-permission')",
            'export' => "__('export-permission')",
        ]
    ],
];
