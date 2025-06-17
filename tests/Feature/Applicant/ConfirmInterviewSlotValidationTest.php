<?php

namespace Tests\Feature\Employer;

use App\Events\InterviewAppointmentConfirmed;
use App\Models\InterviewAppointmentSuggestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ConfirmInterviewSlotValidationTest extends TestCase
{
    use RefreshDatabase;
    public function test_applicant_cannot_confirm_interview_slot()
    {
        Event::fake([InterviewAppointmentConfirmed::class]);
        $applicant = User::factory()->create(['is_applicant' => true]);
        $slot = InterviewAppointmentSuggestion::factory()
            ->create([
                'appointment_status' => 'draft',
                'employer_id'        => $applicant->id,
                'candidate_id'       => User::factory()->create(['is_applicant' => true])->id,
            ]);
        $response = $this->actingAs($applicant)->put(
            route('employer.suggestions.update', $slot),
            [
                'suggested_at'       => $slot->suggested_date_time->format('Y-m-d H:i:s'),
                'appointment_status' => 'confirmed',
            ],
        );
        $response->assertStatus(403);
        Event::assertNotDispatched(InterviewAppointmentConfirmed::class);
    }
}
