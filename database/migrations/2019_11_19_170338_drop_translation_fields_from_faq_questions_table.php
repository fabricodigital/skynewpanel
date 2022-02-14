<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTranslationFieldsFromFaqQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('faq_questions', function (Blueprint $table) {
            $table->dropColumn('question_text');
            $table->dropColumn('answer_text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('faq_questions', function (Blueprint $table) {
            $table->text('question_text');
            $table->text('answer_text');
        });
    }
}
