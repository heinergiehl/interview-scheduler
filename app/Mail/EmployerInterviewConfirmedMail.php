<?php

namespace App\Mail;

use App\Models\InterviewAppointmentSuggestion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;   // ← make sure this is imported
use Illuminate\Mail\Mailables\Envelope;  // ← and this
use Illuminate\Queue\SerializesModels;

class EmployerInterviewConfirmedMail extends Mailable
{
    use Queueable, SerializesModels;
    public InterviewAppointmentSuggestion $suggestion;
    public function __construct(InterviewAppointmentSuggestion $suggestion)
    {
        $this->suggestion = $suggestion;
    }
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You Confirmed an Interview Date',
        );
    }
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.employer.confirmed-interview',
            with: [
                'suggestion' => $this->suggestion,
            ],
        );
    }
    public function attachments(): array
    {
        return [];
    }
}
