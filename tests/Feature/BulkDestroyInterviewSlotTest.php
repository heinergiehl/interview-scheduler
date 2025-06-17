<?php

namespace Tests\Feature;

use App\Models\InterviewAppointmentSuggestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BulkDestroyInterviewSlotTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function test_employer_can_bulk_delete_their_slots_only(): void
    {
        $employer    = User::factory()->create(['is_applicant' => false]);
        $candidate  = User::factory()->create(['is_applicant' => true]);
        $mySlots = InterviewAppointmentSuggestion::factory()->count(3)->create([
            'employer_id' => $employer->id,
            'candidate_id' => $candidate->id,
        ]);
        $someoneElsesSlot = InterviewAppointmentSuggestion::factory()->create([
            'employer_id' => User::factory()->create(['is_applicant' => false])->id,
            'candidate_id' => User::factory()->create(['is_applicant' => true])->id,
        ]);
        $payload = [
            'ids' => $mySlots->pluck('id')->toArray(),
        ];
        $this->actingAs($employer)->delete(
            route('employer.suggestions.bulkDestroy'),
            $payload
        )->assertRedirect()->assertSessionHas('success');
        $this->assertDatabaseMissing('interview_appointment_suggestions', [
            'id' => $mySlots->first()->id,
        ]);
        $this->assertDatabaseMissing('interview_appointment_suggestions', [
            'id' => $mySlots->last()->id,
        ]);
        $this->assertDatabaseHas('interview_appointment_suggestions', [
            'id' => $someoneElsesSlot->id,
        ]);
        $this->assertModelExists($someoneElsesSlot->fresh());
    }
}
