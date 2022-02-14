<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveImpersonatedByFromUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_impersonated_by_id_foreign');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('impersonated_by_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('impersonated_by_id')->nullable();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('impersonated_by_id', 'users_impersonated_by_id_foreign')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }
}
