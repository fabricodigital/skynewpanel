<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Permission;
use App\Models\Admin\User;
use App\Http\Controllers\Controller;
use App\Models\Admin\Role;
use function compact;
use function request;
use function response;
use function view;

class PermissionController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view_index', Permission::class);

        $sections = config('sections');
        $roles = Role::getSelectFilter();

        return view('admin.permissions.index', [
            'sections' => $sections,
            'roles' => $roles,
        ]);
    }

    public function ajaxGetTableView()
    {
        $this->authorize('view_index', Permission::class);

        $this->validate(request(), [
            'permissions_sections_filter' => 'nullable',
            'permissions_roles_filter.*' => 'nullable|exists:roles,id',
        ]);

        if(empty(request('permissions_sections_filter')) && empty(request('permissions_roles_filter'))){
            return response()->json([
                'success' => true,
                'view' => "",
            ]);
        }

        $sections = config('sections');
        if(request('permissions_sections_filter') != 'all') {
            foreach ($sections as $section) {
                if(isset($section["children"])) {
                    foreach ($section["children"] as $sectionChild) {
                        if($sectionChild['permission_target'] == request('permissions_sections_filter')){
                            $filteredSection = $section;
                            $filteredSection["children"] = [$sectionChild];
                            $sections = [$filteredSection];
                            break;
                        }
                    }
                }else{
                    if($section['permission_target'] == request('permissions_sections_filter')){
                        $filteredSection = $section;
                        $sections = [$filteredSection];
                        break;
                    }
                }
            }
        }

        if(!empty(request('user_id'))){
            $user = User::find(request('user_id'));

            $rolesQB = Role::with('permissions')
                ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->where('model_has_roles.model_id', $user->id)
                ->where('model_type', User::class)
                ->orderBy('roles.level', 'ASC');

            if(!empty(request('permissions_roles_filter'))) {
                $rolesQB->whereIn('roles.id', request('permissions_roles_filter'));
            }

            $roles = $rolesQB->get(['roles_trans.role_name', 'roles.id']);

            $view = view('admin.users.partials._permissions-table', compact('sections', 'roles', 'user'))->render();
        }else{
            if(empty(request('permissions_roles_filter'))) {
                $roles = Role::with('permissions')
                            ->orderBy('roles.level', 'ASC')
                            ->get(['roles_trans.role_name', 'roles.id']);
            }else{

                $roles = Role::with('permissions')
                    ->whereIn('roles.id', request('permissions_roles_filter'))
                    ->orderBy('roles.level', 'ASC')
                    ->get(['roles_trans.role_name', 'roles.id']);
            }

            $view = view('admin.permissions.partials._permissions-table', compact('sections', 'roles'))->render();
        }


        return response()->json([
            'success' => true,
            'view' => $view,
        ]);
    }
}
