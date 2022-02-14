<?php

namespace App\Traits\DataTables\Admin;

use App\Models\Admin\Revision;
use App\Models\Admin\User;
use App\Traits\DataTables\DataTable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exports\UsersExport;
use function dd;
use function request;

trait RevisionDataTable
{
    use DataTable;

    /**
     * @param $query
     * @return mixed
     */
    public function scopeDataTablePreFilter($query)
    {
        if (request('model_type')) {
            $modelType = request('model_type');
            $query->where('revisions.model_type', $modelType);
        }

        if (request('model_id')) {
            $modelId = request('model_id');
            $query->where('revisions.model_id', $modelId);
        }

        return $query;
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeDataTableSelectRows($query)
    {
        $select = [
            'type' => 'revisions.type as type',
            'old' => 'revisions.old as old',
            'new' => 'revisions.new as new',
            'created_at' => 'revisions.created_at as created_at',
            'creator_id' => 'revisions.creator_id as creator_id',
            'locale' => 'revisions.locale as locale',
            'ip' => 'revisions.ip as ip',
            'user' => DB::raw('CONCAT(users.name, " ", users.surname) as user'),
            'model' => DB::raw('CONCAT("\\\", revisions.model_type) as model'),
        ];

        if (!request('model_type') && !request('model_id')) {
            $select ['model_type'] = 'revisions.model_type as model_type';
            $select ['model_id'] = 'revisions.model_id as model_id';
        }

        $query->select(self::dataTableQueryColumns($select));
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeDataTableSetJoins($query)
    {
        if (self::dataTableHasVisibleColumn(['user'])) {
            $query->leftJoin('users', 'revisions.creator_id', '=', 'users.id');
        }

        return $query;
    }

    /**
     * @param $table
     * @return mixed
     */
    public static function dataTableFilterColumns($table)
    {
        $table->filterColumn('created_at', function ($query, $keyword) {
            $dates = explode(' - ', $keyword);
            if(count($dates) == 2) {
                $startDatetime = Carbon::createFromFormat('d/m/Y H:i', $dates[0]);
                $endDatetime= Carbon::createFromFormat('d/m/Y H:i', $dates[1]);
                $query->whereBetween('revisions.created_at', [$startDatetime->format('Y-m-d H:i:00'), $endDatetime->format('Y-m-d H:i:59')]);
            }
        });

        $table->filterColumn('user', function ($query, $keyword) {
            $query->whereRaw('CONCAT(users.name, " ", users.surname) like ?', ["%$keyword%"]);
        });


        return $table;
    }

    /**
     * @param $table
     * @return mixed
     */
    public static function dataTableEditColumns($table)
    {
        $table->rawColumns(['type', 'old', 'new', 'user', 'model_type', 'locale']);

        $table->editColumn('type', function ($row) {
            return view('admin.datatables.partials.revisions._action', ['type' => $row->type]);
        });

        $table->editColumn('user', function ($row) {
            if($row->creator_id) {
                $route = route('admin.users.show', ['user' => $row->creator_id]);
                $text = $row->user;

                return view('admin.datatables.partials._link', compact('route', 'text'));
            }

            return null;
        });

        $table->editColumn('locale', function ($row) {
            return view('admin.datatables.partials.revisions._trans-locale', ['locale' => $row->locale]);
        });

        $table->editColumn('old', function ($row) {
            return view('admin.datatables.partials.revisions._revisionables', ['revisionables' => $row->old, 'model' => $row->model]);
        });

        $table->editColumn('new', function ($row) {
            return view('admin.datatables.partials.revisions._revisionables', ['revisionables' => $row->new, 'model' => $row->model]);
        });

        $table->editColumn('model_type', function ($row) {
            $text = self::getModelSection($row->model_type);
            if($row->type == 'deleted forever') {
                return $text;
            }
            $route = Revision::getModelRoute($row->model_type, $row->model_id);
            if (empty($route)) {
                return $text;
            }
            return view('admin.datatables.partials._link', compact('route', 'text'));
        });

        return $table;
    }

    /**
     * @return array
     */
    public static function getSelectsFilters(): array
    {
        $sections = self::getModelsSelectFilter();

        $actions = self::dataTableBuildSelectFilter(self::getEnumsTrans('type'));

        return [
            'sections' => $sections,
            'actions' => $actions,
        ];
    }

    /**
     * @param $tableId
     * @param $route
     * @param User|null $user
     * @param bool $getAll
     * @return array
     */
    public static function getDataTableObject($tableId, $route, ?User $user = null, $getAll = false)
    {
        $user = $user ?? auth()->user();
        $tableId = $tableId ?? request('target_table');

        $filters = self::getSelectsFilters();

        $defaultObject = self::dataTableGetDefaultObject($tableId, $filters, $route, ['getAll' => $getAll]);
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
        $columns = [
            [
                'data' => 'created_at', 'className' => 'dt_col_created_at', 'label' => self::getAttrsTrans('created_at'),
                'filter' => ['type' => "datetime-range-picker"]
            ],
            [
                'data' => 'type', 'className' => 'dt_col_type', 'label' => self::getAttrsTrans('type'),
                'filter' => [
                    'type' => "select",
                    'options' => $filters['actions']
                ],
                'raw' => true
            ],
            [
                'data' => 'user', 'className' => 'dt_col_user', 'label' => self::getAttrsTrans('creator_id'),
                'filter' => ['type' => "search"],
                'raw' => true
            ],
            [
                'data' => 'locale', 'className' => 'dt_col_locale', 'label' => self::getAttrsTrans('locale'),
                'raw' => true
            ],
            [
                'data' => 'old', 'className' => 'dt_col_old', 'label' => self::getAttrsTrans('old'),
                'filter' => ['type' => "search"],
                'raw' => true
            ],
            [
                'data' => 'new', 'className' => 'dt_col_new', 'label' => self::getAttrsTrans('new'),
                'filter' => ['type' => "search"],
                'raw' => true
            ],
            [
                'data' => 'ip', 'className' => 'dt_col_ip', 'label' => self::getAttrsTrans('ip'),
                'filter' => ['type' => "search"]
            ],
        ];

        if ($args['getAll']) {
            $columns []= [
                'data' => 'model_type', 'className' => 'dt_col_model_type', 'label' => self::getAttrsTrans('model_type'),
                'filter' => [
                    'type' => "select",
                    'options' => $filters['sections']
                ],
                'raw' => true
            ];
        }

        $defaultObject = [
            'id' => $tableId,
            'columns' => $columns,
            'ajax' => [
                'url' => $route,
                'method' => 'POST',
                'data' => [
                    '_token' => csrf_token(),
                    'target_table' => $tableId,
                ],
            ],
            'order' => [['created_at', 'desc']],
            'pageLength' => 25
        ];

        return [
            'Default' => $defaultObject,
        ];
    }
}
