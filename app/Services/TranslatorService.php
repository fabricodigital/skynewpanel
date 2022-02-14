<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use function dd;

class TranslatorService
{
    /**
     * @var array|\Illuminate\Config\Repository|mixed
     */
    private $languages = [];

    public function __construct()
    {
        $this->languages = config('main.available_languages');
    }

    /**
     * @return array
     */
    public function getStrings()
    {
        $strings = [];
        foreach ($this->languages as $key => $language) {
            $strings = array_merge_recursive($strings, $this->getTranslationsArray($key));
        }

        return $strings;
    }

    /**
     * @return array|\Illuminate\Config\Repository|mixed
     */
    public function getLangNames()
    {
        return array_values($this->languages);
    }

    /**
     * @return array
     */
    public function getLangKeys()
    {
        return array_keys($this->languages);
    }

    /**
     * @param $language
     * @return array
     */
    private function getTranslationsArray($language)
    {
        return json_decode(
            file_get_contents(resource_path() . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $language . '.json'),
            true
        );
    }

    /**
     * @param array $dataStrings
     * @return $this
     */
    public function saveFiles(array $dataStrings)
    {
        foreach ($this->languages as $key => $language) {
            $trans = $this->getTranslationsArray($key);
            $trans[html_entity_decode($dataStrings['key'])] = html_entity_decode($dataStrings[$key]);
            $this->saveTranslations($key, $trans);
        }

        return $this;
    }

    public function scanStrings()
    {
        foreach ($this->languages as $key => $language) {
            Artisan::call('translatable:export', [
                'lang' => $key,
            ]);
        }
    }

    /**
     * @param $language
     * @param $data
     */
    private function saveTranslations($language, $data)
    {
        file_put_contents(
            resource_path() . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $language . '.json',
            json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        );
    }
}
