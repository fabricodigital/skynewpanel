<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameForeignKeyInExportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exports', function (Blueprint $table) {
            $table->dropForeign('reports_creator_id_foreign');

            $table->foreign('creator_id', 'exports_creator_id_foreign')
                ->references('id')
                ->on('users')
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
        Schema::table('exports', function (Blueprint $table) {
            $table->dropForeign('exports_creator_id_foreign');

            $table->foreign('creator_id', 'reports_creator_id_foreign')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }
}
