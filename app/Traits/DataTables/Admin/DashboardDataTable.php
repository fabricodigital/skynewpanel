<?php

namespace App\Traits\DataTables\Admin;

use App\Models\Admin\Account;
use App\Models\Admin\Role;
use App\Policies\Admin\DashboardPolicy;
use App\Traits\DataTables\DataTable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait DashboardDataTable
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
            'id'    => 'dashboards.id',
            'name' => 'dashboards.name',
            'role' => 'roles.name AS role',
            'account' => 'accounts.name AS account'
        ];

        if (Auth::user()->can('view_deleted', self::class)) {
            $selectRows['deleted'] = DB::raw("IF(dashboards.deleted_at, TRUE, FALSE) AS deleted");
        }

        return $query->select(self::dataTableQueryColumns($selectRows));
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeDataTableSetJoins($query)
    {
        $query->leftJoin('roles', 'dashboards.role_id', '=', 'roles.id');
        $query->leftJoin('accounts', 'dashboards.account_id', '=', 'accounts.id');
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
                $query->whereNull('dashboards.deleted_at');
            } else {
                $query->whereNotNull('dashboards.deleted_at');
            }
        });

        $table->filterColumn('role', function ($query, $keyword) {
            $allKeyword = explode("|", $keyword);
            foreach ($allKeyword as $key => $val) {
                if($key == 0) {
                    $query->where('roles.id', $val);
                } else {
                    $query->orWhere('roles.id', $val);
                }
            }
        });

        $table->filterColumn('account', function ($query, $keyword) {
            $allKeyword = explode("|", $keyword);
            foreach ($allKeyword as $key => $val) {
                if($key == 0) {
                    $query->where('accounts.id', $val);
                } else {
                    $query->orWhere('accounts.id', $val);
                }
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

        $policy = new DashboardPolicy();
        $viewAllPermission = Auth::user()->hasPermissionTo('view_all dashboards');
        $viewOwnPermission = Auth::user()->hasPermissionTo('view_own dashboards');
        $updateAllPermission = Auth::user()->hasPermissionTo('update_all dashboards');
        $updateOwnPermission = Auth::user()->hasPermissionTo('update_own dashboards');
        $deleteAllPermission = Auth::user()->hasPermissionTo('delete_all dashboards');
        $deleteOwnPermission = Auth::user()->hasPermissionTo('delete_own dashboards');
        $deleteForeverPermission = Auth::user()->hasPermissionTo('delete_forever dashboards');
        $restoreAllPermission = Auth::user()->hasPermissionTo('restore_all dashboards');
        $restoreOwnPermission = Auth::user()->hasPermissionTo('restore_own dashboards');

        $table->editColumn('actions', function ($row) use($policy, $viewAllPermission, $viewOwnPermission, $updateAllPermission, $updateOwnPermission, $deleteAllPermission, $deleteOwnPermission, $deleteForeverPermission, $restoreAllPermission, $restoreOwnPermission) {
            $routeKey = 'admin.dashboards';

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
            'roles' => Role::transformForSelectsFilters(Role::getSelectFilter()),
            'accounts' => Account::transformForSelectsFilters(Account::getSelectFilter())
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
                    [
                        'data' => 'role', 'className' => 'dt_col_role', 'label' => self::getAttrsTrans('role'),
                        'filter' => [
                            'type' => "select",
                            'options' => $filters['roles']
                        ],
                    ],
                    [
                        'data' => 'account', 'className' => 'dt_col_account', 'label' => self::getAttrsTrans('account'),
                        'filter' => [
                            'type' => "select",
                            'options' => $filters['accounts']
                        ],
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
