<?php

namespace App\Traits\DataTables\Admin;

use App\Models\Admin\Role;
use App\Models\Admin\User;
use App\Policies\Admin\NotificationPolicy;
use App\Traits\DataTables\DataTable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exports\UsersExport;
use Illuminate\Support\Facades\Auth;

trait NotificationDataTable
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

        if(!Auth::user()->can('view_all', \App\Models\Admin\Notification::class)) {
            $userRolesIds = Auth::user()->roles->pluck('id')->toArray();

            $query->whereHas('roles', function($query) use($userRolesIds) {
                $query->whereIn('roles.id', $userRolesIds);
            });
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
            'id' => 'notifications.id as id',
            'title' => 'notifications_trans.title as title',
            'roles_name' => DB::raw('(
                    SELECT GROUP_CONCAT(DISTINCT roles_trans.role_name separator ", ")
                    FROM notification_role as nr
                    JOIN roles_trans ON nr.role_id = roles_trans.role_id
                    WHERE nr.notification_id = notifications.id AND roles_trans.locale = "' . app()->getLocale() .'"
                ) as roles_name'),
            'start' =>  'notifications.start as start',
            'end' => 'notifications.end as end',
        ];

        if (Auth::user()->can('view_deleted', self::class)) {
            $selectRows['deleted'] = DB::raw("IF(notifications.deleted_at, TRUE, FALSE) AS deleted");
        }

        $query->select(self::dataTableQueryColumns($selectRows));
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
        $query->groupBy('notifications.id');
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
                $query->whereNull('notifications.deleted_at');
            } else {
                $query->whereNotNull('notifications.deleted_at');
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

        $table->filterColumn('start', function ($query, $keyword) {
            $dates = explode(' - ', $keyword);
            if(count($dates) == 2) {
                $startDatetime = Carbon::createFromFormat('d/m/Y H:i', $dates[0]);
                $endDatetime= Carbon::createFromFormat('d/m/Y H:i', $dates[1]);
                $query->whereBetween('notifications.start', [$startDatetime->format('Y-m-d H:i:00'), $endDatetime->format('Y-m-d H:i:59')]);
            }
        });

        $table->filterColumn('end', function ($query, $keyword) {
            $dates = explode(' - ', $keyword);
            if(count($dates) == 2) {
                $startDatetime = Carbon::createFromFormat('d/m/Y H:i', $dates[0]);
                $endDatetime= Carbon::createFromFormat('d/m/Y H:i', $dates[1]);
                $query->whereBetween('notifications.end', [$startDatetime->format('Y-m-d H:i:00'), $endDatetime->format('Y-m-d H:i:59')]);
            }
        });

        $table->filterColumn('title', function ($query, $keyword) {
            $query->whereRaw('notifications_trans.title like ?', ["%$keyword%"]);
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

        $policy = new NotificationPolicy();
        $viewAllPermission = Auth::user()->hasPermissionTo('view_all notifications');
        $viewOwnPermission = Auth::user()->hasPermissionTo('view_own notifications');
        $updateAllPermission = Auth::user()->hasPermissionTo('update_all notifications');
        $updateOwnPermission = Auth::user()->hasPermissionTo('update_own notifications');
        $deleteAllPermission = Auth::user()->hasPermissionTo('delete_all notifications');
        $deleteOwnPermission = Auth::user()->hasPermissionTo('delete_own notifications');
        $deleteForeverPermission = Auth::user()->hasPermissionTo('delete_forever notifications');
        $restoreAllPermission = Auth::user()->hasPermissionTo('restore_all notifications');
        $restoreOwnPermission = Auth::user()->hasPermissionTo('restore_own notifications');

        $table->editColumn('start', function ($row) {
            return !empty($row->start) ? $row->start->format('d/m/Y H:i') : '';
        });

        $table->editColumn('end', function ($row) {
            return !empty($row->end) ? $row->end->format('d/m/Y H:i') : '';
        });

        $table->addColumn('actions', '&nbsp;');
        $table->editColumn('actions', function ($row) use($policy, $viewAllPermission, $viewOwnPermission, $updateAllPermission, $updateOwnPermission, $deleteAllPermission, $deleteOwnPermission, $deleteForeverPermission, $restoreAllPermission, $restoreOwnPermission) {
            $routeKey = 'admin.notifications';

            return view('admin.datatables.partials._actions', compact('row', 'routeKey', 'policy', 'viewAllPermission', 'viewOwnPermission', 'updateAllPermission', 'updateOwnPermission', 'deleteAllPermission', 'deleteOwnPermission', 'deleteForeverPermission', 'restoreAllPermission', 'restoreOwnPermission'));
        });

        $table->editColumn('deleted', function ($row) {
            return view('admin.datatables.partials._tag-deleted', ['bool' => $row->deleted]);
        });

        $table->editColumn('roles_name', function ($row) {
            $roles =  explode(',', $row->roles_name);
            $output = [];
            foreach ($roles as $role) {
                if(!empty($role)) {
                    $tmp = "<span class=\"label label-info\">$role</span>";
                    $output []= $tmp;
                }
            }
            $output = implode(" ", $output);

            return $output;
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
        $roles = Role::transformForSelectsFilters(Role::getSelectFilter());

        return [
            'deleted' => self::dataTableBuildSelectFilter(self::getEnumsTrans('deleted')),
            'roles' => $roles,
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
                        'data' => 'title', 'className' => 'dt_col_title', 'label' => self::getAttrsTrans('title'),
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
                        'data' => 'start', 'className' => 'dt_col_start', 'label' => self::getAttrsTrans('start'),
                        'filter' => [ 'type' => "datetime-range-picker" ]
                    ],
                    [
                        'data' => 'end', 'className' => 'dt_col_end', 'label' => self::getAttrsTrans('end'),
                        'filter' => [ 'type' => "datetime-range-picker" ]
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
                'order' => [ ['start', 'desc'] ],
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
