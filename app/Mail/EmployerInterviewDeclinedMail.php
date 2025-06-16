<?php

namespace App\Mail;

use App\Models\InterviewAppointmentSuggestion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmployerInterviewDeclineddMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * Create a new message instance.
     */
    public function __construct(public InterviewAppointmentSuggestion $suggestion)
    {
        //
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Employer Interview Declinedd Mail',
        );
    }
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.employer.declined-interview',
            with: [
                'suggestion' => $this->suggestion,
            ],
        );
    }
    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
