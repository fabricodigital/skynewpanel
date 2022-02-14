<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountIdToRevisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('revisions', function (Blueprint $table) {
            $table->unsignedInteger('account_id')->after('id');
        });

        DB::table('revisions')->update(['account_id' => 1]);

        Schema::table('revisions', function (Blueprint $table) {
            $table->foreign('account_id', 'revisions_account_id_foreign')
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
        Schema::table('revisions', function (Blueprint $table) {
            $table->dropForeign('revisions_account_id_foreign');
            $table->dropColumn('account_id');
        });
    }
}
