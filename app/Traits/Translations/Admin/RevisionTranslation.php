<?php

namespace App\Traits\Translations\Admin;

use App\Traits\Translations\AttributeTranslation;

trait RevisionTranslation
{
    use AttributeTranslation;

    /**
     * @return array
     */
    private static function getTranslationsConfig()
    {
        return [
            'titles' => [
                'singular'  => __('Audit log'),
                'plural'    => __('Audit log'),
            ],
            'attributes' => [
                'model_type' => [
                    'translation'   => __('Section')
                ],
                'creator_id' => [
                    'translation'   => __('User'),
                ],
                'locale' => [
                    'translation'   => __('locale-form-label'),
                ],
                'type' => [
                    'translation'   => __('Action'),
                    'enum_translations' => [
                        'deleted' => __('Deleted'),
                        'created' => __('Created'),
                        'updated' => __('Updated'),
                    ]
                ],
                'old' => [
                    'translation'   => __('Old'),
                ],
                'new' => [
                    'translation'   => __('New'),
                ],
                'ip' => [
                    'translation'   => __('IP')
                ],
                'created_at' => [
                    'translation'   => __('Date'),
                ],
            ]
        ];
    }
}
