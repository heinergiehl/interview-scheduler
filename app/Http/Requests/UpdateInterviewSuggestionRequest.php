<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInterviewSuggestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('suggestion'));
    }
    public function rules(): array
    {
        $suggestion = $this->route('suggestion');
        return [
            'suggested_at' => [
                'required',
                'date',
                'after:now',
                Rule::unique('interview_appointment_suggestions', 'suggested_date_time')
                    ->ignore($suggestion->id)
                    ->where(fn($q) => $q->where('employer_id', $this->user()->id)),
            ],
            'appointment_status' => 'required|in:draft,confirmed',
        ];
    }
}
