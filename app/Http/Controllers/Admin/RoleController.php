<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use App\Models\Admin\Revision;
use App\Team;
use App\Models\Admin\User;
use function compact;
use App\Http\Controllers\Controller;
use App\Models\Admin\Role;
use function handle_redirect_route_after_crud_action;
use function response;
use function route;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view_index', Role::class);

        $dataTableObject = Role::getDataTableObject('rolesDataTable', route('admin.datatables.roles'));

        return view('admin.roles.index', compact('dataTableObject'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Role::class);

        $role = Role::class;
        $roles = Role::getLevelSelectOptions();

        return view('admin.roles.create', compact('role', 'roles'));
    }

    /**
     * @param StoreRoleRequest $request
     * @param \App\Models\Admin\Role $role
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreRoleRequest $request)
    {
        $this->authorize('create', Role::class);

        $data = $request->validated();
        $data['role_name'] = $data['name'];
        $role = Role::createTranslated($data);

        if (!empty($data['sub_roles'])) {
            $role->subRoles()->sync($data['sub_roles']);
        }

        return redirect()->to(handle_redirect_route_after_crud_action('admin.roles', $role))
            ->with('success', Role::getMsgTrans('created'));
    }

    /**
     * @param $roleId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($roleId)
    {
        $role = Role::withTrashed()->findOrFail($roleId);
        $this->authorize('view', $role);

        $usersDataTableObject = User::getDataTableObject('usersDataTableIncluded', route('admin.datatables.users', ['role_id' => $role->id]));

        $revisionsDataTableObject = Revision::getDataTableObject('rolesRevisionsDataTable', route('admin.datatables.revisions', ['model_type' => get_class($role), 'model_id' => $role->id]));

        return view('admin.roles.show', [
            'role' => $role,
            'usersDataTableObject' => $usersDataTableObject,
            'revisionsDataTableObject' => $revisionsDataTableObject,
        ]);
    }

    /**
     * @param \App\Models\Admin\Role $role
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Role $role)
    {
        $this->authorize('update', $role);

        header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
        header("Pragma: no-cache"); // HTTP 1.0.
        header("Expires: 0");

        $roles = Role::getLevelSelectOptions($role->id);

        $subRoles = Role::getSelectOptions($role->level);

        $selectedSubRoles = [];
        foreach ($role->subRoles()->get() as $subRole) {
            $selectedSubRoles[] = $subRole->id;
        }



        return view('admin.roles.edit', compact('role', 'roles', 'subRoles', 'selectedSubRoles'));
    }

    /**
     * @param UpdateRoleRequest $request
     * @param \App\Models\Admin\Role $role
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $this->authorize('update', $role);

        $data = $request->validated();
        if(app()->getLocale() == 'en') {
            $data['name'] = $data['role_name'];
        }
        $role->updateTranslated($data);

        $role->revisionableUpdateManyToMany($data);
        $role->subRoles()->sync(!empty($data['sub_roles']) ? $data['sub_roles'] : []);

        return redirect()->to(handle_redirect_route_after_crud_action('admin.roles', $role))
            ->with('success', Role::getMsgTrans('updated'));
    }

    /**
     * @param $roleId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($roleId)
    {
        $role = Role::withTrashed()->findOrFail($roleId);

        if ((int) request('delete_forever') === 1) {
            $this->authorize('delete_forever', $role);
            $role->forceDelete();
        } else {
            $this->authorize('delete', $role);
            $role->delete();
        }

        return redirect()->route('admin.roles.index')
            ->with('success', Role::getMsgTrans('deleted'));
    }

    /**
     * @param $roleId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function restore($roleId)
    {
        $role = Role::withTrashed()->findOrFail($roleId);

        $this->authorize('restore', $role);

        $role->restore();

        return redirect()->route('admin.roles.index')
            ->with('success', Role::getMsgTrans('restored'));
    }

    /**
     * @param \App\Models\Admin\Role $role
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ajaxGivePermission(Role $role)
    {
        $this->authorize('update_permissions', Role::class);

        $this->validate(request(), [
            'permission' => 'required|exists:permissions,name',
        ]);

        $role->givePermissionTo(request('permission'));

        return response()->json([
            'success' => true,
            'message' => __('Permission created successfully!')
        ], 201);
    }

    /**
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ajaxRevokePermission(Role $role)
    {
        $this->authorize('update_permissions', Role::class);

        $this->validate(request(), [
            'permission' => 'required|exists:permissions,name',
        ]);

        $role->revokePermissionTo(request('permission'));

        return response()->json([
            'success' => true,
            'message' => __('Permission removed successfully!')
        ], 201);
    }

    /**
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function datatable()
    {
        $this->authorize('view_all', Role::class);

        $query = Role::query();
        $query->dataTableSelectRows()
            ->dataTableSetJoins()
            ->dataTablePreFilter()
            ->dataTableGroupBy();

        $table = Datatables::of($query);
        $table = Role::dataTableFilterColumns($table);

        if(!request('export')) {
            $table = Role::dataTableEditColumns($table);

            return $table->make(true);
        }

        Role::dataTableExport($table);

        return response()->json([
            'success' => true,
            'message' => __("The export will run in background! When it's done we will notify you via email!")
        ]);
    }
}
