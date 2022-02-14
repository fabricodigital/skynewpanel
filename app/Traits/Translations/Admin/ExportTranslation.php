<?php

namespace App\Traits\Translations\Admin;

use App\Traits\Translations\AttributeTranslation;

trait ExportTranslation
{
    use AttributeTranslation;

    /**
     * @return array
     */
    private static function getTranslationsConfig()
    {
        return [
            'titles' => [
                'singular'  => __('Export'),
                'plural'    => __('Exports'),
            ],
            'attributes' => [
                'creator_id' => [
                    'translation'   => __('creator-form-label')
                ],
                'date_start' => [
                    'translation'   => __('date_start-form-label'),
                ],
                'date_end' => [
                    'translation'   => __('date_end-form-label')
                ],
                'state' => [
                    'translation'   => __('state-form-label'),
                    'enum_translations' => [
                        'in_progress'    => __('In progress'),
                        'completed'    => __('Completed'),
                        'failed'    => __('Failed')
                    ]
                ],
                'message' => [
                    'translation'   => __('message-form-label'),
                ],
                'model_target' => [
                    'translation'   => __('model_target-form-label')
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
