<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\FaqQuestion;
use App\Http\Requests\Admin\StoreFaqCategoryRequest;
use App\Http\Requests\Admin\UpdateFaqCategoryRequest;
use App\Http\Controllers\Controller;
use App\Models\Admin\FaqCategory;
use App\Models\Admin\Revision;
use function compact;
use function route;
use Yajra\DataTables\DataTables;

class FaqCategoryController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view_index', FaqCategory::class);

        $dataTableObject = FaqCategory::getDataTableObject('faqCategoriesDataTable', route('admin.datatables.faq-categories'));

        return view('admin.faq-categories.index', compact('dataTableObject'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', FaqCategory::class);

        $faqCategory = FaqCategory::class;

        return view('admin.faq-categories.create', compact('faqCategory'));
    }

    /**
     * @param StoreFaqCategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreFaqCategoryRequest $request)
    {
        $this->authorize('create', FaqCategory::class);

        $faqCategory = FaqCategory::createTranslated($request->validated());

        return redirect()->to(handle_redirect_route_after_crud_action('admin.faq-categories', $faqCategory))
            ->with('success', FaqCategory::getMsgTrans('created'));
    }

    /**
     * @param $faqCategoryId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($faqCategoryId)
    {
        $faqCategory = FaqCategory::withTrashed()->findOrFail($faqCategoryId);

        $this->authorize('view', $faqCategory);

        $faqQuestionsDataTableObject = FaqQuestion::getDataTableObject('faqQuestionsDataTableIncluded', route('admin.datatables.faq-questions', ['category_id' => $faqCategory->id]));

        $revisionsDataTableObject = Revision::getDataTableObject('faqCategoriesRevisionsDataTable', route('admin.datatables.revisions', ['model_type' => get_class($faqCategory), 'model_id' => $faqCategory->id]));

        return view('admin.faq-categories.show', [
            'faqCategory' => $faqCategory,
            'faqQuestionsDataTableObject' => $faqQuestionsDataTableObject,
            'revisionsDataTableObject' => $revisionsDataTableObject,
        ]);
    }

    /**
     * @param \App\Models\Admin\FaqCategory $faqCategory
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(FaqCategory $faqCategory)
    {
        $this->authorize('update', $faqCategory);

        header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
        header("Pragma: no-cache"); // HTTP 1.0.
        header("Expires: 0");

        return view('admin.faq-categories.edit', compact('faqCategory'));
    }

    /**
     * @param UpdateFaqCategoryRequest $request
     * @param \App\Models\Admin\FaqCategory $faqCategory
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateFaqCategoryRequest $request, FaqCategory $faqCategory)
    {
        $this->authorize('update', $faqCategory);

        $faqCategory->updateTranslated($request->validated());

        return redirect()->to(handle_redirect_route_after_crud_action('admin.faq-categories', $faqCategory))
            ->with('success', FaqCategory::getMsgTrans('updated'));
    }

    /**
     * @param $faqCategoryId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($faqCategoryId)
    {
        $faqCategory = FaqCategory::withTrashed()->findOrFail($faqCategoryId);

        if ((int) request('delete_forever') === 1) {
            $this->authorize('delete_forever', $faqCategory);
            $faqCategory->forceDelete();
        } else {
            $this->authorize('delete', $faqCategory);
            $faqCategory->delete();
        }

        return redirect()->route('admin.faq-categories.index')
            ->with('success', FaqCategory::getMsgTrans('deleted'));
    }

    /**
     * @param $faqCategoryId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function restore($faqCategoryId)
    {
        $faqCategory = FaqCategory::withTrashed()->findOrFail($faqCategoryId);

        $this->authorize('restore', $faqCategory);

        $faqCategory->restore();

        return redirect()->route('admin.faq-categories.index')
            ->with('success', FaqCategory::getMsgTrans('restored'));
    }

    /**
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function datatable()
    {
        $this->authorize('view_index', FaqCategory::class);

        $query = FaqCategory::query();
        $query->dataTableSelectRows()
            ->dataTableSetJoins()
            ->dataTablePreFilter()
            ->dataTableGroupBy();

        $table = Datatables::of($query);
        $table = FaqCategory::dataTableFilterColumns($table);

        if(!request('export')) {
            $table = FaqCategory::dataTableEditColumns($table);

            return $table->make(true);
        }

        FaqCategory::dataTableExport($table);

        return response()->json([
            'success' => true,
            'message' => __("The export will run in background! When it's done we will notify you via email!")
        ]);
    }
}
