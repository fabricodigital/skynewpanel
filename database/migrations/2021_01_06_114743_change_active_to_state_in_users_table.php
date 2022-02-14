<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeActiveToStateInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('state', ['activated', 'deactivated'])->after('active');
        });

        DB::table('users')->where('active', 1)->update(['state' => 'activated']);
        DB::table('users')->where('active', 0)->update(['state' => 'deactivated']);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('active')->after('state');
        });

        DB::table('users')->where('state', 'activated')->update(['active' => 1]);
        DB::table('users')->where('state', 'deactivated')->update(['active' => 0]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('state');
        });
    }
}
