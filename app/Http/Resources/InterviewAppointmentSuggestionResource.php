<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class InterviewAppointmentSuggestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'user'         => [
                'id'   => $this->user->id,
                'name' => $this->user->name,
            ],
            // you can format dates here, or even cast in the model
            'suggested_date_time'    => $this->suggested_date_time,
            'appointment_status'       => $this->appointment_status,
            'responded_at' => $this->responded_at,
            'candidate' => [
                'id'   => $this->candidate->id,
                'name' => $this->candidate->name,
            ],
            'employer' => [
                'id'   => $this->employer->id,
                'name' => $this->employer->name,
            ],
            // policy-based flags:
            'canUpdate'    => Gate::allows('update', $this->resource),
            'canDelete'    => Gate::allows('delete', $this->resource),
            'canAccept'    => Gate::allows('accept', $this->resource),
            'canDecline'    => Gate::allows('decline', $this->resource),
        ];
    }
}
