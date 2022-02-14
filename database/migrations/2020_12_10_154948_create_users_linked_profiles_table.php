<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersLinkedProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_linked_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('linked_user_id');
            $table->string('hash')->nullable();
            $table->timestamp('hash_expired_at')->nullable();
            $table->boolean('active');
            $table->timestamps();
        });

        Schema::table('users_linked_profiles', function (Blueprint $table) {
            $table->foreign('user_id', 'users_linked_profiles_user_id_foreign')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('linked_user_id', 'users_linked_profiles_linked_user_id_foreign')
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
        Schema::dropIfExists('users_linked_profiles');
    }
}
