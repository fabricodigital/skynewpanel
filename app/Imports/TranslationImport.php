<?php

namespace App\Imports;

use App\Services\TranslatorService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use function array_pop;
use function dd;

class TranslationImport implements ToCollection
{
    protected $translator;

    public function __construct()
    {
        $this->translator = new TranslatorService();
    }

    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        $langKeys = $this->translator->getLangKeys();

        foreach ($rows as $index => $row) {
            if($index === 0) {
                continue;
            }

            $dataStrings = [
                'key' => $row[0]
            ];
            foreach ($langKeys as $index => $langKey) {
                $dataStrings[$langKey] = $row[$index + 1];
            }

            $this->translator->saveFiles($dataStrings);
        }
    }
}
