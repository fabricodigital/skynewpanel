<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Admin\Event;

class CreateEventsTransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events_trans', function (Blueprint $table) {
            $table->unsignedInteger('model_id');
            $table->string('locale', 3);
            $table->string('title');
            $table->text('description')->nullable();
        });

        Schema::table('events_trans', function (Blueprint $table) {
            $table->primary(['model_id', 'locale']);
            $table->foreign('model_id', 'events_trans_model_id_foreign')
                ->references('id')
                ->on('events')
                ->onDelete('cascade');
            $table->index('locale', 'events_trans_locale_index');
        });

        $events = Event::get();
        $locales = config('main.available_languages');

        foreach ($locales as $locale => $label) {
            foreach ($events as $event) {
                DB::table('events_trans')
                    ->insert([
                        'model_id' => $event->id,
                        'locale' => $locale,
                        'title' => $event->title,
                        'description' => $event->description,
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
        Schema::dropIfExists('events_trans');
    }
}
