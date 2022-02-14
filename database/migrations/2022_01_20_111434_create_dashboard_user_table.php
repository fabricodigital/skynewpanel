<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDashboardUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dashboard_user', function (Blueprint $table) {
            $table->integer('dashboard_id', false, true);
            $table->integer('user_id', false, true);
        });

        Schema::table('dashboard_user', function (Blueprint $table) {
            $table->foreign('dashboard_id')->references('id')->on('dashboards')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dashboard_user');
    }
}
