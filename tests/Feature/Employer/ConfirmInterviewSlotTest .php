<?php

namespace Tests\Feature;

use App\Events\InterviewAppointmentConfirmed;
use App\Models\InterviewAppointmentSuggestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ConfirmInterviewSlotTest  extends TestCase
{
    use RefreshDatabase;
    /** @test
     * just test whether the event is dispatched after the employer confirms an interview suggestion
     * This test does not check the email sending functionality, as that is covered in the SendInterviewConfirmedEmailsTest unit test.
     */
    public function test_employer_confirming_interview_suggestion_dispatches_event(): void
    {
        Event::fake([InterviewAppointmentConfirmed::class]);
        $employer = User::factory()->create([
            'is_applicant' => false,
        ]);
        $candidate = User::factory()->create(
            [
                'is_applicant' => true,
            ]
        );
        $interviewSlot = InterviewAppointmentSuggestion::factory()
            ->create([
                'appointment_status' => 'draft',
                'employer_id'        => $employer->id,
                'candidate_id'       => $candidate->id,
            ]);
        /** @var \App\Models\User $employer */
        $this->actingAs($employer)->put(
            route('employer.suggestions.update', $interviewSlot),     // 2) Payload mitsenden
            [
                'suggested_at'       => $interviewSlot->suggested_date_time->format('Y-m-d H:i:s'),
                'appointment_status' => 'confirmed',
            ],
        )->assertRedirect();
        Event::assertDispatched(
            InterviewAppointmentConfirmed::class,
            fn($e) => $e->suggestion->is($interviewSlot->fresh())
        );
    }
}
