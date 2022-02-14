<?php

namespace App\Traits\Translations\Admin;

use App\Traits\Translations\AttributeTranslation;
use App\Traits\Translations\ModelTranslation;

trait NotificationTranslation
{
    use AttributeTranslation;
    use ModelTranslation;

    /**
     * @var array
     */
    protected $translationAttributes = ['title', 'text'];

    /**
     * @return array
     */
    private static function getTranslationsConfig()
    {
        return [
            'titles' => [
                'singular'  => __('Notification'),
                'plural'    => __('Notifications'),
            ],
            'attributes' => [
                'title' => [
                    'translation'   => __('title-form-label')
                ],
                'text' => [
                    'translation'   => __('text-form-label')
                ],
                'start' => [
                    'translation'   => __('start-form-label')
                ],
                'end' => [
                    'translation'   => __('end-form-label')
                ],
                'roles' => [
                    'translation'   => __('roles-form-label')
                ],
                'attachments' => [
                    'translation'   => __('Attachments')
                ],
                'deleted' => [
                    'translation'   => __('deleted-form-label'),
                    'enum_translations' => [
                        1 => __('Yes'),
                        0 => __('No'),
                    ]
                ],
            ]
        ];
    }
}
