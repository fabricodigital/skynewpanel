<?php

namespace App\Traits\Translations\Admin;

use App\Traits\Translations\AttributeTranslation;

trait AccountTranslation
{
    use AttributeTranslation;

    /**
     * @var array
     */
    protected $translationAttributes = ['name'];

    /**
     * @return array
     */
    private static function getTranslationsConfig()
    {
        return [
            'titles' => [
                'singular'  => __('Account'),
                'plural'    => __('Accounts'),
            ],
            'attributes' => [
                'name' => [
                    'translation'   => __('name-form-label')
                ],
                'widgets' => [
                    'translation'   => __('widgets-form-label')
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
