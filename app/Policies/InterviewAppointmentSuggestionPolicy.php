<?php

namespace App\Policies;

use App\Models\InterviewAppointmentSuggestion;
use App\Models\User;

class InterviewAppointmentSuggestionPolicy
{
    public function update(User $user, InterviewAppointmentSuggestion $suggestion): bool
    {
        return $user->isEmployer() && $suggestion->appointment_status === 'draft' || ($user->isApplicant() && $suggestion->appointment_status === 'confirmed');
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
    public function accept(User $user, InterviewAppointmentSuggestion $suggestion): bool
    {
        // the applicant can accept the suggestion
        return $user->isApplicant() && $suggestion->appointment_status === 'confirmed';
    }
    public function decline(User $user, InterviewAppointmentSuggestion $suggestion): bool
    {
        // the applicant can decline the suggestion
        return $user->isApplicant() && $suggestion->appointment_status === 'confirmed';
    }
}
