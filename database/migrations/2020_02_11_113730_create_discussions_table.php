<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscussionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discussions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('model_type');
            $table->unsignedInteger('model_id')->index();
            $table->text('comment');
            $table->unsignedInteger('creator_id');
            $table->timestamps();
        });

        Schema::table('discussions', function (Blueprint $table) {
            $table->foreign('creator_id', 'discussions_creator_id_foreign')
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
        Schema::dropIfExists('discussions');
    }
}
