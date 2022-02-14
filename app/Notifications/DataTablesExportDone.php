<?php

namespace App\Notifications;

use function __;
use App\Models\Admin\Export;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use function route;

class DataTablesExportDone extends Notification
{
    use Queueable;
    /**
     * @var \App\Models\Admin\Export
     */
    public $export;

    /**
     * DataTablesExportDone constructor.
     * @param Export $export
     */
    public function __construct(Export $export)
    {
        $this->export = $export;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $state = '';
        switch ($this->export->state){
            case 'in_progress':
                $state = __('In progress');
                break;
            case 'completed':
                $state =  __('Completed');
                break;
            case 'failed':
                $state = __('Failed');
                break;
        }

        return (new MailMessage)
            ->from(config('main.emails.no_replay'))
            ->subject(__('Requested export'))
            ->view('emails.admin.dt-export-done', ['notifiable' => $notifiable, 'export' => $this->export, 'state' => $state]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
