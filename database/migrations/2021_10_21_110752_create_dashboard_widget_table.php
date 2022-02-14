<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDashboardWidgetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dashboard_widget', function (Blueprint $table) {
            $table->unsignedInteger('dashboard_id');
            $table->unsignedInteger('widget_id');

            $table->foreign('dashboard_id')
                ->references('id')
                ->on('dashboards')
                ->onDelete('cascade');
            
            $table->foreign('widget_id')
                ->references('id')
                ->on('widgets')
                ->onDelete('cascade');

            $table->primary(['dashboard_id', 'widget_id'], 'dashboard_widget_relation_primary');
        });

        // Schema::table('dashboard_widget', function($table) {
        //     $table->foreign('dashboard_id')->references('id')->on('dashboards');
        // });

        // Schema::table('dashboard_widget', function($table) {
        //     $table->foreign('widget_id')->references('id')->on('widgets');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dashboard_widget');
    }
}
