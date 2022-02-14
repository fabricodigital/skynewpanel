<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\CRUD_filename;
use App\Http\Requests\Admin\StoreCRUD_filenameRequest;
use App\Http\Requests\Admin\UpdateCRUD_filenameRequest;
use App\Http\Controllers\Controller;
use App\Models\Admin\Revision;
use Yajra\DataTables\DataTables;

class CRUD_filenameController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view_index', CRUD_filename::class);

        $dataTableObject = CRUD_filename::getDataTableObject('CRUD_lcfirstDataTable', route('admin.datatables.CRUD_route'));

        return view('admin.CRUD_route.index', compact('dataTableObject'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', CRUD_filename::class);

        $CRUD_lcfirst = CRUD_filename::class;

        return view('admin.CRUD_route.create', compact('CRUD_lcfirst'));
    }

    /**
     * @param StoreCRUD_filenameRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreCRUD_filenameRequest $request)
    {
        $this->authorize('create', CRUD_filename::class);

        $CRUD_lcfirst = CRUD_filename::create($request->validated());

        return redirect()->to(handle_redirect_route_after_crud_action('admin.CRUD_route', $CRUD_lcfirst))
            ->with('success', CRUD_filename::getMsgTrans('created'));
    }

    /**
     * @param $CRUD_lcfirstId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($CRUD_lcfirstId)
    {
        $CRUD_lcfirst = CRUD_filename::withTrashed()->findOrFail($CRUD_lcfirstId);
        $this->authorize('view', $CRUD_lcfirst);

        $revisionsDataTableObject = Revision::getDataTableObject('CRUD_lcfirstRevisionsDataTable', route('admin.datatables.revisions', ['model_type' => get_class($CRUD_lcfirst), 'model_id' => $CRUD_lcfirst->id]));

        return view('admin.CRUD_route.show', [
            'CRUD_lcfirst' => $CRUD_lcfirst,
            'revisionsDataTableObject' => $revisionsDataTableObject,
        ]);
    }

    /**
     * @param CRUD_filename $CRUD_lcfirst
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(CRUD_filename $CRUD_lcfirst)
    {
        $this->authorize('update', $CRUD_lcfirst);

        header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
        header("Pragma: no-cache"); // HTTP 1.0.
        header("Expires: 0");

        return view('admin.CRUD_route.edit', compact('CRUD_lcfirst'));
    }

    /**
     * @param UpdateCRUD_filenameRequest $request
     * @param CRUD_filename $CRUD_lcfirst
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateCRUD_filenameRequest $request, CRUD_filename $CRUD_lcfirst)
    {
        $this->authorize('update', $CRUD_lcfirst);

        $CRUD_lcfirst->update($request->validated());

        return redirect()->to(handle_redirect_route_after_crud_action('admin.CRUD_route', $CRUD_lcfirst))
            ->with('success', CRUD_filename::getMsgTrans('updated'));
    }

    /**
     * @param $CRUD_lcfirstId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($CRUD_lcfirstId)
    {
        $CRUD_lcfirst = CRUD_filename::withTrashed()->findOrFail($CRUD_lcfirstId);

        if ((int) request('delete_forever') === 1) {
            $this->authorize('delete_forever', $CRUD_lcfirst);
            $CRUD_lcfirst->forceDelete();
        } else {
            $this->authorize('delete', $CRUD_lcfirst);
            $CRUD_lcfirst->delete();
        }

        return redirect()->route('admin.CRUD_route.index')
            ->with('success', CRUD_filename::getMsgTrans('deleted'));
    }

    /**
     * @param $CRUD_lcfirstId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function restore($CRUD_lcfirstId)
    {
        $CRUD_lcfirst = CRUD_filename::withTrashed()->findOrFail($CRUD_lcfirstId);

        $this->authorize('restore', $CRUD_lcfirst);

        $CRUD_lcfirst->restore();

        return redirect()->route('admin.CRUD_route.index')
            ->with('success', CRUD_filename::getMsgTrans('restored'));
    }

    /**
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function datatable()
    {
        $this->authorize('view_index', CRUD_filename::class);

        $query = CRUD_filename::query();
        $query->dataTableSelectRows()
            ->dataTableSetJoins()
            ->dataTablePreFilter()
            ->dataTableGroupBy();

        $table = Datatables::of($query);
        $table = CRUD_filename::dataTableFilterColumns($table);

        if(!request('export')) {
            $table = CRUD_filename::dataTableEditColumns($table);

            return $table->make(true);
        }

        CRUD_filename::dataTableExport($table);

        return response()->json([
            'success' => true,
            'message' => __("The export will run in background! When it's done we will notify you via email!")
        ]);
    }
}
