<?php

namespace App\Console\Commands;

use App\Models\Admin\JobsLog;
use App\Models\Admin\Export;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;

class ClearOldExports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:old-exports {days_ago?} {user_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear old exports and attachments';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $daysAgo = $this->argument('days_ago') ? intval($this->argument('days_ago')) : Export::EXPIRED_AFTER_DAYS;
        $date = Carbon::now()->subDays($daysAgo);
        $userId = $this->argument('user_id');

        $job = JobsLog::where('name', 'clear:old-exports')->first();

        if($job->last_state != 'queue') {
            return false;
        }

        try {
            $job->run($userId);

            $limit = 50;
            $offset = 0;

            $exports = Export::whereDate('date_start', '<', $date)
                ->limit($limit)
                ->offset($offset)
                ->get();

            while (!$exports->isEmpty()) {
                foreach ($exports as $export) {
                    $export->forceDelete();
                }

                $offset += $limit;

                $exports = Export::whereDate('date_start', '<', $date)
                    ->limit($limit)
                    ->offset($offset)
                    ->get();
            }

            $job->finish();
        }catch (Exception $exception) {
            $message = $exception->getMessage().PHP_EOL;
            $message .= $exception->getFile().PHP_EOL;
            $message .= $exception->getLine().PHP_EOL;
            $message .= $exception->getTraceAsString().PHP_EOL;
            $message .= $exception->getCode();

            $job->fail($message);
        }
    }
}
