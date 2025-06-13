<?php

namespace Tests\Feature;

use App\Events\InterviewAppointmentConfirmed;
use App\Listeners\SendInterviewConfirmedEmails;
use App\Mail\CandidateInterviewConfirmedMail;
use App\Mail\EmployerInterviewConfirmedMail;
use App\Models\InterviewAppointmentSuggestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InterviewConfirmedTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function listener_queues_two_emails_when_event_is_fired()
    {
        Mail::fake();
        // set up an employer and a candidate
        $employer  = User::factory()->create(['email' => 'heiner.giehl@yahoo.com']);
        $candidate = User::factory()->create(['email' => 'cand@example.com']);
        // create a suggestion linking them
        $suggestion = InterviewAppointmentSuggestion::factory()->create([
            'employer_id'           => $employer->id,
            'candidate_id'          => $candidate->id,
            'appointment_status'    => 'pending',
            'suggested_date_time'   => now()->addDay(),
        ]);
        logger()->info('Created suggestion', [
            'suggestion_id' => $suggestion->id,
            'employer_id'   => $employer->id,
            'candidate_id'  => $candidate->id,
        ]);
        // dispatch the event
        event(new InterviewAppointmentConfirmed($suggestion));
        // run the listener manually (or let Laravel queue it)
        (new SendInterviewConfirmedEmails())->handle(
            new InterviewAppointmentConfirmed($suggestion)
        );
        // assert the candidate mail was queued
        Mail::assertQueued(CandidateInterviewConfirmedMail::class, function ($mail) use ($suggestion, $candidate) {
            return $mail->hasTo($candidate->email)
                && $mail->suggestion->is($suggestion);
        });
        // assert the employer mail was queued
        Mail::assertQueued(EmployerInterviewConfirmedMail::class, function ($mail) use ($suggestion, $employer) {
            return $mail->hasTo($employer->email)
                && $mail->suggestion->is($suggestion);
        });
    }
    /** @test */
    public function update_route_dispatches_event_when_status_changes_to_confirmed()
    {
        Event::fake();
        // act as the employer user
        $user = User::factory()->create();
        $this->actingAs($user);
        // create a pending suggestion
        $suggestion = InterviewAppointmentSuggestion::factory()->create([
            'employer_id'        => $user->id,
            'appointment_status' => 'pending',
        ]);
        // hit the update endpoint, flipping to 'confirmed'
        $response = $this->put(
            route('employer.suggestions.update', $suggestion),
            [
                'suggested_at'       => $suggestion->suggested_date_time->format('Y-m-d H:i:s'),
                'appointment_status' => 'confirmed',
            ]
        );
        $response->assertRedirect();
        $response->assertSessionHas('success');
        // the event should have been dispatched
        Event::assertDispatched(InterviewAppointmentConfirmed::class, function ($e) use ($suggestion) {
            return $e->suggestion->id === $suggestion->id;
        });
    }
}
