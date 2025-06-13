<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkDestroyAppointmentSuggestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()                   // Authenticated user
            ->can(
                'bulkDelete',                // ability name
                InterviewAppointmentSuggestion::class
            ); // target
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ids'   => ['required', 'array', 'min:1'],
            'ids.*' => [
                Rule::exists('interview_appointment_suggestions', 'id')
                    ->where('employer_id', $this->user()->id), // 👈 row ownership
            ],
        ];
    }
}
