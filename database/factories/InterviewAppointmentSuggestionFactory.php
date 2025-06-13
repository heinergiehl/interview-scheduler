<?php

namespace Database\Factories;

use App\Models\InterviewAppointmentSuggestion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InterviewAppointmentSuggestionFactory extends Factory
{
    protected $model = InterviewAppointmentSuggestion::class;
    public function definition()
    {
        return [
            'employer_id'         => User::factory(),
            'candidate_id'        => User::factory(),
            'suggested_date_time' => $this->faker->dateTimeBetween('+1 day', '+1 month'),
            'appointment_status'  => 'pending',
            'responded_at'        => null,
        ];
    }
}
