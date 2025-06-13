<?php
// app/Mail/EmployerInterviewAcceptedMail.php
namespace App\Mail;

use App\Models\InterviewAppointmentSuggestion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmployerInterviewAcceptedMail extends Mailable
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
            subject: 'Your Interview Slot Was Accepted',
        );
    }
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.employer.accepted-interview',
            with: [
                'suggestion' => $this->suggestion,
            ],
        );
    }
}
