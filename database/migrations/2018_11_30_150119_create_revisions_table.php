<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRevisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revisions', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('model');
            $table->unsignedInteger('creator_id')->nullable();
            $table->string('type');
            $table->json('old')->nullable();
            $table->json('new')->nullable();
            $table->string('ip')->nullable();
            $table->timestamps();
        });

        Schema::table('revisions', function (Blueprint $table) {
            $table->foreign('creator_id', 'revisions_creator_id_foreign')
                ->references('id')
                ->on('users')
                ->onDelete(' set null');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revisions');
    }
}
