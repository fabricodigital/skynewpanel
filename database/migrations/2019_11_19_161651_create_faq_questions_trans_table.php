<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Admin\FaqQuestion;

class CreateFaqQuestionsTransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faq_questions_trans', function (Blueprint $table) {
            $table->unsignedInteger('model_id');
            $table->string('locale', 3);
            $table->text('question_text');
            $table->text('answer_text');
        });

        Schema::table('faq_questions_trans', function (Blueprint $table) {
            $table->primary(['model_id', 'locale']);
            $table->foreign('model_id', 'faq_questions_trans_model_id_foreign')
                ->references('id')
                ->on('faq_questions')
                ->onDelete('cascade');
            $table->index('locale', 'faq_questions_trans_locale_index');
        });

        $faqQuestions = DB::table('faq_questions')->get();
        $locales = config('main.available_languages');

        foreach ($locales as $locale => $label) {
            foreach ($faqQuestions as $q) {
                DB::table('faq_questions_trans')
                    ->insert([
                        'model_id' => $q->id,
                        'locale' => $locale,
                        'question_text' => $q->question_text,
                        'answer_text' => $q->answer_text,
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faq_questions_trans');
    }
}
