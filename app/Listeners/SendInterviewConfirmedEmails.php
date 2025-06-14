<?php
// app/Listeners/SendInterviewConfirmedEmails.php
namespace App\Listeners;

use App\Events\InterviewAppointmentConfirmed;
use App\Mail\CandidateInterviewConfirmedMail;
use App\Mail\EmployerInterviewConfirmedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendInterviewConfirmedEmails implements ShouldQueue
{
    public function handle(InterviewAppointmentConfirmed $event)
    {
        $s = $event->suggestion;
        Mail::to($s->candidate->email)
            ->queue(new CandidateInterviewConfirmedMail($s));
        // to the employer
        Mail::to($s->employer->email)
            ->queue(new EmployerInterviewConfirmedMail($s));
    }
}
