<?php

namespace App\Traits\Translations\Admin;

use App\Traits\Translations\AttributeTranslation;

trait DashboardTranslation
{
    use AttributeTranslation;

    /**
     * @return array
     */
    private static function getTranslationsConfig()
    {
        return [
            'titles' => [
                'singular'  => __('Dashboard'),
                'plural'    => __('Dashboards'),
            ],
            'attributes' => [
                'name' => [
                    'translation'   => __('name-form-label')
                ],
                'description' => [
                    'translation'   => __('description-form-label')
                ],
                'role' => [
                    'translation'   => __('role-form-label')
                ],
                'account' => [
                    'translation'   => __('account-form-label')
                ],
                'widgets' => [
                    'translation'   => __('widgets-form-label')
                ],
                'deleted' => [
                    'translation'   => __('deleted-form-label'),
                    'enum_translations' => [
                        0 => __('No'),
                        1 => __('Yes')
                    ]
                ],
            ]
        ];
    }
}
