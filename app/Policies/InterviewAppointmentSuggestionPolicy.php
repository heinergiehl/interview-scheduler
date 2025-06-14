<?php

namespace App\Policies;

use App\Models\InterviewAppointmentSuggestion;
use App\Models\User;

class InterviewAppointmentSuggestionPolicy
{
    public function update(User $user, InterviewAppointmentSuggestion $suggestion): bool
    {
        // return $suggestion->employer_id === $user->id;
        // the employer can update the suggestion as he wants, but the applicant can only update the status from confirmed to accepted
        return $user->isEmployer() || ($user->isApplicant() && $suggestion->appointment_status === 'confirmed');
    }
    public function delete(User $user, InterviewAppointmentSuggestion $suggestion): bool
    {
        // the employer can delete the suggestion, but the applicant can't
        return $user->isEmployer();
    }
    public function bulkDelete(User $user): bool
    {
        return $user->isEmployer();   // adapt to your own role‐check helper
    }
}
