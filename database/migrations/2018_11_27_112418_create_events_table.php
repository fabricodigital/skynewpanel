<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('color');
            $table->text('description')->nullable();
            $table->dateTime('start');
            $table->dateTime('end');
            $table->unsignedInteger('creator_id');
            $table->timestamps();
        });

        Schema::table('events', function (Blueprint $table) {
            $table->foreign('creator_id', 'events_creator_id_foreign')
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
        Schema::dropIfExists('events');
    }
}
