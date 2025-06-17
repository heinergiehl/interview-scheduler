<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Events\InterviewAppointmentConfirmed;
use App\Listeners\SendInterviewConfirmedEmails;
use App\Mail\CandidateInterviewConfirmedMail;
use App\Mail\EmployerInterviewConfirmedMail;
use App\Models\InterviewAppointmentSuggestion;
use App\Models\User;

class SendInterviewConfirmedEmailsTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function test_queues_two_emails_to_candidate_and_employer(): void
    {
        Mail::fake();
        $employer  = User::factory()->create();
        $candidate = User::factory()->create();
        $interviewSlot      = InterviewAppointmentSuggestion::factory()
            ->for($employer, 'employer') // create a confirmed interview slot with employer by setting employer_id to $employer->id	
            ->for($candidate, 'candidate') // create a confirmed interview slot with candidate by setting candidate_id to $candidate->id
            ->create();
        // use the listener directly
        (new SendInterviewConfirmedEmails())->handle(
            new InterviewAppointmentConfirmed($interviewSlot)
        );
        // assert that the candidate email was queued
        Mail::assertQueued(CandidateInterviewConfirmedMail::class, function ($mail) use ($interviewSlot, $candidate) {
            return $mail->hasTo($candidate->email)
                && $mail->suggestion->is($interviewSlot);
        });
        // assert that the employer email was queued
        Mail::assertQueued(EmployerInterviewConfirmedMail::class, function ($mail) use ($interviewSlot, $employer) {
            return $mail->hasTo($employer->email)
                && $mail->suggestion->is($interviewSlot);
        });
    }
}
