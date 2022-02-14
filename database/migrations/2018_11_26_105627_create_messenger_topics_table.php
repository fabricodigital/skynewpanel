<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessengerTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messenger_topics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject');
            $table->integer('sender_id');
            $table->integer('receiver_id')->nullable();
            $table->unsignedInteger('role_id')->nullable();
            $table->enum('type', ['direct', 'help'])->default('direct');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messenger_topics');
    }
}
