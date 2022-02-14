<?php

namespace App\Traits\DataTables\Admin;

use App\Models\Admin\Role;
use App\Models\Admin\User;
use App\Policies\Admin\UserPolicy;
use App\Traits\DataTables\DataTable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use function compact;
use function is_null;
use function request;
use function view;

trait UserDataTable
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

        if(!Auth::user()->hasRole('Super Administrator')) {
            $query->whereRaw('users.id NOT IN (
                SELECT model_id FROM model_has_roles as mhr_sa JOIN roles as r_sa ON mhr_sa.role_id = r_sa.id WHERE r_sa.name = "Super Administrator"
            )');
        }

        if(!Auth::user()->can('view_all', User::class) && Auth::user()->can('view_own', User::class)) {
            $query->join('model_has_roles as mhr', 'mhr.model_id', 'users.id');
            $query->where('users.id', Auth::id())
                    ->orWhereRaw('mhr.role_id IN (
                        SELECT rsr.sub_role_id FROM model_has_roles as mr JOIN role_sub_roles as rsr ON rsr.role_id = mr.role_id WHERE mr.model_id = ' . Auth::user()->id .'
                    )');
        }

        if(request('role_id')) {
            $query->whereHas('roles', function ($query) {
                $query->where('roles.id', request('role_id'));
            });
        }

        return $query;
    }

    /**
     * @param $query
     * @param $activityStatusTimeIntervalInactive
     * @param $activityStatusTimeIntervalOffline
     * @return mixed
     */
    public function scopeDataTableSelectRows($query, $activityStatusTimeIntervalInactive, $activityStatusTimeIntervalOffline)
    {
        $selectRows = [
            'id' => 'users.id',
            'name' => 'users.name as name',
            'surname' => 'users.surname as surname',
            'email' => 'users.email as email',
            'state' => 'users.state',
            'roles_name' => DB::raw('(
                    SELECT GROUP_CONCAT(DISTINCT roles_trans.role_name separator ", ")
                    FROM model_has_roles as mhr
                    JOIN roles_trans ON mhr.role_id = roles_trans.role_id
                    WHERE mhr.model_id = users.id AND roles_trans.locale = "' . app()->getLocale() .'"
                ) as roles_name'),
            'logged_status' => DB::raw("
                IF( users.logged
                        AND users.last_activity IS NOT NULL
                        AND TIMESTAMPDIFF(MINUTE, users.last_activity, now()) < $activityStatusTimeIntervalInactive ,
                    'active', IF( users.logged
                                    AND users.last_activity IS NOT NULL
                                    AND TIMESTAMPDIFF(MINUTE, users.last_activity, now()) >= $activityStatusTimeIntervalInactive
                                    AND TIMESTAMPDIFF(MINUTE, users.last_activity, now()) < $activityStatusTimeIntervalOffline ,
                                'inactive',
                                'offline')
                ) as logged_status
            "),
        ];

        if (Auth::user()->can('view_deleted', self::class)) {
            $selectRows['deleted'] = DB::raw("IF(users.deleted_at, TRUE, FALSE) AS deleted");
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
        return $query->groupBy('users.id');
    }


    /**
     * @param $table
     * @param $activityStatusTimeIntervalInactive
     * @param $activityStatusTimeIntervalOffline
     * @return mixed
     */
    public static function dataTableFilterColumns($table, $activityStatusTimeIntervalInactive, $activityStatusTimeIntervalOffline)
    {
        $table->filterColumn('deleted', function ($query, $keyword) {
            if ($keyword == '0') {
                $query->whereNull('users.deleted_at');
            } else {
                $query->whereNotNull('users.deleted_at');
            }
        });

        $table->filterColumn('roles_name', function ($query, $keyword) {
            $query->where(function ($query) use ($keyword) {
                $roleIds = explode("|", $keyword);
                $query->whereHas('roles', function ($query) use ($roleIds) {
                    foreach ($roleIds as $key => $val) {
                        if($key == 0) {
                            $query->where("roles.id", $val);
                        }else{
                            $query->orWhere("roles.id", $val);
                        }
                    }
                });
            });
        });

        $table->filterColumn('logged_status', function ($query, $keyword) use($activityStatusTimeIntervalOffline, $activityStatusTimeIntervalInactive) {
            $query->whereRaw("
                    IF( users.logged
                            AND users.last_activity IS NOT NULL
                            AND TIMESTAMPDIFF(MINUTE, users.last_activity, now()) < $activityStatusTimeIntervalInactive ,
                        'active', IF( users.logged
                                        AND users.last_activity IS NOT NULL
                                        AND TIMESTAMPDIFF(MINUTE, users.last_activity, now()) >= $activityStatusTimeIntervalInactive
                                        AND TIMESTAMPDIFF(MINUTE, users.last_activity, now()) < $activityStatusTimeIntervalOffline ,
                                    'inactive',
                                    'offline')
                    ) = ?
                ", [$keyword]);
        });

        $table->filterColumn('state', function ($query, $keyword) {
            return $query->where('users.state', $keyword);
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

        $policy = new UserPolicy();
        $viewAllPermission = Auth::user()->hasPermissionTo('view_all users');
        $viewOwnPermission = Auth::user()->hasPermissionTo('view_own users');
        $updateAllPermission = Auth::user()->hasPermissionTo('update_all users');
        $updateOwnPermission = Auth::user()->hasPermissionTo('update_own users');
        $deleteAllPermission = Auth::user()->hasPermissionTo('delete_all users');
        $deleteOwnPermission = Auth::user()->hasPermissionTo('delete_own users');
        $deleteForeverPermission = Auth::user()->hasPermissionTo('delete_forever users');
        $restoreAllPermission = Auth::user()->hasPermissionTo('restore_all users');
        $restoreOwnPermission = Auth::user()->hasPermissionTo('restore_own users');
        $impersonatePermission = Auth::user()->hasPermissionTo('impersonate users');

        $table->addColumn('actions', '&nbsp;');
        $table->editColumn('actions', function ($row) use($policy, $viewAllPermission, $viewOwnPermission, $updateAllPermission, $updateOwnPermission, $deleteAllPermission, $deleteOwnPermission, $deleteForeverPermission, $restoreAllPermission, $restoreOwnPermission, $impersonatePermission) {
            $routeKey = 'admin.users';
            $baseActionsString = view('admin.datatables.partials._actions', compact(
                    'row',
                    'routeKey',
                    'policy',
                    'viewAllPermission',
                    'viewOwnPermission',
                    'updateAllPermission',
                    'updateOwnPermission',
                    'deleteAllPermission',
                    'deleteOwnPermission',
                    'deleteForeverPermission',
                    'restoreAllPermission',
                    'restoreOwnPermission'
                )
            )->render();

            $impersonateActionString = view('admin.users.partials.datatables._impersonate', compact(
                'row',
                'impersonatePermission',
                'updateAllPermission',
                'updateOwnPermission')
            )->render();

            return $baseActionsString . $impersonateActionString;
        });

        $table->editColumn('deleted', function ($row) {
            return view('admin.datatables.partials._tag-deleted', ['bool' => $row->deleted]);
        });

        $table->editColumn('roles_name', function ($row) {
            $roles =  explode(',', $row->roles_name);
            $output = [];
            foreach ($roles as $role) {
                if(!empty($role)) {
                    $tmp = "<span class=\"label label-info\">".__($role)."</span>";
                    $output []= $tmp;
                }
            }
            $output = implode(" ", $output);

            return $output;
        });

        $table->editColumn('logged_status', function ($row) {
            switch ($row->logged_status) {
                case 'offline':
                    $title = __('Offline');
                    $class = 'text-red';
                    break;
                case 'inactive':
                    $title = __('Firm');
                    $class = 'text-orange';
                    break;
                default:
                    $title = __('Online');
                    $class = 'text-green';
                    break;
            }
            return "<i class=\"fa fa-circle online-offline $class \" title=\"$title\"></i>";
        });

        $table->editColumn('state', function ($row) {
            return view('admin.users.partials._states', ['state' => $row->state]);
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
        if (!empty($columns['logged_status'])) {
            $columns['logged_status']['value_translations'] = [
                'active' => __('Online'),
                'inactive' => __('Firm'),
                'offline' => __('Offline')
            ];
        }
        if (!empty($columns['state'])) {
            $columns['state']['value_translations'] = self::getEnumsTrans('state');
        }
        self::dataTableQueueExport($table, $columns);
    }

    /**
     * @return array
     */
    public static function getSelectsFilters(): array
    {
        $roles = Role::transformForSelectsFilters(Role::getSelectFilter());

        $state = self::dataTableBuildSelectFilter(self::getEnumsTrans('state'));

        $loggedStatus = collect([
            (object)[
                'value' => 'offline',
                'label' => __('Offline')
            ],
            (object)[
                'value' => 'inactive',
                'label' => __('Firm')
            ],
            (object)[
                'value' => 'active',
                'label' => __('Online')
            ],
        ]);

        return [
            'deleted' => self::dataTableBuildSelectFilter(self::getEnumsTrans('deleted')),
            'roles' => $roles,
            'state' => $state,
            'logged_status' => $loggedStatus,
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
                        'data' => 'surname', 'className' => 'dt_col_surname', 'label' => self::getAttrsTrans('surname'),
                        'filter' => [ 'type' => "search" ]
                    ],
                    [
                        'data' => 'email', 'className' => 'dt_col_email', 'label' => self::getAttrsTrans('email'),
                        'filter' => [ 'type' => "search" ]
                    ],
                    [
                        'data' => 'roles_name', 'className' => 'dt_col_roles_name', 'label' => self::getAttrsTrans('roles'),
                        'filter' => [
                            'type' => "select-multi",
                            'options' => $filters['roles']
                        ],
                        'raw' => true
                    ],
                    [
                        'data' => 'state', 'className' => 'dt_col_state', 'label' => self::getAttrsTrans('state'),
                        'filter' => [
                            'type' => "select",
                            'options' => $filters['state']
                        ],
                        'raw' => true
                    ],
                    [
                        'data' => 'logged_status', 'className' => 'dt_col_logged_status', 'label' => self::getAttrsTrans('logged'),
                        'filter' => [
                            'type' => "select",
                            'options' => $filters['logged_status']
                        ],
                        'raw' => true
                    ]
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
