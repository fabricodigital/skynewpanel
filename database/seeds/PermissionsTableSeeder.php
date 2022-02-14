<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\Admin\Role;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $sections = config('sections');

        foreach ($sections as $section) {
            if(isset($section["children"])) {
                foreach ($section["children"] as $sectionChild) {
                    $permissionTarget = $sectionChild['permission_target'];

                    foreach ($sectionChild['permissions'] as $permission => $label) {
                        Permission::firstOrCreate([
                            'name' => "$permission $permissionTarget",
                        ]);
                    }
                }
            }else{
                $permissionTarget = $section['permission_target'];

                foreach ($section['permissions'] as $permission => $label) {
                    Permission::firstOrCreate([
                        'name' => "$permission $permissionTarget",
                    ]);
                }
            }
        }

        $superAdministrator = Role::where('name', 'Super Administrator')->first();
        $superAdministrator->givePermissionTo(Permission::all());
    }
}
