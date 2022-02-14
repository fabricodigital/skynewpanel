<?php

use Illuminate\Database\Seeder;
use App\Models\Admin\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * @throws Exception
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'Super Administrator',
                'level' => 0,
                'trans' => ['en' => 'Super Administrator', 'it' => 'Super Amministratore', 'bg' => 'Супер Администратор']
            ],
            [
                'name' => 'Administrator',
                'level' => 1,
                'trans' => ['en' => 'Administrator', 'it' => 'Amministratore', 'bg' => 'Администратор']
            ],
            [
                'name' => 'User',
                'level' => 3,
                'trans' => ['en' => 'User', 'it' => 'Utente', 'bg' => 'Потребител']
            ],
            [
                'name' => 'Help',
                'level' => 2,
                'trans' => ['en' => 'Help', 'it' => 'Assistenza', 'bg' => 'Помощ']
            ],
            [
                'name' => 'Basic',
                'level' => 4,
                'trans' => ['en' => 'Basic', 'it' => 'Base', 'bg' => 'Основен']
            ],
        ];

        foreach ($roles as $role) {

            if (Role::where('name', $role['name'])->count() > 0) {
                continue;
            }

            $roleRow = null;
            foreach ($role['trans'] as $locale => $transName) {
                app()->setLocale($locale);
                if (!$roleRow) {
                    $roleRow = Role::createTranslated(['name' => $role['name'], 'level' => $role['level'], 'role_name' => $transName]);
                } else {
                    $roleRow->updateTranslated(['role_name' => $transName]);
                }
            }
        }
    }
}
