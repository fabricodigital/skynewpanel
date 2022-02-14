<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Admin\Notification;

class CreateNotificationsTransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications_trans', function (Blueprint $table) {
            $table->unsignedInteger('model_id');
            $table->string('locale', 3);
            $table->string('title');
            $table->text('text');
        });

        Schema::table('notifications_trans', function (Blueprint $table) {
            $table->primary(['model_id', 'locale']);
            $table->foreign('model_id', 'notifications_trans_model_id_foreign')
                ->references('id')
                ->on('notifications')
                ->onDelete('cascade');
            $table->index('locale', 'notifications_trans_locale_index');
        });

        $notifications = DB::table('notifications')->get();
        $locales = config('main.available_languages');

        foreach ($locales as $locale => $label) {
            foreach ($notifications as $not) {
                DB::table('notifications_trans')
                    ->insert([
                        'model_id' => $not->id,
                        'locale' => $locale,
                        'title' => $not->title,
                        'text' => $not->text,
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
        Schema::dropIfExists('notifications_trans');
    }
}
