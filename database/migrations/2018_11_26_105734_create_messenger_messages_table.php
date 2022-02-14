<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateMessengerMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messenger_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('topic_id');
            $table->integer('sender_id');
            $table->integer('receiver_id')->nullable();
            $table->string('receiver_model')->nullable();
            $table->integer('role_id')->nullable();
            $table->text('content');
            $table->timestamp('sent_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('read_at')->nullable();
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
        Schema::dropIfExists('messenger_messages');
    }
}
