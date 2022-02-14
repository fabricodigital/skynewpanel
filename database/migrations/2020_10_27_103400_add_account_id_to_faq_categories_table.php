<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountIdToFaqCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('faq_categories', function (Blueprint $table) {
            $table->unsignedInteger('account_id')->after('id');
        });

        DB::table('faq_categories')->update(['account_id' => 1]);

        Schema::table('faq_categories', function (Blueprint $table) {
            $table->foreign('account_id', 'faq_categories_account_id_foreign')
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
        Schema::table('faq_categories', function (Blueprint $table) {
            $table->dropForeign('faq_categories_account_id_foreign');
            $table->dropColumn('account_id');
        });
    }
}
