<?php

namespace App\Traits\DataTables;

use App\Models\Admin\Export;
use App\Models\Admin\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function dd;
use function in_array;

trait DataTable
{
    /**
     * @param $columns
     * @return array
     */
    private static function dataTableQueryColumns($columns)
    {
        $invisibleColumns = !empty(request('invisibleColumns')) ? request('invisibleColumns') : [];
        $selectColumns = [];

        foreach ($columns as $sqlColumn => $sql) {
            $selectColumns[$sqlColumn] = $sql;

            foreach ($invisibleColumns as $invColumn) {
                if ($invColumn == $sqlColumn) {
                    $selectColumns[$sqlColumn] = DB::raw('NULL AS ' . $sqlColumn);
                }
            }
        }

        return $selectColumns;
    }

    /**
     * @param array $columns
     * @return bool
     */
    private static function dataTableHasVisibleColumn(array $columns)
    {
        $invisibleColumns = !empty(request('invisibleColumns')) ? request('invisibleColumns') : [];
        $countInvisible = 0;
        foreach ($columns as $column) {
            foreach ($invisibleColumns as $invColumn) {
                if ($column == $invColumn) {
                    $countInvisible++;
                }
            }
        }

        if (count($columns) == $countInvisible) {
            return false;
        }
        return true;
    }

    /**
     * @param $table
     */
    public static function dataTableSetRawColumns($table)
    {
        $dtObject = self::getDataTableObject(null, null);
        $rawColumns = [];

        foreach ($dtObject['columns'] as $dtColumn) {
            if (!empty($dtColumn['raw'])) {
                $rawColumns[] = $dtColumn['data'];
            }
        }

        $table->rawColumns($rawColumns);
    }

    /**
     * @param $table
     * @param $modelTarget
     * @param $columns
     * @param string $exportClass
     */
    private static function dataTableQueueExport($table, $columns, $exportClass = '\App\Exports\DataTableExport')
    {
        $sql = $table->getFilteredQuery()->toSql();
        $params = $table->getFilteredQuery()->getBindings();
        $tmpFilePath = 'tmp/' . time() . '_' . Auth::id() . '-' . self::getTitleTrans() . '.xlsx';
        $export = Export::start(self::class);

        Artisan::queue('data-tables:export', [
            'sql' => $sql,
            'columns' => $columns,
            'params' => $params,
            'tmpFilePath' => $tmpFilePath,
            'export' => $export,
            'exportClass' => $exportClass
        ]);
    }

    /**
     * @param array $removeColumns
     * @return array
     */
    private static function dataTableExportColumns($removeColumns = [])
    {
        $columns = [];
        $reqColumns = request('columns');
        $dtObject = self::getDataTableObject(null, null);

        foreach ($reqColumns as $reqColumn) {
            if(
                !isset($reqColumn['visible']) || $reqColumn['visible'] == 'false'
                || !isset($reqColumn['data']) || in_array($reqColumn['data'], $removeColumns)
            ) {
                continue;
            }

            foreach ($dtObject['columns'] as $dtColumn) {
                if(!isset($dtColumn['label']) || !isset($dtColumn['data'])) {
                    continue;
                }
                if ($dtColumn['data'] == $reqColumn['data']) {
                    $columns[$reqColumn['data']] = [
                        'column'        => $reqColumn['data'],
                        'translation'   => $dtColumn['label']
                    ];
                }
            }
        }

        return $columns;
    }

    /**
     * @param $options
     * @return \Illuminate\Support\Collection
     */
    private static function dataTableBuildSelectFilter($options)
    {
        $filter = [];

        foreach ($options as $value => $label) {
            $filter[] = (object) [
                'value' => $value,
                'label' => $label
            ];
        }

        return collect($filter);
    }

    /**
     * @return array
     */
    public static function getSelectsFilters(): array
    {
        return [

        ];
    }

    /**
     * @param array $defaultObject
     * @param User $user
     * @return array
     */
    protected static function dataTableHandleDefaultObjectFromView(array $defaultObject, User $user):array
    {
        $tableId = $defaultObject['id'];
        $lastUsedView = self::dataTableGetLastUsedView($tableId, $user);
        $defaultObject['lastUsedView'] = $lastUsedView;
        $defaultObject['defaultViews'] = self::dataTableGetDefaultViews();
        $defaultObject['views'] = self::dataTableGetViews($tableId, $user);
        $viewObj = self::dataTableGetLastUsedViewObj($tableId, $user, $lastUsedView);

        if(!$lastUsedView || !$viewObj) {
            return $defaultObject;
        }

        if(!is_array($viewObj['visibleCols'])) {
            return $defaultObject;
        }

        $columns = [];
        $order = [];

        foreach ($viewObj['visibleCols'] as $index => $viewColumn) {
            $tmpColumn = array_first($defaultObject['columns'], function ($column) use($viewColumn) {
                return $column['data'] == $viewColumn['data'];
            });
            if(empty($tmpColumn)) {
                continue;
            }

            $tmpColumn['visible'] = $viewColumn['visible'] === "true" ? true : false;
            $tmpColumn['search'] = $viewObj['searchCols'][$index];

            $columns []= $tmpColumn;
        }

        foreach ($defaultObject['columns'] as $index => $column) {
            $columnExist = array_first($columns, function ($viewColumn) use($column) {
                return $column['data'] == $viewColumn['data'];
            });
            if (empty($columnExist)) {
                $tmpColumn = $column;
                $tmpColumn['visible'] = $lastUsedView == 'Default' ? true : false;
                $columns[] = $tmpColumn;
            }
        }

        $defaultObject['columns'] = $columns;

        foreach ($viewObj['order'] as $orderArr) {
            $columnName = $columns[$orderArr[0]]['data'];
            $orderType = $orderArr[1];
            $order []= [$columnName, $orderType];
        }
        $defaultObject['order'] = $order;

        return $defaultObject;
    }

    /**
     * @param string $tableId
     * @param array $filters
     * @param string|null $route
     * @param array $args
     * @return array
     */
    private static function dataTableGetDefaultObject(string $tableId, array $filters, ?string $route = null, array $args = []):array
    {
        $objects = self::dataTableGetDefaultObjects($tableId, $filters, $route, $args);

        if(isset($args['lastUsedView'])) {
            return $objects[$args['lastUsedView']] ?? $objects['Default'];
        }

        return $objects['Default'];
    }

    /**
     * @param $tableId
     * @param User $user
     * @return string|null
     */
    public static function dataTableGetLastUsedView(string $tableId, User $user)
    {
        return isset($user->settings['data_tables'][$tableId]['last_used_view'])
            ? $user->settings['data_tables'][$tableId]['last_used_view']
            : null;
    }

    /**
     * @param string $tableId
     * @param User $user
     * @param string|null $lastUsedView
     * @return mixed|null
     */
    public static function dataTableGetLastUsedViewObj(string $tableId, User $user, ?string $lastUsedView = null)
    {
        return isset($user->settings['data_tables'][$tableId]['views'][$lastUsedView])
            ? $user->settings['data_tables'][$tableId]['views'][$lastUsedView]
            : null;
    }

    /**
     * @param string $tableId
     * @param User $user
     * @return array
     */
    public static function dataTableGetSavedViews(string $tableId, User $user): array
    {
        return isset($user->settings['data_tables'][$tableId]['views'])
        && !empty($user->settings['data_tables'][$tableId]['views'])
        && is_array($user->settings['data_tables'][$tableId]['views'])
            ? $user->settings['data_tables'][$tableId]['views']
            : [];
    }

    /**
     * @return array
     */
    public static function dataTableGetDefaultViews():array
    {
        return ['Default'];
    }

    /**
     * @param string $tableId
     * @param User $user
     * @return array
     */
    public static function dataTableGetViews(string $tableId, User $user):array
    {
        $defaultViews = self::dataTableGetDefaultViews();
        $savedViews = self::dataTableGetSavedViews($tableId, $user);
        $views = [];

        foreach ($defaultViews as $defaultView){
            $views []= $defaultView;
        }

        foreach ($savedViews as $viewName => $savedView) {
            if(!in_array($viewName, $views) && $viewName != 'Default') {
                $views []= $viewName;
            }
        }

        return $views;
    }
}
