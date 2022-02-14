<?php

use App\Models\Admin\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\Admin\Account;
use App\Models\Admin\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $superAdmin = User::withoutGlobalScope('account_tenant')
            ->where('email', config('main.users.admin.email'))
            ->first();

        $account = Account::first();

        if (empty($superAdmin)) {
            $superAdmin = User::create(
                [
                    'account_id' => $account->id,
                    'email' => config('main.users.admin.email'),
                    'name' => config('main.users.admin.name'),
                    'surname' => config('main.users.admin.surname'),
                    'password' => Hash::make(config('main.users.admin.password')),
                    'email_verified_at' => Carbon::now(),
                    'state' => 'activated',
                ]
            );
        }

        $superAdministratorRole = Role::where('name', 'Super Administrator')->first();

        $superAdmin->assignRole($superAdministratorRole);
    }
}
