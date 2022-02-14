<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\JobsLog;
use App\Models\Admin\Export;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use function compact;
use function route;
use Yajra\DataTables\DataTables;

class ExportController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view_index', Export::class);

        $dataTableObject = Export::getDataTableObject('exportsDataTable', route('admin.datatables.exports'));

        $clearOldJob = JobsLog::where('name', 'clear:old-exports')->first();

        return view('admin.exports.index', compact('dataTableObject', 'clearOldJob'));
    }

    /**
     * @param $exportId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($exportId)
    {
        $export = Export::withTrashed()->findOrFail($exportId);

        if ((int) request('delete_forever') === 1) {
            $this->authorize('delete_forever', $export);
            $export->forceDelete();
        } else {
            $this->authorize('delete', $export);
            $export->delete();
        }

        return redirect()->route('admin.exports.index')
            ->with('success', Export::getMsgTrans('deleted'));
    }

    /**
     * @param $exportId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function restore($exportId)
    {
        $export = Export::withTrashed()->findOrFail($exportId);

        $this->authorize('restore', $export);

        $export->restore();

        return redirect()->route('admin.exports.index')
            ->with('success', Export::getMsgTrans('restored'));
    }

    /**
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function datatable()
    {
        $this->authorize('view_index', Export::class);

        $query = Export::query();
        $query->dataTableSelectRows()
            ->dataTableSetJoins()
            ->dataTablePreFilter()
            ->dataTableGroupBy();

        $table = Datatables::of($query);

        $table = Export::dataTableFilterColumns($table);

        $table = Export::dataTableEditColumns($table);

        return $table->make(true);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function postClearOld()
    {
        $this->authorize('clear_old', Export::class);

        JobsLog::updateOrCreate([
            'name' => 'clear:old-exports'
        ], [
            'last_date_start' => Carbon::now(),
            'last_creator_id' => auth()->id(),
            'last_state' => 'queue',
        ]);

        Artisan::queue('clear:old-exports', [
            'user_id' => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', __("The job will run in background! Please check it later!"));
    }
}
