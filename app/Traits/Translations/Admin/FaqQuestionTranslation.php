<?php

namespace App\Traits\Translations\Admin;

use App\Traits\Translations\AttributeTranslation;
use App\Traits\Translations\ModelTranslation;

trait FaqQuestionTranslation
{
    use AttributeTranslation;
    use ModelTranslation;

    /**
     * @var array
     */
    protected $translationAttributes = ['question_text', 'answer_text'];

    /**
     * @return array
     */
    private static function getTranslationsConfig()
    {
        return [
            'titles' => [
                'singular'  => __('FAQ question'),
                'plural'    => __('FAQ questions'),
            ],
            'attributes' => [
                'category_id' => [
                    'translation'   => __('category-form-label')
                ],
                'question_text' => [
                    'translation'   => __('question_text-form-label')
                ],
                'answer_text' => [
                    'translation'   => __('answer_text-form-label')
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
