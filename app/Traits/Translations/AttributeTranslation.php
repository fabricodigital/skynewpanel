<?php

namespace App\Traits\Translations;

trait AttributeTranslation
{
    /**
     * @param null $attribute
     * @return mixed
     */
    public static function getAttrsTrans($attribute = null)
    {
        $config = self::getTranslationsConfig();
        if (empty($config['attributes'])
            || (!empty($attribute) && empty($config['attributes'][$attribute]) && empty($config['attributes'][$attribute]['translation']))
        ) {
            if (empty($attribute)) {
                return [];
            }
            return ;
        }

        if (!empty($attribute)) {
            return $config['attributes'][$attribute]['translation'];
        } else {
            $trans = [];
            foreach ($config['attributes'] as $attr => $data) {
                $trans[$attr] = $data['translation'];
            }
            return $trans;
        }
    }

    /**
     * @param $attribute
     * @param null $enumValue
     * @return mixed
     */
    public static function getEnumsTrans($attribute, $enumValue = null)
    {
        $config = self::getTranslationsConfig();
        if (empty($config['attributes']) || empty($config['attributes'][$attribute]) || empty($config['attributes'][$attribute]['enum_translations'])
            || (!is_null($enumValue) && empty($config['attributes'][$attribute]['enum_translations'][$enumValue]) )
        ) {
            if (is_null($enumValue)) {
                return [];
            }

            return ;
        }

        if (!is_null($enumValue)) {
            return !empty($config['attributes'][$attribute]['enum_translations'][$enumValue]) ? $config['attributes'][$attribute]['enum_translations'][$enumValue] : null;
        } else {
            return $config['attributes'][$attribute]['enum_translations'];
        }

    }

    /**
     * @param string $type
     * @return mixed
     */
    public static function getTitleTrans($type = 'plural')
    {
        $config = self::getTranslationsConfig();
        if (!empty($config['titles']) && !empty($config['titles'][$type])) {
            return $config['titles'][$type];
        }
    }

    /**
     * @param $type
     * @return mixed
     */
    public static function getMsgTrans($type)
    {
        $trans = [
            'view_heading' => __('View :model_title', ['model_title' => self::getTitleTrans('singular')]),
            'create_heading' => __('Create :model_title', ['model_title' => self::getTitleTrans('singular')]),
            'update_heading' => __('Update :model_title', ['model_title' => self::getTitleTrans('singular')]),
            'created' => __('Creation successful!'),
            'updated' => __('Update successful!'),
            'deleted' => __('Deletion successful!'),
            'restored' => __('Restore successful!'),
        ];

        if (array_key_exists($type, $trans)) {
            return $trans[$type];
        }
    }
}
