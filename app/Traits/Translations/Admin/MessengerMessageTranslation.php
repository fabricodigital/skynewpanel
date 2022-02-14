<?php

namespace App\Traits\Translations\Admin;

use App\Traits\Translations\AttributeTranslation;

trait MessengerMessageTranslation
{
    use AttributeTranslation;

    /**
     * @return array
     */
    private static function getTranslationsConfig()
    {
        return [
            'titles' => [
                'singular'  => __('Message'),
                'plural'    => __('Messages'),
            ],
            'attributes' => [
                'subject' => [
                    'translation'   => __('subject-form-label')
                ],
                'content' => [
                    'translation'   => __('message-form-label')
                ],
                'receiver' => [
                    'translation'   => __('receiver-form-label')
                ],
            ]
        ];
    }
}
