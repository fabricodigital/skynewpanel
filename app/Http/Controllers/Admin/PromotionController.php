<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Promotion;
use App\Http\Requests\Admin\StorePromotionRequest;
use App\Http\Requests\Admin\UpdatePromotionRequest;
use App\Http\Controllers\Controller;
use App\Models\Admin\Revision;
use Yajra\DataTables\DataTables;

class PromotionController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view_index', Promotion::class);

        $dataTableObject = Promotion::getDataTableObject('promotionDataTable', route('admin.datatables.promotions'));

        return view('admin.promotions.index', compact('dataTableObject'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Promotion::class);

        $promotion = Promotion::class;

        return view('admin.promotions.create', compact('promotion'));
    }

    /**
     * @param StorePromotionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StorePromotionRequest $request)
    {
        $this->authorize('create', Promotion::class);

        $promotion = Promotion::create($request->validated());

        return redirect()->to(handle_redirect_route_after_crud_action('admin.promotions', $promotion))
            ->with('success', Promotion::getMsgTrans('created'));
    }

    /**
     * @param $promotionId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($promotionId)
    {
        $promotion = Promotion::withTrashed()->findOrFail($promotionId);
        $this->authorize('view', $promotion);

        $revisionsDataTableObject = Revision::getDataTableObject('promotionRevisionsDataTable', route('admin.datatables.revisions', ['model_type' => get_class($promotion), 'model_id' => $promotion->id]));

        return view('admin.promotions.show', [
            'promotion' => $promotion,
            'revisionsDataTableObject' => $revisionsDataTableObject,
        ]);
    }

    /**
     * @param Promotion $promotion
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Promotion $promotion)
    {
        $this->authorize('update', $promotion);

        header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
        header("Pragma: no-cache"); // HTTP 1.0.
        header("Expires: 0");

        return view('admin.promotions.edit', compact('promotion'));
    }

    /**
     * @param UpdatePromotionRequest $request
     * @param Promotion $promotion
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdatePromotionRequest $request, Promotion $promotion)
    {
        $this->authorize('update', $promotion);

        $promotion->update($request->validated());

        return redirect()->to(handle_redirect_route_after_crud_action('admin.promotions', $promotion))
            ->with('success', Promotion::getMsgTrans('updated'));
    }

    /**
     * @param $promotionId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($promotionId)
    {
        $promotion = Promotion::withTrashed()->findOrFail($promotionId);

        if ((int) request('delete_forever') === 1) {
            $this->authorize('delete_forever', $promotion);
            $promotion->forceDelete();
        } else {
            $this->authorize('delete', $promotion);
            $promotion->delete();
        }

        return redirect()->route('admin.promotions.index')
            ->with('success', Promotion::getMsgTrans('deleted'));
    }

    /**
     * @param $promotionId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function restore($promotionId)
    {
        $promotion = Promotion::withTrashed()->findOrFail($promotionId);

        $this->authorize('restore', $promotion);

        $promotion->restore();

        return redirect()->route('admin.promotions.index')
            ->with('success', Promotion::getMsgTrans('restored'));
    }

    /**
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function datatable()
    {
        $this->authorize('view_index', Promotion::class);

        $query = Promotion::query();
        $query->dataTableSelectRows()
            ->dataTableSetJoins()
            ->dataTablePreFilter()
            ->dataTableGroupBy();

        $table = Datatables::of($query);
        $table = Promotion::dataTableFilterColumns($table);

        if(!request('export')) {
            $table = Promotion::dataTableEditColumns($table);

            return $table->make(true);
        }

        Promotion::dataTableExport($table);

        return response()->json([
            'success' => true,
            'message' => __("The export will run in background! When it's done we will notify you via email!")
        ]);
    }
}
