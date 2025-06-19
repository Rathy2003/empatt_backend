<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewLeaveRequestNotification extends Notification
{
    use Queueable;

    protected $leave;

    public function __construct($leave)
    {
        $this->leave = $leave;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => "New leave request submitted from " . $this->leave->user->name,
            'leave_id' => $this->leave->id,
            'requested_by' => $this->leave->user->name,
            'date' => now()->toDateTimeString(),
        ];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'New leave request submitted',
            'leave_id' => $this->leave->id,
        ];
    }
}
