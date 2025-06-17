<?php

namespace App\Listeners;

use App\Events\InterviewAppointmentConfirmed;
use App\Mail\EmployerInterviewDeclinedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

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
            ->queue(new EmployerInterviewDeclinedMail($s));
    }
}
