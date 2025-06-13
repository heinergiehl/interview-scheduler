<?php

namespace App\Http\Requests;

use App\Models\InterviewAppointmentSuggestion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInterviewSuggestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Make sure only employers can hit this
        return $this->user()->can('employer');
    }
    public function rules(): array
    {
        // single unique rule that only considers DRAFT/CONFIRMED as duplicates
        $uniqueForYou = Rule::unique(
            (new InterviewAppointmentSuggestion)->getTable(),
            'suggested_date_time'
        )
            ->where(
                fn($q) => $q
                    ->where('employer_id', $this->user()->id)
                    ->whereIn('appointment_status', ['draft', 'confirmed'])
            );
        return [
            'suggested_at'      => [
                'required',
                'date',
                'after:now',
                $uniqueForYou,
            ],
            'candidate_id'      => ['exists:users,id'],
            'appointment_status' => ['sometimes', 'in:draft,confirmed'],
        ];
    }
    public function messages(): array
    {
        return [
            'suggested_at.unique'      => 'You already proposed that exact slot.',
            'suggested_at.after'       => 'Choose a date/time in the future.',
            'candidate_id.exists'      => 'Selected candidate does not exist.',
            'appointment_status.in'    => 'Invalid status selected.',
        ];
    }
    public function withValidator($validator)
    {
        // After the other rules, check if applicant already accepted this slot
        $validator->after(function ($v) {
            $dt = $this->input('suggested_at');
            if (! $v->errors()->has('suggested_at')) {
                $exists = InterviewAppointmentSuggestion::query()
                    ->where('employer_id', $this->user()->id)
                    ->where('suggested_date_time', $dt)
                    ->where('appointment_status', 'accepted')
                    ->exists();
                if ($exists) {
                    $v->errors()->add(
                        'suggested_at',
                        'That slot was already accepted by the applicant'
                    );
                }
            }
        });
    }
}
