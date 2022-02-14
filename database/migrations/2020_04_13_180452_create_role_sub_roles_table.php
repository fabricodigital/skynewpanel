<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleSubRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_sub_roles', function (Blueprint $table) {
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('sub_role_id');
        });

        Schema::table('role_sub_roles', function (Blueprint $table) {
            $table->foreign('role_id', 'role_sub_roles_role_id_foreign')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->foreign('sub_role_id', 'role_sub_roles_sub_role_id_foreign')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_sub_roles');
    }
}
