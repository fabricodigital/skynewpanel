<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountIdToMessengerTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messenger_topics', function (Blueprint $table) {
            $table->unsignedInteger('account_id')->after('id');
        });

        DB::table('messenger_topics')->update(['account_id' => 1]);

        Schema::table('messenger_topics', function (Blueprint $table) {
            $table->foreign('account_id', 'messenger_topics_account_id_foreign')
                ->references('id')
                ->on('accounts')
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
        Schema::table('messenger_topics', function (Blueprint $table) {
            $table->dropForeign('messenger_topics_account_id_foreign');
            $table->dropColumn('account_id');
        });
    }
}
