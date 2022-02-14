<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateTypesInWidgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE widgets MODIFY type ENUM('line', 'bar', 'pie', 'doughnut', 'radar', 'kpi') DEFAULT 'line'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE widgets MODIFY type ENUM('line', 'bar', 'pie') DEFAULT 'line'");
    }
}
