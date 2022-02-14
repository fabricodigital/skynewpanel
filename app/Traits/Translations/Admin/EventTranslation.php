<?php

namespace App\Traits\Translations\Admin;

use App\Traits\Translations\AttributeTranslation;
use App\Traits\Translations\ModelTranslation;

trait EventTranslation
{
    use AttributeTranslation;
    use ModelTranslation;

    /**
     * @var array
     */
    protected $translationAttributes = ['title', 'description'];

    /**
     * @return array
     */
    private static function getTranslationsConfig()
    {
        return [
            'titles' => [
                'singular'  => __('Event'),
                'plural'    => __('Events'),
            ],
            'attributes' => [
                'title' => [
                    'translation'   => __('title-form-label')
                ],
                'color' => [
                    'translation'   => __('color-form-label')
                ],
                'description' => [
                    'translation'   => __('description-form-label')
                ],
                'locale' => [
                    'translation'   => __('locale-form-label')
                ],
                'start' => [
                    'translation'   => __('start-form-label')
                ],
                'end' => [
                    'translation'   => __('end-form-label')
                ],
                'creator_id' => [
                    'translation'   => __('creator-form-label')
                ],
                'roles' => [
                    'translation'   => __('roles-form-label')
                ],
                'users' => [
                    'translation'   => __('users-form-label')
                ],
                'attachments' => [
                    'translation'   => __('Attachments')
                ],
            ]
        ];
    }
}
