<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDashboardRequest;
use App\Http\Requests\Admin\UpdateDashboardRequest;
use App\Models\Admin\Account;
use App\Models\Admin\Dashboard;
use App\Models\Admin\Revision;
use App\Models\Admin\Role;
use App\Models\Admin\Widget;
use Yajra\DataTables\DataTables;

class DashboardController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view_index', Dashboard::class);

        $dataTableObject = Dashboard::getDataTableObject('dashboardDataTable', route('admin.datatables.dashboards'));

        return view('admin.dashboards.index', compact('dataTableObject'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Dashboard::class);

        $dashboard = Dashboard::class;
        $roles = Role::get()->pluck('name', 'id')->prepend('', '');
        $accounts = Account::get()->pluck('name', 'id')->prepend('Tutti', 0);
        $widgets = Widget::all();

        return view('admin.dashboards.create', compact('dashboard', 'roles', 'accounts', 'widgets'));
    }

    /**
     * @param StoreDashboardRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreDashboardRequest $request)
    {
        $this->authorize('create', Dashboard::class);

        $data = $request->validated();
        if ($data['account_id'] == 0) $data['account_id'] = null;

        $dashboard = Dashboard::create($data);
        $dashboard->widgets()->sync($request->get('widgets'));

        if (!empty($data['dashboard_image'])) {
            $dashboard->addMedia($data['dashboard_image'])->toMediaCollection('dashboard_image');
        }

        return redirect()->to(handle_redirect_route_after_crud_action('admin.dashboards', $dashboard))
            ->with('success', Dashboard::getMsgTrans('created'));
    }

    /**
     * @param $dashboardId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($dashboardId)
    {
        $dashboard = Dashboard::withTrashed()->findOrFail($dashboardId);
        $this->authorize('view', $dashboard);

        $revisionsDataTableObject = Revision::getDataTableObject('dashboardRevisionsDataTable', route('admin.datatables.revisions', ['model_type' => get_class($dashboard), 'model_id' => $dashboard->id]));

        $widgets = $dashboard->widgets->pluck('name')->toArray();
        $widgets = implode('<br>', $widgets);

        return view('admin.dashboards.show', [
            'dashboard' => $dashboard,
            'revisionsDataTableObject' => $revisionsDataTableObject,
            'widgets' => $widgets
        ]);
    }

    /**
     * @param Dashboard $dashboard
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Dashboard $dashboard)
    {
        $this->authorize('update', $dashboard);

        header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
        header("Pragma: no-cache"); // HTTP 1.0.
        header("Expires: 0");

        $roles = Role::get()->pluck('name', 'id')->prepend('', '');
        $accounts = Account::get()->pluck('name', 'id')->prepend('Tutti', 0);
        $widgets = Widget::all();

        return view('admin.dashboards.edit', compact('dashboard', 'roles', 'accounts', 'widgets'));
    }

    /**
     * @param UpdateDashboardRequest $request
     * @param Dashboard $dashboard
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateDashboardRequest $request, Dashboard $dashboard)
    {
        $this->authorize('update', $dashboard);

        $data = $request->validated();

        if ($data['account_id'] == 0) $data['account_id'] = null;

        $dashboard->update($data);
        $dashboard->widgets()->sync($request->get('widgets'));

        if (!empty($data['dashboard_image'])) {
            $dashboard->clearMediaCollection('dashboard_image');
            $dashboard->addMedia($data['dashboard_image'])->toMediaCollection('dashboard_image');
        }

        return redirect()->to(handle_redirect_route_after_crud_action('admin.dashboards', $dashboard))
            ->with('success', Dashboard::getMsgTrans('updated'));
    }

    /**
     * @param $dashboardId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($dashboardId)
    {
        $dashboard = Dashboard::withTrashed()->findOrFail($dashboardId);

        if ((int) request('delete_forever') === 1) {
            $this->authorize('delete_forever', $dashboard);
            $dashboard->forceDelete();
        } else {
            $this->authorize('delete', $dashboard);
            $dashboard->delete();
        }

        return redirect()->route('admin.dashboards.index')
            ->with('success', Dashboard::getMsgTrans('deleted'));
    }

    /**
     * @param $dashboardId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function restore($dashboardId)
    {
        $dashboard = Dashboard::withTrashed()->findOrFail($dashboardId);

        $this->authorize('restore', $dashboard);

        $dashboard->restore();

        return redirect()->route('admin.dashboards.index')
            ->with('success', Dashboard::getMsgTrans('restored'));
    }

    /**
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function datatable()
    {
        $this->authorize('view_index', Dashboard::class);

        $query = Dashboard::query();
        $query->dataTableSelectRows()
            ->dataTableSetJoins()
            ->dataTablePreFilter()
            ->dataTableGroupBy();

        $table = Datatables::of($query);
        $table = Dashboard::dataTableFilterColumns($table);

        if (!request('export')) {
            $table = Dashboard::dataTableEditColumns($table);

            return $table->make(true);
        }

        Dashboard::dataTableExport($table);

        return response()->json([
            'success' => true,
            'message' => __("The export will run in background! When it's done we will notify you via email!")
        ]);
    }
}
