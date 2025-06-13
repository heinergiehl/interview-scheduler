<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\{Notification, Messages\MailMessage};
use App\Models\InterviewSuggestion;

class InterviewAccepted extends Notification implements ShouldQueue
{
    use Queueable;
    public function __construct(public InterviewSuggestion $suggestion) {}
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Interview confirmed 🎉')
            ->line('Your proposed slot has been accepted!')
            ->line($this->suggestion->suggested_at->format('F j, Y H:i'));
    }
    public function toArray($notifiable): array
    {
        return [
            'suggestion_id' => $this->suggestion->id,
            'scheduled_at' => $this->suggestion->suggested_at,
        ];
    }
}
