<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Admin\FaqCategory;

class CreateFaqCategoriesTransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faq_categories_trans', function (Blueprint $table) {
            $table->unsignedInteger('model_id');
            $table->string('locale', 3);
            $table->string('title');
        });

        Schema::table('faq_categories_trans', function (Blueprint $table) {
            $table->primary(['model_id', 'locale']);
            $table->foreign('model_id', 'faq_categories_trans_model_id_foreign')
                ->references('id')
                ->on('faq_categories')
                ->onDelete('cascade');
            $table->index('locale', 'faq_categories_trans_locale_index');
        });

        $faqCat = DB::table('faq_categories')->get();
        $locales = config('main.available_languages');

        foreach ($locales as $locale => $label) {
            foreach ($faqCat as $cat) {
                DB::table('faq_categories_trans')
                    ->insert([
                        'model_id' => $cat->id,
                        'title' => $cat->title,
                        'locale' => $locale,
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
        Schema::dropIfExists('faq_categories_trans');
    }
}
