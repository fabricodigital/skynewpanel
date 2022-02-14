<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Revision;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class RevisionController extends Controller
{

    public function index()
    {
        $this->authorize('view_index', Revision::class);

        $dataTableObject = Revision::getDataTableObject('allRevisionsDataTable', route('admin.datatables.revisions'), auth()->user(), true);

        return view('admin.revisions.index', compact('dataTableObject'));
    }

    /**
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function datatable()
    {
        $this->authorize('view_all', Revision::class);

        $query = Revision::query();
        $query->dataTableSelectRows()
            ->dataTableSetJoins()
            ->dataTablePreFilter();

        $table = Datatables::of($query);

        $table = Revision::dataTableFilterColumns($table);

        $table = Revision::dataTableEditColumns($table);

        return $table->make(true);
    }
}
