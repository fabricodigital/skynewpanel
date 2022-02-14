<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_user', function (Blueprint $table) {
            $table->unsignedInteger('event_id');
            $table->unsignedInteger('user_id');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::table('event_user', function (Blueprint $table) {
            $table->primary(['event_id', 'user_id'], 'event_user_event_id_user_id_primary');
            $table->foreign('event_id', 'event_user_event_id_foreign')
                ->references('id')
                ->on('events')
                ->onDelete('cascade');

            $table->foreign('user_id', 'event_user_user_id_foreign')
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
        Schema::dropIfExists('event_user');
    }
}
