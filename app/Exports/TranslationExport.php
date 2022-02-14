<?php

namespace App\Exports;

use App\Services\TranslatorService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class TranslationExport implements FromArray, ShouldAutoSize, WithHeadings, WithEvents
{
    /**
     * @var TranslatorService
     */
    protected $translator;

    protected $langNames;

    protected $langKeys;

    protected $strings;

    public function __construct()
    {
        $this->translator = new TranslatorService();
        $this->langNames = $this->translator->getLangNames();
        $this->langKeys = $this->translator->getLangKeys();
        $this->strings = $this->translator->getStrings();
    }
    /**
     * @return View
     */
    public function array(): array
    {
        $output = [];

        foreach ($this->strings as $key => $translations) {
            $row = [];
            $row []= $key;
            foreach($this->langKeys as $langIndex => $langKey) {
                if(isset($translations[$langIndex])) {
                    $row []= $translations[$langIndex];
                }else{
                    $row []= '';
                }
            }

            if(count(array_unique($translations)) == count($translations)) {
                $row []= __('Yes');
            }else{
                $row []= __('No');
            }

            $output []= $row;
        }

        return $output;
    }

    public function headings(): array
    {
        $headings = [__('String')];

        foreach ($this->langNames as $langName) {
            $headings []= __($langName);
        }

        $headings []= __('Translated');

        return $headings;
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $cellRange = 'A1:'.$event->sheet->getDelegate()->getHighestColumn().'1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
        ];
    }
}
