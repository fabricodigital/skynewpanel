<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

abstract class AbstractDataTableExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithMapping
{
    use Exportable;

    /**
     * @var string
     */
    protected $sql;

    /**
     * @var array
     */
    protected $params;
    /**
     * @var array
     */
    protected $columns;

    /**
     * BaseExport constructor.
     * @param $sql
     * @param $params
     * @param $columns
     */
    public function __construct($sql, $params, $columns)
    {
        $this->sql = $sql;
        $this->params = $params;
        $this->columns = $columns;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $results = DB::select(
            $this->sql, $this->params
        );

        return new Collection($results);
    }

    /**
     * @return array
     */
    public function headings() :array
    {
        $output = [];

        foreach ($this->columns as $column) {
            if (!isset($column['column']) || !isset($column['translation'])) {
                continue;
            }

            $output[] = $column['translation'];
        }

        return $output;
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        $columns = [];

        foreach ($this->columns as $column) {
            if (!isset($column['column']) || !isset($column['translation'])) {
                continue;
            }

            $selectDataKey = $column['column'];

            if ($selectDataKey == 'deleted' && isset($row->is_deleted)) {
                $columns[] = $column['value_translations'][$row->is_deleted];
                continue ;
            }

            if(!property_exists($row, $selectDataKey)) {
                continue;
            }

            $columnValue = '';

            if (isset($column['value_translations'])) {
                if (array_key_exists($row->$selectDataKey, $column['value_translations'])) {
                    $columnValue = $column['value_translations'][$row->$selectDataKey];
                } else {
                    $columnValue = $row->$selectDataKey;
                }
            } else {
                $columnValue = $this->editColumnValue($row, $selectDataKey);
            }

            $columns[] = $columnValue;
        }

        return $columns;
    }

    /**
     * @param $row
     * @param $property
     * @return mixed
     */
    protected function editColumnValue($row, $property)
    {
        return $row->$property;
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
