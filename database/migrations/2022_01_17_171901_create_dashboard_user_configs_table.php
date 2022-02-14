<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDashboardUserConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dashboard_user_configs', function (Blueprint $table) {
            $table->id();
            $table->integer('account_id', false, true);
            $table->integer('user_id', false, true);
            $table->integer('dashboard_id', false, true);
            $table->integer('widget_id', false, true);
            $table->integer('widget_position', false, true)->nullable();
            $table->json('widget_settings')->nullable();
            $table->timestamps();
            $table->dateTime('deleted_at');
        });

        Schema::table('dashboard_user_configs', function (Blueprint $table) {
            $table->foreign('account_id')->references('id')->on('accounts')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('dashboard_id')->references('id')->on('dashboards')->cascadeOnDelete();
            $table->foreign('widget_id')->references('id')->on('widgets')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dashboard_user_configs');
    }
}
