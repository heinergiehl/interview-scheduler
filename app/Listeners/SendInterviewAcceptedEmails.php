<?php
// app/Listeners/SendInterviewAcceptedEmails.php
namespace App\Listeners;

use App\Events\InterviewAppointmentAccepted;
use App\Mail\CandidateInterviewAcceptedMail;
use App\Mail\EmployerInterviewAcceptedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendInterviewAcceptedEmails implements ShouldQueue
{
    public function handle(InterviewAppointmentAccepted $event): void
    {
        $s = $event->suggestion;
        // to the candidate
        Mail::to($s->candidate->email)
            ->queue(new CandidateInterviewAcceptedMail($s));
        // to the employer
        Mail::to($s->employer->email)
            ->queue(new EmployerInterviewAcceptedMail($s));
    }
}
