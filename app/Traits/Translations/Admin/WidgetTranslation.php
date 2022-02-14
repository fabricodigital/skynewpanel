<?php

namespace App\Traits\Translations\Admin;

use App\Traits\Translations\AttributeTranslation;
use App\Traits\Translations\ModelTranslation;

trait WidgetTranslation
{
    use AttributeTranslation;
    use ModelTranslation;

    /**
     * @var array
     */
    protected $translationAttributes = ['description'];

    /**
     * @return array
     */
    private static function getTranslationsConfig()
    {
        return [
            'titles' => [
                'singular'  => __('Widget'),
                'plural'    => __('Widgets'),
            ],
            'attributes' => [
                'name' => [
                    'translation'   => __('name-form-label')
                ],
                'type' => [
                    'translation'   => __('type-form-label'),
                    'enum_translations' => [
                        'line' => __('Line'),
                        'bar' => __('Bar'),
                        'pie' => __('Pie'),
                        'doughnut' => __('Doughnut'),
                        'radar' => __('Radar'),
                        'kpi' => __('KPI'),
                        'datatable' => __('Datatable')
                    ]
                ],
                'width' => [
                    'translation'   => __('width-form-label'),
                    'enum_translations' => [
                        '2' => "1/6", // 16%
                        '3' => "1/4", // 25$
                        '4' => "1/3", // 33%
                        '6' => "1/2", // 50%
                        '9' => "3/4", // 75%
                        '10' => "5/6", // 83%
                        '12' => "1", // 100%
                    ]
                ],
                'description' => [
                    'translation'   => __('description-form-label')
                ],
                'query' => [
                    'translation'   => __('query-form-label')
                ],
                'accounts' => [
                    'translation'   => __('accounts-form-label')
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
