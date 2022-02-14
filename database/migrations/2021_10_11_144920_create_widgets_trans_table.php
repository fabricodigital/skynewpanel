<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWidgetsTransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widgets_trans', function (Blueprint $table) {
            $table->unsignedInteger('model_id');
            $table->string('locale', 3);
            $table->text('description');
        });

        Schema::table('widgets_trans', function (Blueprint $table) {
            $table->primary(['model_id', 'locale']);
            $table->foreign('model_id', 'widgets_trans_model_id_foreign')
                ->references('id')
                ->on('widgets')
                ->onDelete('cascade');
            $table->index('locale', 'widgets_trans_locale_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('widgets_trans');
    }
}
