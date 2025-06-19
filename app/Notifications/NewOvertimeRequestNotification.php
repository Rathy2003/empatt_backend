<?php

namespace App\Notifications;

use App\Models\Overtime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOvertimeRequestNotification extends Notification
{
    use Queueable;

    public $overtime;

    public function __construct($overtime)
    {
        $this->overtime = $overtime;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New overtime request from ' . $this->overtime->user->name,
            'overtime_id' => $this->overtime->id,
            'start_time' => $this->overtime->start_time,
            'end_time' => $this->overtime->end_time,
            'date' => now()->toDateTimeString(),
        ];
    }
}
