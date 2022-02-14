<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateTypeColumnWidgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE widgets MODIFY type ENUM('line', 'bar', 'pie', 'doughnut', 'radar', 'kpi', 'datatable') DEFAULT 'line'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE widgets MODIFY type ENUM('line', 'bar', 'pie', 'doughnut', 'radar', 'kpi') DEFAULT 'line'");
    }
}
