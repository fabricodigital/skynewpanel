<?php

namespace App\Traits\Translations\Admin;

use App\Traits\Translations\AttributeTranslation;

trait NoteTranslation
{
    use AttributeTranslation;

    /**
     * @return array
     */
    private static function getTranslationsConfig()
    {
        return [
            'titles' => [
                'singular'  => __('Note'),
                'plural'    => __('Notes'),
            ],
            'attributes' => [
                'notes' => [
                    'translation'   => __('notes-form-label')
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
