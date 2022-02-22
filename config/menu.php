<?php

return [
    'uri_segment' => 1,
    'sections' => [
        [
            'type' => 'link',
            'title' => "__('Dashboard')",
            'icon' => 'fa-dashboard',
            'route' => 'admin.homepage',
            'uri_segments' => ['']
        ],
        [
            'type' => 'header',
            'title' => "__('PLATFORM')",
            'sections' => [
                [
                    'type' => 'dropdown',
                    'title' => "__('Settings')",
                    'icon' => 'fa-gear',
                    'sections' => [
                        [
                            'type' => 'link',
                            'title' => "App\Models\Admin\Account::getTitleTrans()",
                            'icon' => 'fa-users',
                            'route' => 'admin.accounts.index',
                            'uri_segments' => ['accounts'],
                            'permission_class' => \App\Models\Admin\Account::class
                        ],
                        [
                            'type' => 'link',
                            'title' => "App\Models\Admin\User::getTitleTrans()",
                            'icon' => 'fa-user',
                            'route' => 'admin.users.index',
                            'uri_segments' => ['users'],
                            'permission_class' => \App\Models\Admin\User::class
                        ],
                        [
                            'type' => 'link',
                            'title' => "App\Models\Admin\Role::getTitleTrans()",
                            'icon' => 'fa-briefcase',
                            'route' => 'admin.roles.index',
                            'uri_segments' => ['roles'],
                            'permission_class' => \App\Models\Admin\Role::class,
                        ],
                        [
                            'type' => 'link',
                            'title' => "App\Models\Admin\Permission::getTitleTrans()",
                            'icon' => 'fa-ban',
                            'route' => 'admin.permissions.index',
                            'uri_segments' => ['permissions'],
                            'permission_class' => \App\Models\Admin\Permission::class,
                        ],
                        [
                            'type' => 'link',
                            'title' => "App\Models\Admin\Revision::getTitleTrans()",
                            'icon' => 'fa-archive',
                            'route' => 'admin.revisions.index',
                            'uri_segments' => ['revisions'],
                            'permission_class' => \App\Models\Admin\Revision::class,
                        ]
                    ]
                ],
                [
                    'type' => 'dropdown',
                    'title' => "__('FAQ')",
                    'icon' => 'fa-question',
                    'sections' => [
                        [
                            'type' => 'link',
                            'title' => "__('Categories')",
                            'icon' => 'fa-folder',
                            'route' => 'admin.faq-categories.index',
                            'uri_segments' => ['faq-categories'],
                            'permission_class' => \App\Models\Admin\FaqCategory::class
                        ],
                        [
                            'type' => 'link',
                            'title' => "__('Questions')",
                            'icon' => 'fa-question',
                            'route' => 'admin.faq-questions.index',
                            'uri_segments' => ['faq-questions'],
                            'permission_class' => \App\Models\Admin\FaqQuestion::class,
                        ],
                    ]
                ],
            ]
        ],
        [
            'type' => 'link',
            'title' => "__('Search')",
            'icon' => 'fa-user-circle-o',
            'route' => 'admin.finclient',
            'uri_segments' => [''],
        ],
        [
            'type' => 'header',
            'title' => "__('ACCOUNT')",
            'sections' => [

//                [
//                    'type' => 'link',
//                    'title' => "__('Widgets')",
//                    'icon' => 'fa-th-large',
//                    'route' => 'admin.widgets.index',
//                    'uri_segments' => ['widgets'],
//                    'permission_class' => \App\Models\Admin\Widget::class,
//                ],
//                [
//                    'type' => 'link',
//                    'title' => "__('Dashboards')",
//                    'icon' => 'fa-dashboard',
//                    'route' => 'admin.dashboards.index',
//                    'uri_segments' => ['dashboards'],
//                    'permission_class' => \App\Models\Admin\Dashboard::class,
//                ],
                [
                    'type' => 'link',
                    'title' => "__('Profile')",
                    'icon' => 'fa-user-circle-o',
                    'route' => 'admin.profile.edit',
                    'uri_segments' => ['profile'],
                ],
//                [
//                    'type' => 'link',
//                    'title' => "__('Messages')",
//                    'icon' => 'fa-envelope',
//                    'route' => 'admin.messenger.index',
//                    'uri_segments' => ['messenger'],
//                    'permission_class' => \App\Models\Admin\MessengerTopic::class,
//                    'append_html' => "'<sidebar-unread-messages-counter :unread-messages=\''. json_encode(App::make('unreadMessagesCount')) . '\'></sidebar-unread-messages-counter>'",
//                ],
//                [
//                    'type' => 'link',
//                    'title' => "App\Models\Admin\Notification::getTitleTrans()",
//                    'icon' => 'fa-bell',
//                    'route' => 'admin.notifications.index',
//                    'uri_segments' => ['notifications'],
//                    'permission_class' => \App\Models\Admin\Notification::class,
//                    'append_html' => "'<sidebar-unread-notifications-counter :unread-notifications-count=\'' . App::make('unreadNotificationsCount') . '\'></sidebar-unread-notifications-counter>'",
//                ],
//                [
//                    'type' => 'link',
//                    'title' => "__('Calendar')",
//                    'icon' => 'fa-calendar',
//                    'route' => 'admin.calendar',
//                    'uri_segments' => ['calendar'],
//                    'permission_class' => \App\Models\Admin\Event::class,
//                    'append_html' => "'<sidebar-unread-events-counter :init-unread-events-count=\'' . Auth::user()->unreadEventsCount() . '\'></sidebar-unread-events-counter>'",
//                ],
//                [
//                    'type' => 'link',
//                    'title' => "App\Models\Admin\Export::getTitleTrans()",
//                    'icon' => 'fa-download',
//                    'route' => 'admin.exports.index',
//                    'uri_segments' => ['exports'],
//                    'permission_class' => \App\Models\Admin\Export::class,
//                ]
            ]
        ],
        [
            'type' => 'header',
            'title' => "__('LANGUAGES')",
            'sections' => [
                [
                    'type' => 'partial',
                    'partial_file' => 'partials.sidebar._translations',
                    'title' => "__('Translations')",
                    'icon' => 'fa-language',
                    'route' => 'admin.translations.index',
                    'uri_segments' => ['translations'],
                ],
                [
                    'type' => 'partial',
                    'partial_file' => 'partials.sidebar._languages'
                ],
            ]
        ],
        [
            'type' => 'link',
            'title' => "__('Logout')",
            'icon' => 'fa-arrow-left',
            'route' => 'logout'
        ]
    ]
];
