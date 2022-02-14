<?php

use App\Models\Admin\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles_trans', function (Blueprint $table) {
            $table->unsignedInteger('role_id');
            $table->string('role_name');
            $table->string('locale', 3);
        });

        Schema::table('roles_trans', function (Blueprint $table) {
            $table->primary(['role_id', 'locale']);
            $table->foreign('role_id', 'roles_role_id_foreign')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');
            $table->index('locale', 'roles_trans_locale_index');
        });

        $roles = DB::table('roles')->get();
        $locales = config('main.available_languages');

        foreach ($locales as $locale => $label) {
            foreach ($roles as $role) {
                DB::table('roles_trans')
                    ->insert([
                        'role_id' => $role->id,
                        'role_name' => $role->name,
                        'locale' => $locale,
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles_trans');
    }
}
