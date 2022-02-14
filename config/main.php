<?php

return [
    'app' => [
        'credits' => env('APP_CREDITS'),
        'version' => '1.0'
    ],

    'repo_path' => env('REPO_PATH', null),

    /**
     * Languages used for translations
     */
    'available_languages' => [
        'en' => 'English',
        'it' => 'Italian',
    ],

    /**
     * Time intervals for user activity status
     * Used in User.php for calculated "activity_status" attribute
     */
    'activity_status_time_intervals' => [
        'offline' => env('ACTIVITY_STATUS_OFFLINE_INTERVAL', 15),
        'inactive' => env('ACTIVITY_STATUS_INACTIVE_INTERVAL', 7),
    ],

    'help_roles' => [
        'Help',
    ],

    'dashboard' => [
        'widgets' => [
            [
                'id' => 'widgets.calendar',
                'position' => [
                    'x' => 7,
                    'y' => 0,
                    'width' => 6,
                    'height' => 10
                ]
            ],
        ]
    ],

    'emails' => [
        'no_replay' => env('MAIL_NO_REPLAY', 'noreplay@admin-panel.com'),
        'mail_system_messages' => explode(',', env('MAIL_SYSTEM_MESSAGES', 'simeon.ivaylov.petrov@gmail.com')),
    ],

    'users' => [
        'admin' => [
            'name' => env('ADMIN_NAME', 'ADMIN'),
            'surname' => env('ADMIN_SURNAME', 'ADMIN'),
            'email' => env('ADMIN_EMAIL', 'admin@admin.com'),
            'password' => env('ADMIN_PASSWORD', '123456'),
        ]
    ]
];
