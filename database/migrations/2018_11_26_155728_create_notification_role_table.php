<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_role', function (Blueprint $table) {
            $table->unsignedInteger('notification_id');
            $table->unsignedInteger('role_id');
            $table->timestamp("created_at")->default(DB::raw("CURRENT_TIMESTAMP"));
        });

        Schema::table('notification_role', function (Blueprint $table) {

            $table->primary(['notification_id', 'role_id'], 'not_user_notification_id_role_id_primary');
            $table->foreign('notification_id', 'notification_role_notification_id_foreign')
                ->references('id')
                ->on('notifications')
                ->onDelete('cascade');

            $table->foreign('role_id', 'notification_role_role_id_foreign')
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
        Schema::dropIfExists('notification_role');
    }
}
