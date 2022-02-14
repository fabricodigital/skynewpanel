<?php

namespace App\Traits\DataTables\Admin;

use App\Models\Admin\User;
use App\Policies\Admin\RolePolicy;
use App\Traits\DataTables\DataTable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait RoleDataTable
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

        $query->where('roles_trans.locale', app()->getLocale());
        return $query;
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeDataTableSelectRows($query)
    {
        $selectRows = [
            'id'    => 'roles.id as id',
            'name'  => 'roles_trans.role_name as name',
            'level' => 'roles.level'
        ];

        if (Auth::user()->can('view_deleted', self::class)) {
            $selectRows['deleted'] = DB::raw("IF(roles.deleted_at, TRUE, FALSE) AS deleted");
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
                $query->whereNull('roles.deleted_at');
            } else {
                $query->whereNotNull('roles.deleted_at');
            }
        });

        $table->filterColumn('name', function ($query, $keyword) {
            return $query->where('roles_trans.role_name', 'like', DB::raw("'%$keyword%'"));
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

        $policy = new RolePolicy();
        $viewAllPermission = Auth::user()->hasPermissionTo('view_all roles');
        $viewOwnPermission = Auth::user()->hasPermissionTo('view_own roles');
        $updateAllPermission = Auth::user()->hasPermissionTo('update_all roles');
        $updateOwnPermission = Auth::user()->hasPermissionTo('update_own roles');
        $deleteAllPermission = Auth::user()->hasPermissionTo('delete_all roles');
        $deleteOwnPermission = Auth::user()->hasPermissionTo('delete_own roles');
        $deleteForeverPermission = Auth::user()->hasPermissionTo('delete_forever roles');
        $restoreAllPermission = Auth::user()->hasPermissionTo('restore_all roles');
        $restoreOwnPermission = Auth::user()->hasPermissionTo('restore_own roles');

        $table->addColumn('actions', '&nbsp;');
        $table->editColumn('actions', function ($row) use($policy, $viewAllPermission, $viewOwnPermission, $updateAllPermission, $updateOwnPermission, $deleteAllPermission, $deleteOwnPermission, $deleteForeverPermission, $restoreAllPermission, $restoreOwnPermission) {
            $routeKey = 'admin.roles';

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
                        'raw'   => true
                    ],
                    [
                        'data' => 'name', 'className' => 'dt_col_name', 'label' => self::getAttrsTrans('name'),
                        'filter' => [ 'type' => "search" ],
                        'raw'   => true
                    ],
                    [
                        'data' => 'level', 'className' => 'dt_col_level', 'label' => self::getAttrsTrans('level'),
                        'filter' => [ 'type' => "search" ],
                        'raw'   => true
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
