<?php

namespace App\Models\Admin;

use App\Mail\JobFailed;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class JobsLog extends Model
{
    protected $guarded = [];

    protected $dates = [
        'last_date_start',
        'last_date_end',
    ];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'last_creator_id', 'id');
    }

    /**
     * @param $userId
     */
    public function run($userId = null)
    {
        $this->update([
            'last_state' => 'running',
            'last_date_start' => Carbon::now(),
            'last_message' => '',
            'last_creator_id' => $userId
        ]);
    }

    public function finish()
    {
        $this->update([
            'last_state' => 'completed',
            'last_message' => '',
            'last_date_end' => Carbon::now(),
        ]);
    }

    public function fail($message, $lastState = 'failed')
    {
        $now = Carbon::now();
        $this->fresh();

        $this->update([
            'last_state' => $lastState,
            'last_message' => $message,
            'last_date_end' => $now,
        ]);

        JobsLogsFail::create([
            'job_id' => $this->id,
            'creator_id' => $this->last_creator_id,
            'date_start' => $this->last_date_start,
            'date_end' => $now,
            'message' => $message,
        ]);

        Mail::send(new JobFailed($this));
    }
}
