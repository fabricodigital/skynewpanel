<?php

use App\Models\Admin\JobsLog;
use Illuminate\Database\Seeder;

class JobsLogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        JobsLog::firstOrCreate([
            'name' => 'clear:old-exports',
        ],[
            'last_state' => 'completed',
        ]);
    }
}
