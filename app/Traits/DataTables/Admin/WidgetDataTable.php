<?php

namespace App\Traits\DataTables\Admin;

use App\Policies\Admin\WidgetPolicy;
use App\Traits\DataTables\DataTable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait WidgetDataTable
{
    use DataTable;

    /**
     * @param $query
     * @return mixed
     */
    public function scopeDataTablePreFilter($query)
    {
        if (Auth::user()->can('view_deleted', self::class)) {
            $query->withTrashed();
        }

        return $query;
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeDataTableSelectRows($query)
    {
        $selectRows = [
            'id'    => 'id',
            'name' => 'name',
        ];

        if (Auth::user()->can('view_deleted', self::class)) {
            $selectRows['deleted'] = DB::raw("IF(deleted_at, TRUE, FALSE) AS deleted");
        }

        return $query->select(self::dataTableQueryColumns($selectRows));
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeDataTableSetJoins($query)
    {
        return $query;
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeDataTableGroupBy($query)
    {
        return $query;
    }

    /**
     * @param $table
     * @return mixed
     */
    public static function dataTableFilterColumns($table)
    {
        $table->filterColumn('deleted', function ($query, $keyword) {
            if ($keyword == '0') {
                $query->whereNull('deleted_at');
            } else {
                $query->whereNotNull('deleted_at');
            }
        });

        return $table;
    }

    /**
     * @param $table
     * @return mixed
     */
    public static function dataTableEditColumns($table)
    {
        self::dataTableSetRawColumns($table);

        $policy = new WidgetPolicy();
        $viewAllPermission = Auth::user()->hasPermissionTo('view_all widgets');
        $viewOwnPermission = Auth::user()->hasPermissionTo('view_own widgets');
        $updateAllPermission = Auth::user()->hasPermissionTo('update_all widgets');
        $updateOwnPermission = Auth::user()->hasPermissionTo('update_own widgets');
        $deleteAllPermission = Auth::user()->hasPermissionTo('delete_all widgets');
        $deleteOwnPermission = Auth::user()->hasPermissionTo('delete_own widgets');
        $deleteForeverPermission = Auth::user()->hasPermissionTo('delete_forever widgets');
        $restoreAllPermission = Auth::user()->hasPermissionTo('restore_all widgets');
        $restoreOwnPermission = Auth::user()->hasPermissionTo('restore_own widgets');

        $table->editColumn('actions', function ($row) use($policy, $viewAllPermission, $viewOwnPermission, $updateAllPermission, $updateOwnPermission, $deleteAllPermission, $deleteOwnPermission, $deleteForeverPermission, $restoreAllPermission, $restoreOwnPermission) {
            $routeKey = 'admin.widgets';

            return view('admin.datatables.partials._actions', compact('row', 'routeKey', 'policy', 'viewAllPermission', 'viewOwnPermission', 'updateAllPermission', 'updateOwnPermission', 'deleteAllPermission', 'deleteOwnPermission', 'deleteForeverPermission', 'restoreAllPermission', 'restoreOwnPermission'));
        });

        $table->editColumn('deleted', function ($row) {
            return view('admin.datatables.partials._tag-deleted', ['bool' => $row->deleted]);
        });

        return $table;
    }

    /**
     * @param $table
     */
    public static function dataTableExport($table)
    {
        $columns = self::dataTableExportColumns(['actions']);
        if (!empty($columns['deleted'])) {
            $columns['deleted']['value_translations'] = self::getEnumsTrans('deleted');
        }

        self::dataTableQueueExport($table, $columns);
    }

    /**
     * @return array
     */
    public static function getSelectsFilters(): array
    {
        return [
            'deleted' => self::dataTableBuildSelectFilter(self::getEnumsTrans('deleted')),
        ];
    }

    /**
     * @param $tableId
     * @param $route
     * @param User|null $user
     * @return array
     */
    public static function getDataTableObject($tableId, $route, ?User $user = null)
    {
        $user = $user ?? auth()->user();
        $tableId = $tableId ?? request('target_table');

        $filters = self::getSelectsFilters();

        $defaultObject = self::dataTableGetDefaultObject($tableId, $filters, $route);
        $defaultObject = self::dataTableHandleDefaultObjectFromView($defaultObject, $user);

        return $defaultObject;
    }

    /**
     * @param string $tableId
     * @param array $filters
     * @param string|null $route
     * @param array|null $args
     * @return array|array[]
     */
    private static function dataTableGetDefaultObjects(string $tableId, array $filters, ?string $route = null, ?array $args = []):array
    {
        $dtObject = [
            'Default' => [
                'id' => $tableId,
                'columns' => [
                    [
                        'data' => 'actions',
                        'searchable' => false,
                        'sortable' => false,
                        'className' => 'dt_col_actions',
                        'label' => __('Actions'),
                        'raw' => true
                    ],
                    [
                        'data' => 'name', 'className' => 'dt_col_name', 'label' => self::getAttrsTrans('name'),
                        'filter' => [ 'type' => "search" ]
                    ],
                ],
                'ajax' => [
                    'url' => $route,
                    'method' => 'POST',
                    'data' => [
                        '_token' => csrf_token(),
                        'target_table' => $tableId,
                    ],
                ],
                'order' => [ ['name', 'asc'] ],
                'pageLength' => 25
            ]
        ];

        if (Auth::user()->can('view_deleted', self::class)) {
            $dtObject['Default']['columns'][] = [
                'data' => 'deleted', 'className' => 'dt_col_deleted', 'label' => self::getAttrsTrans('deleted'),
                'filter' => [
                    'type' => "select",
                    'options' => $filters['deleted']
                ],
            ];
        }

        return $dtObject;
    }

}
