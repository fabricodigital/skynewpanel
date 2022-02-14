<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreWidgetRequest;
use App\Http\Requests\Admin\UpdateWidgetRequest;
use App\Models\Admin\Account;
use App\Models\Admin\Revision;
use App\Models\Admin\Widget;
use Yajra\DataTables\DataTables;

class WidgetController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view_index', Widget::class);

        $dataTableObject = Widget::getDataTableObject('widgetDataTable', route('admin.datatables.widgets'));

        return view('admin.widgets.index', compact('dataTableObject'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Widget::class);

        $widget = Widget::class;

        return view('admin.widgets.create', compact('widget'));
    }

    /**
     * @param StoreWidgetRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreWidgetRequest $request)
    {
        $this->authorize('create', Widget::class);

        $widget = Widget::createTranslated($request->validated());

        return redirect()->to(handle_redirect_route_after_crud_action('admin.widgets', $widget))
            ->with('success', Widget::getMsgTrans('created'));
    }

    /**
     * @param $widgetId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($widgetId)
    {
        $widget = Widget::withTrashed()->findOrFail($widgetId);
        $this->authorize('view', $widget);

        $revisionsDataTableObject = Revision::getDataTableObject('widgetRevisionsDataTable', route('admin.datatables.revisions', ['model_type' => get_class($widget), 'model_id' => $widget->id]));

        $accounts = $widget->accounts->pluck('name')->toArray();
        $accounts = implode('<br>', $accounts);

        return view('admin.widgets.show', [
            'widget' => $widget,
            'accounts' => $accounts,
            'revisionsDataTableObject' => $revisionsDataTableObject,
        ]);
    }

    /**
     * @param Widget $widget
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Widget $widget)
    {
        $this->authorize('update', $widget);

        header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
        header("Pragma: no-cache"); // HTTP 1.0.
        header("Expires: 0");

        $accounts = Account::all();

        return view('admin.widgets.edit', compact('widget', 'accounts'));
    }

    /**
     * @param UpdateWidgetRequest $request
     * @param Widget $widget
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateWidgetRequest $request, Widget $widget)
    {
        $this->authorize('update', $widget);

        $widget->updateTranslated($request->validated());
        $widget->accounts()->sync($request->get('accounts'));

        return redirect()->to(handle_redirect_route_after_crud_action('admin.widgets', $widget))
            ->with('success', Widget::getMsgTrans('updated'));
    }

    /**
     * @param $widgetId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($widgetId)
    {
        $widget = Widget::withTrashed()->findOrFail($widgetId);

        if ((int) request('delete_forever') === 1) {
            $this->authorize('delete_forever', $widget);
            $widget->forceDelete();
        } else {
            $this->authorize('delete', $widget);
            $widget->delete();
        }

        return redirect()->route('admin.widgets.index')
            ->with('success', Widget::getMsgTrans('deleted'));
    }

    /**
     * @param $widgetId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function restore($widgetId)
    {
        $widget = Widget::withTrashed()->findOrFail($widgetId);

        $this->authorize('restore', $widget);

        $widget->restore();

        return redirect()->route('admin.widgets.index')
            ->with('success', Widget::getMsgTrans('restored'));
    }

    /**
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function datatable()
    {
        $this->authorize('view_index', Widget::class);

        $query = Widget::query();
        $query->dataTableSelectRows()
            ->dataTableSetJoins()
            ->dataTablePreFilter()
            ->dataTableGroupBy();

        $table = Datatables::of($query);
        $table = Widget::dataTableFilterColumns($table);

        if(!request('export')) {
            $table = Widget::dataTableEditColumns($table);

            return $table->make(true);
        }

        Widget::dataTableExport($table);

        return response()->json([
            'success' => true,
            'message' => __("The export will run in background! When it's done we will notify you via email!")
        ]);
    }
}
