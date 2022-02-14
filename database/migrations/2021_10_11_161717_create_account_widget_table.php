<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountWidgetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_widget', function (Blueprint $table) {
            $table->unsignedInteger('account_id');
            $table->unsignedInteger('widget_id');

            $table->foreign('account_id')
                ->references('id')
                ->on('accounts')
                ->onDelete('cascade');
            
            $table->foreign('widget_id')
                ->references('id')
                ->on('widgets')
                ->onDelete('cascade');

            $table->primary(['account_id', 'widget_id'], 'account_widget_relation_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_widget');
    }
}
