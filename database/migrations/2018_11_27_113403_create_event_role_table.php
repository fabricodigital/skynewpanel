<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_role', function (Blueprint $table) {
            $table->unsignedInteger('event_id');
            $table->unsignedInteger('role_id');
            $table->timestamps();
        });

        Schema::table('event_role', function (Blueprint $table) {
            $table->primary(['event_id', 'role_id'], 'event_role_event_id_role_id_primary');
            $table->foreign('event_id', 'event_role_event_id_foreign')
                ->references('id')
                ->on('events')
                ->onDelete('cascade');

            $table->foreign('role_id', 'event_role_role_id_foreign')
                ->references('id')
                ->on('roles')
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
        Schema::dropIfExists('event_role');
    }
}
