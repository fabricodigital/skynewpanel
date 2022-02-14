<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobFailed extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var $jobsLog
     */
    public $jobsLog;

    /**
     * JobFailed constructor.
     * @param $jobsLog
     */
    public function __construct($jobsLog)
    {
        $this->jobsLog = $jobsLog;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        app()->setLocale("it");

        $this->to(config('main.emails.mail_system_messages'));
        $this->from(config('main.emails.no_replay'));
        $this->subject(config('app.name')." - ".__('Job failed'));

        return $this->view('emails.admin.job-failed')
            ->with('jobsLog', $this->jobsLog);
    }
}
