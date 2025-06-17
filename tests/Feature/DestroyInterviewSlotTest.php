<?php

namespace Tests\Feature;

namespace Tests\Feature\Employer;

use App\Models\InterviewAppointmentSuggestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestroyInterviewSlotTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function test_employer_can_delete_own_slot(): void
    {
        $employer  = User::factory()->create(['is_applicant' => false]);
        $candidate = User::factory()->create([
            'is_applicant' => true,
        ]);
        $interviewSlot = InterviewAppointmentSuggestion::factory()->create([
            'employer_id' => $employer->id,
            'candidate_id' => $candidate->id,
        ]);
        $response = $this->actingAs($employer)->delete(
            route('employer.suggestions.destroy', $interviewSlot)
        );
        $response->assertRedirect(route('employer.home'));
        $this->assertDatabaseMissing('interview_appointment_suggestions', [
            'id' => $interviewSlot->id,
        ]);
    }
    /** @test */
    public function test_employer_cannot_delete_others_slot(): void
    {
        $employer  = User::factory()->create(['is_applicant' => false]);
        $candidate = User::factory()->create([
            'is_applicant' => true,
        ]);
        $interviewSlot = InterviewAppointmentSuggestion::factory()->create([
            'employer_id' => $employer->id,
            'candidate_id' => $candidate->id,
        ]);
        $otherEmployer = User::factory()->create(['is_applicant' => false]);
        $response = $this->actingAs($otherEmployer)->delete(
            route('employer.suggestions.destroy', $interviewSlot)
        );
        $response->assertForbidden();
        $this->assertDatabaseHas('interview_appointment_suggestions', [
            'id' => $interviewSlot->id,
        ]);
    }
}
