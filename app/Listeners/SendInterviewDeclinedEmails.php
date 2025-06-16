<?php

namespace App\Listeners;

use App\Events\InterviewAppointmentConfirmed;
use App\Mail\EmployerInterviewDeclineddMail;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendInterviewDeclinedEmails implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }
    /**
     * Handle the event.
     */
    public function handle(InterviewAppointmentConfirmed $event)
    {
        $s = $event->suggestion;
        // just out of lazyness, left it for the candidate :)
        Mail::to($s->employer->email)
            ->queue(new EmployerInterviewDeclineddMail($s));
    }
}
