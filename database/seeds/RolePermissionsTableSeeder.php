<?php

use Illuminate\Database\Seeder;
use App\Models\Admin\Role;
use App\Models\Admin\Permission;

class RolePermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdministrator = Role::where('name', 'Super Administrator')->first();
        $superAdministrator->givePermissionTo(Permission::all());

        $linkProfilesPermission = Permission::where('name', 'link_profiles users')->first();
        if (!empty($linkProfilesPermission)) {
            foreach (Role::all() as $role) {
                $role->givePermissionTo([$linkProfilesPermission]);
            }
        }
    }
}
