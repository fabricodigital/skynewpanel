<?php

namespace App\Traits\Translations\Admin;

use App\Traits\Translations\AttributeTranslation;

trait PromotionTranslation
{
    use AttributeTranslation;

    /**
     * @return array
     */
    private static function getTranslationsConfig()
    {
        return [
            'titles' => [
                'singular'  => __('Promotion'),
                'plural'    => __('Promotions'),
            ],
            'attributes' => [
                'nome' => [
                    'translation'   => __('nome-form-label')
                ],
                'deleted' => [
                    'translation'   => __('deleted-form-label'),
                    'enum_translations' => [
                        0 => __('No'),
                        1 => __('Yes')
                    ]
                ],
                'tipologiaskyservice' => [
                    'translation'   => __('tipologiaskyservice'),
                    'enum_translations' => [
                        'service' => __('Service'),
                        'center' => __('Center'),
                        'service/center' => __('Service/Center'),
                    ]
                ]
            ]
        ];
    }
}
