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
        logger()->info('SendInterviewConfirmedEmails!!!', [
            '$s->candidate->email' => $s->candidate->email,
            '$s->employer->email' => $s->employer->email,
        ]);
        Mail::to($s->candidate->email)
            ->queue(new CandidateInterviewConfirmedMail($s));
        // to the employer
        Mail::to($s->employer->email)
            ->queue(new EmployerInterviewConfirmedMail($s));
    }
}
