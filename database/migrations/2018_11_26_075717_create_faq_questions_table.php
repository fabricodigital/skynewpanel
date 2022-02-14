<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaqQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faq_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->text('question_text');
            $table->text('answer_text');
            $table->unsignedInteger('category_id');
            $table->timestamps();
        });

        Schema::table('faq_questions', function (Blueprint $table) {
            $table->foreign('category_id', 'faq_questions_category_id_foreign')
                ->references('id')
                ->on('faq_categories')
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
        Schema::dropIfExists('faq_questions');
    }
}
