<?php

namespace App\Traits\DataTables\Admin;

use App\Models\Admin\Export;
use App\Models\Admin\Role;
use App\Models\Admin\User;
use App\Policies\Admin\ExportPolicy;
use App\Traits\DataTables\DataTable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use function dd;

trait ExportDataTable
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

        if(!Auth::user()->can('view_all', Export::class)) {
            $query->where('exports.creator_id', Auth::id());
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
            'id' => 'exports.id as id',
            'creator_id' => 'exports.creator_id as creator_id',
            'model_target' => 'exports.model_target as model_target',
            'date_start' => 'exports.date_start as date_start',
            'date_end' => 'exports.date_end as date_end',
            'state' => 'exports.state as state',
            'creator' => DB::raw('CONCAT(c.name, " ", c.surname) as creator'),
            'message' => 'exports.message as message',
            'media_id' => 'm.id as media_id',
        ];

        if (Auth::user()->can('view_deleted', self::class)) {
            $selectRows['deleted'] = DB::raw("IF(exports.deleted_at, TRUE, FALSE) AS deleted");
        }

        return $query->select(self::dataTableQueryColumns($selectRows));
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeDataTableSetJoins($query)
    {
        if (self::dataTableHasVisibleColumn(['creator'])) {
            $query->join('users as c', 'exports.creator_id', '=', 'c.id');
        }
        $query->leftJoin('media as m', function($join){
            $join->on('exports.id', '=', 'm.model_id');
            $join->on('m.model_type', '=', DB::raw('"App\\\Models\\\Admin\\\Export"'));
        });

        return $query;
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeDataTableGroupBy($query)
    {
        $query->groupBy('exports.id');

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
                $query->whereNull('exports.deleted_at');
            } else {
                $query->whereNotNull('exports.deleted_at');
            }
        });

        $table->filterColumn('date_start', function ($query, $keyword) {
            $dates = explode(' - ', $keyword);
            if(count($dates) == 2) {
                $startDatetime = Carbon::createFromFormat('d/m/Y H:i', $dates[0]);
                $endDatetime= Carbon::createFromFormat('d/m/Y H:i', $dates[1]);
                $query->whereBetween('exports.date_start', [$startDatetime->format('Y-m-d H:i:00'), $endDatetime->format('Y-m-d H:i:59')]);
            }
        });

        $table->filterColumn('date_end', function ($query, $keyword) {
            $dates = explode(' - ', $keyword);
            if(count($dates) == 2) {
                $startDatetime = Carbon::createFromFormat('d/m/Y H:i', $dates[0]);
                $endDatetime= Carbon::createFromFormat('d/m/Y H:i', $dates[1]);
                $query->whereBetween('exports.date_end', [$startDatetime->format('Y-m-d H:i:00'), $endDatetime->format('Y-m-d H:i:59')]);
            }
        });

        $table->filterColumn('creator', function ($query, $keyword) {
            $query->whereRaw("CONCAT(c.name, ' ', c.surname) like ?", ["%$keyword%"]);
        });

        $table->filterColumn('state', function ($query, $keyword) {
            $query->where("exports.state", $keyword);
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

        $policy = new ExportPolicy();
        $viewAllPermission = false;
        $viewOwnPermission = false;
        $updateAllPermission = false;
        $updateOwnPermission = false;
        $deleteAllPermission = Auth::user()->hasPermissionTo('delete_all exports');
        $deleteOwnPermission = Auth::user()->hasPermissionTo('delete_own exports');
        $deleteForeverPermission = Auth::user()->hasPermissionTo('delete_forever exports');
        $restoreAllPermission = Auth::user()->hasPermissionTo('restore_all exports');
        $restoreOwnPermission = Auth::user()->hasPermissionTo('restore_own exports');
        $downloadAllPermission = Auth::user()->hasPermissionTo('download_all exports');
        $downloadOwnPermission = Auth::user()->hasPermissionTo('download_own exports');

        $table->addColumn('actions', '&nbsp;');
        $table->editColumn('actions', function ($row) use($policy, $viewAllPermission, $viewOwnPermission, $updateAllPermission, $updateOwnPermission, $deleteAllPermission, $deleteOwnPermission, $deleteForeverPermission, $restoreAllPermission, $restoreOwnPermission) {
            $routeKey = 'admin.exports';

            return view('admin.datatables.partials._actions', compact('row', 'routeKey', 'policy', 'viewAllPermission', 'viewOwnPermission', 'updateAllPermission', 'updateOwnPermission', 'deleteAllPermission', 'deleteOwnPermission', 'deleteForeverPermission', 'restoreAllPermission', 'restoreOwnPermission'));
        });

        $table->addColumn('download', '&nbsp;');
        $table->editColumn('download', function ($row) use($downloadAllPermission, $downloadOwnPermission) {
            return view('admin.datatables.partials._download-btn', compact('row', 'downloadAllPermission', 'downloadOwnPermission'));
        });

        $table->editColumn('deleted', function ($row) {
            return view('admin.datatables.partials._tag-deleted', ['bool' => $row->deleted]);
        });

        $table->editColumn('model_target', function ($row) {
            if (method_exists($row->model_target, 'getTitleTrans')) {
                return $row->model_target::getTitleTrans();
            } else {
                return __($row->model_target);
            }
        });
        $table->editColumn('date_start', function ($row) {
            return $row->date_start->format('d/m/Y H:i');
        });

        $table->editColumn('date_end', function ($row) {
            return $row->date_end ? $row->date_end->format('d/m/Y H:i') : null;
        });

        $table->addColumn('elapsed', '&nbsp;');
        $table->editColumn('elapsed', function ($row) {
            if($row->date_end){
                return strtotime($row->date_end) - strtotime($row->date_start) . ' ' . __('seconds');
            }
            return time() - strtotime($row->date_start) . ' ' . __('seconds');
        });

        $table->editColumn('state', function ($row) {
            return view('admin.datatables.partials._states', ['state' => $row->state]);
        });

        return $table;
    }

    /**
     * @return array
     */
    public static function getSelectsFilters(): array
    {
        return [
            'states' => self::dataTableBuildSelectFilter(self::getEnumsTrans('state')),
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
                        'data' => 'download',
                        'searchable' => false,
                        'sortable' => false,
                        'className' => 'dt_col_download',
                        'label' => __('Download'),
                        'raw' => true
                    ],
                    [
                        'data' => 'model_target', 'className' => 'dt_col_model_target', 'label' => self::getAttrsTrans('model_target'),
                        'filter' => [ 'type' => "search" ]
                    ],
                    [
                        'data' => 'date_start', 'className' => 'dt_col_date_start', 'label' => self::getAttrsTrans('date_start'),
                        'filter' => [ 'type' => "datetime-range-picker" ]
                    ],
                    [
                        'data' => 'date_end', 'className' => 'dt_col_date_end', 'label' => self::getAttrsTrans('date_end'),
                        'filter' => [ 'type' => "datetime-range-picker" ]
                    ],
                    [
                        'data' => 'elapsed', 'searchable' => false, 'sortable' => false, 'className' => 'dt_col_elapsed', 'label' => __('elapsed-form-label'),
                        'filter' => [ 'type' => "search" ]
                    ],
                    [
                        'data' => 'state', 'className' => 'dt_col_state', 'label' => self::getAttrsTrans('state'),
                        'filter' => [
                            'type' => "select",
                            'options' => $filters['states']
                        ],
                        'raw' => true
                    ],
                    [
                        'data' => 'creator', 'className' => 'dt_col_creator', 'label' => self::getAttrsTrans('creator_id'),
                        'filter' => [ 'type' => "search" ]
                    ],
                    [
                        'data' => 'message', 'className' => 'dt_col_message', 'label' => self::getAttrsTrans('message'),
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
                'order' => [ ['date_start', 'desc'] ],
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
