<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\Admin\Account;
use App\Models\Admin\Revision;
use App\Models\Admin\Role;
use App\Models\Admin\User;
use Auth;
use function __;
use function compact;
use function dd;
use function request;
use function session;
use Hash;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view_index', User::class);

        $dataTableObject = User::getDataTableObject('usersDataTable', route('admin.datatables.users'));

        return view('admin.users.index', compact('dataTableObject'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', User::class);

        $roles = Role::getUserSelectOptions();

        $user = User::class;

        return view('admin.users.create', compact('roles', 'user'));
    }

    /**
     * @param StoreUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);

        $data = $request->validated();

        $user = User::create($data);

        if(Auth::user()->can('assign_roles', User::class)) {
            $user->roles()->sync($data['roles']);
        }

        if($request->file('image')) {
            $user->clearMediaCollection('profile-image');
            $user->addMediaFromRequest('image')->toMediaCollection('profile-image');
        }

        return redirect()->to(handle_redirect_route_after_crud_action('admin.users', $user))
            ->with('success', User::getMsgTrans('created'));
    }

    /**
     * @param $userId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($userId)
    {
        $user = User::withTrashed()->findOrFail($userId);
        $this->authorize('view', $user);

        $revisionsDataTableObject = Revision::getDataTableObject('usersRevisionsDataTable', route('admin.datatables.revisions', ['model_type' => get_class($user), 'model_id' => $user->id]));

        return view('admin.users.show', compact('user', 'revisionsDataTableObject'));
    }

    /**
     * @param \App\Models\Admin\User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        $user->load('roles');
        $roles = Role::getUserSelectOptions(Auth::user()->id == $user->id);

        $sections = config('sections');

        header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
        header("Pragma: no-cache"); // HTTP 1.0.
        header("Expires: 0");

        return view('admin.users.edit', compact('user', 'roles', 'sections'));
    }

    /**
     * @param UpdateUserRequest $request
     * @param \App\Models\Admin\User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $data = $request->validated();

        $user->update($data);
        $user->revisionableUpdateManyToMany($data);

        if(Auth::user()->can('assign_roles', User::class)) {
            $user->roles()->sync($data['roles']);
        }

        return redirect()->to(handle_redirect_route_after_crud_action('admin.users', $user))
            ->with('success', User::getMsgTrans('updated'));
    }

    /**
     * @param $userId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($userId)
    {
        $user = User::withTrashed()->findOrFail($userId);

        if ((int) request('delete_forever') === 1) {
            $this->authorize('delete_forever', $user);
            $user->forceDelete();
        } else {
            $this->authorize('delete', $user);
            $user->delete();
        }

        return redirect()->route('admin.users.index')
            ->with('success', User::getMsgTrans('deleted'));
    }

    /**
     * @param $userId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function restore($userId)
    {
        $user = User::withTrashed()->findOrFail($userId);

        $this->authorize('restore', $user);

        $user->restore();

        return redirect()->route('admin.users.index')
            ->with('success', User::getMsgTrans('restored'));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxSearch()
    {
        $users = User::search(\request('needle'));

        return response()->json($users);
    }

    /**
     * @param \App\Models\Admin\User $user
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ajaxGivePermission(User $user)
    {
        $this->authorize('update_permissions', User::class);

        $this->validate(request(), [
            'permission' => 'required|exists:permissions,name',
        ]);

        $user->addPermission(request('permission'));

        return response()->json([
            'success' => true,
            'message' => __('Permission created successfully!')
        ], 201);
    }

    /**
     * @param \App\Models\Admin\User $user
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ajaxRevokePermission(User $user)
    {
        $this->authorize('update_permissions', User::class);

        $this->validate(request(), [
            'permission' => 'required|exists:permissions,name',
        ]);

        $user->removePermission(request('permission'));

        return response()->json([
            'success' => true,
            'message' => __('Permission removed successfully!')
        ], 201);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function datatable()
    {
        $activityStatusTimeIntervalInactive = config('main.activity_status_time_intervals.inactive');
        $activityStatusTimeIntervalOffline = config('main.activity_status_time_intervals.offline');

        $query = User::query();
        $query->dataTableSelectRows($activityStatusTimeIntervalInactive, $activityStatusTimeIntervalOffline)
            ->dataTableSetJoins()
            ->dataTablePreFilter()
            ->dataTableGroupBy();

        $table = Datatables::of($query);

        $table = User::dataTableFilterColumns($table, $activityStatusTimeIntervalInactive, $activityStatusTimeIntervalOffline);

        if(!request('export')) {
            $table = User::dataTableEditColumns($table);

            return $table->make(true);
        }

        User::dataTableExport($table);

        return response()->json([
            'success' => true,
            'message' => __("The export will run in background! When it's done we will notify you via email!")
        ]);
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function postImpersonate(User $user)
    {
        $this->authorize('impersonate', $user);

        $originUserId = auth()->id();

        auth()->logout();
        auth()->login($user);
        session()->put('impersonated_by', $originUserId);

        return redirect()->route('admin.home');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function postImpersonateBack()
    {
        $this->authorize('impersonate_back', User::class);

        $impersonatedBy = User::find(session()->get('impersonated_by'));

        auth()->logout();

        session()->invalidate();

        auth()->login($impersonatedBy);

        return redirect()->route('admin.home');
    }
}
