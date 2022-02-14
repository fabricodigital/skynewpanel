<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameReportsToExportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('reports', 'exports');

        DB::statement('UPDATE media SET model_type = "App\\\Models\\\Admin\\\Export" WHERE model_type = "App\\\Models\\\Admin\\\Report"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('exports', 'reports');

        DB::statement('UPDATE media SET model_type = "App\\\Models\\\Admin\\\Report" WHERE model_type = "App\\\Models\\\Admin\\\Export"');
    }
}
