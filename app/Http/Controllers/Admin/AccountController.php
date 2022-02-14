<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAccountRequest;
use App\Http\Requests\Admin\UpdateAccountRequest;
use App\Models\Admin\Account;
use App\Models\Admin\Revision;
use App\Models\Admin\Widget;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class AccountController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view_index', Account::class);

        $dataTableObject = Account::getDataTableObject('accountDataTable', route('admin.datatables.accounts'));

        return view('admin.accounts.index', compact('dataTableObject'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Account::class);

        $account = Account::class;

        return view('admin.accounts.create', compact('account'));
    }

    /**
     * @param StoreAccountRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreAccountRequest $request)
    {
        $this->authorize('create', Account::class);

        $data = $request->validated();

        $account = Account::create($data);

        if (!empty($data['logo'])) {
            $mediaFile = $account->addMedia($data['logo'])->toMediaCollection('logo');
            $account->logo = $mediaFile->getFullUrl();
            $account->save();
        }

        return redirect()->to(handle_redirect_route_after_crud_action('admin.accounts', $account))
            ->with('success', Account::getMsgTrans('created'));
    }

    /**
     * @param $accountId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($accountId)
    {
        $account = Account::withTrashed()->findOrFail($accountId);
        $this->authorize('view', $account);

        $revisionsDataTableObject = Revision::getDataTableObject('accountRevisionsDataTable', route('admin.datatables.revisions', ['model_type' => get_class($account), 'model_id' => $account->id]));

        $widgets = $account->widgets->pluck('name')->toArray();
        $widgets = implode('<br>', $widgets);

        return view('admin.accounts.show', [
            'account' => $account,
            'widgets' => $widgets,
            'revisionsDataTableObject' => $revisionsDataTableObject,
        ]);
    }

    /**
     * @param Account $account
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Account $account)
    {
        $this->authorize('update', $account);

        header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
        header("Pragma: no-cache"); // HTTP 1.0.
        header("Expires: 0");

        $widgets = Widget::all();

        return view('admin.accounts.edit', compact('account', 'widgets'));
    }

    /**
     * @param UpdateAccountRequest $request
     * @param Account $account
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateAccountRequest $request, Account $account)
    {
        $this->authorize('update', $account);

        $data = $request->validated();

        $account->update($data);

        $account->widgets()->sync($request->get('widgets'));

        if (!empty($data['logo'])) {
            $account->clearMediaCollection('logo');
            $mediaFile = $account->addMedia($data['logo'])->toMediaCollection('logo');
            $account->logo = $mediaFile->getFullUrl();
            $account->save();
        }

        return redirect()->to(handle_redirect_route_after_crud_action('admin.accounts', $account))
            ->with('success', Account::getMsgTrans('updated'));
    }

    /**
     * @param $accountId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($accountId)
    {
        $account = Account::withTrashed()->findOrFail($accountId);

        if ((int) request('delete_forever') === 1) {
            $this->authorize('delete_forever', $account);
            $account->forceDelete();
        } else {
            $this->authorize('delete', $account);
            $account->delete();
        }

        return redirect()->route('admin.accounts.index')
            ->with('success', Account::getMsgTrans('deleted'));
    }

    /**
     * @param $accountId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function restore($accountId)
    {
        $account = Account::withTrashed()->findOrFail($accountId);

        $this->authorize('restore', $account);

        $account->restore();

        return redirect()->route('admin.accounts.index')
            ->with('success', Account::getMsgTrans('restored'));
    }

    /**
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function datatable()
    {
        $this->authorize('view_index', Account::class);

        $query = Account::query();
        $query->dataTableSelectRows()
            ->dataTableSetJoins()
            ->dataTablePreFilter()
            ->dataTableGroupBy();

        $table = Datatables::of($query);
        $table = Account::dataTableFilterColumns($table);

        if (!request('export')) {
            $table = Account::dataTableEditColumns($table);

            return $table->make(true);
        }

        Account::dataTableExport($table);

        return response()->json([
            'success' => true,
            'message' => __("The export will run in background! When it's done we will notify you via email!")
        ]);
    }

    /**
     * @param Account $account
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function switchAccount(Account $account)
    {
        $this->authorize('switch_account', Account::class);

        $user = Auth::user();
        $user->account_id = $account->id;
        $user->save();

        return redirect()->route('admin.home');
    }
}
