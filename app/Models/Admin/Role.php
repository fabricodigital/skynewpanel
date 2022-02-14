<?php

namespace App\Models\Admin;

use App\Traits\Translations\Admin\RoleTranslation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role as BaseRole;
use App\Traits\Revisionable\Admin\RoleRevisionable;
use App\Traits\DataTables\Admin\RoleDataTable;

class Role extends BaseRole
{
    use RoleRevisionable;
    use RoleDataTable;
    use RoleTranslation;
    use SoftDeletes;

    protected $guard_name = 'web';

    protected $guarded = ['sub_roles'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function subRoles()
    {
        return $this->belongsToMany(Role::class, 'role_sub_roles', 'role_id', 'sub_role_id')->select('roles.*', 'roles_trans.*');
    }

    /**
     * @return mixed
     */
    public static function getSelectOptions($level = null)
    {
        if(empty($level)) {
            return self::get()->pluck('role_name', 'id');
        } else {
            return self::where('level', '>', $level)->get()->pluck('role_name', 'id');
        }
    }

    /**
     * @param bool $allowOwnRoles
     * @return mixed
     */
    public static function getUserSelectOptions($allowOwnRoles = false)
    {
        //Check if user can access all roles
        $all = false;
        $roleIds = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->level == 0) {
                $all = true;
                break;
            }
            $roleIds[] = $role->id;
        }
        if ($all) {
            return self::getSelectOptions();
        }

        //Check if user has a role, that can access all higher level roles
        $rolesSubRolesCount = self::select([
                'id',
                'level',
                'count' => DB::raw('COUNT(rsr.sub_role_id) as count')
            ])
            ->leftJoin('role_sub_roles as rsr', 'rsr.role_id', 'roles.id')
            ->whereIn('roles.id', $roleIds)
            ->groupBy('roles.id')
            ->get();
        $allAboveLevel = null;
        foreach ($rolesSubRolesCount as $row) {
            if ($row->count == 0 && (is_null($allAboveLevel) || $row->level > $allAboveLevel)) {
                $allAboveLevel = $row->level;
            }
        }

        //Build query that retrieves roles
        $query = self::select('role_name', 'id')
                    ->leftJoin('role_sub_roles as rsr', 'rsr.sub_role_id', 'roles.id')
                    ->whereIn('rsr.role_id', $roleIds);
        if ($allowOwnRoles) {
            $query->orWhereIn('roles.id', $roleIds);
        }
        if (!is_null($allAboveLevel)) {
            $query->orWhere('level', '>', $allAboveLevel);
        }

        return $query->get()->pluck('role_name', 'id');
    }

    /**
     * @param $excludeId
     * @return mixed
     */
    public static function getLevelSelectOptions($excludeId = null)
    {
        return self::select(['id', 'role_name', 'level'])
                        ->where('id', '!=', $excludeId)
                        ->get()
                        ->toArray();
    }

    /**
     * @param $level
     * @return mixed
     */
    public static function getSubLevelRoleIds($level)
    {
        return self::where('level', '>', $level)->get()->pluck('id')->toArray();
    }

    /**
     * @return mixed
     */
    public static function getSelectFilter()
    {
        return self::select(['id', 'role_name'])->get();
    }

    /**
     * @param Collection $roles
     * @return Collection
     */
    public static function transformForSelectsFilters(Collection $roles): Collection
    {
        foreach ($roles as $role) {
            $role->label = $role->role_name;
            $role->value = $role->id;
        }

        return $roles->sortBy("label");
    }
}
