<?php

namespace App\Events;

use App\Http\Resources\InterviewAppointmentSuggestionResource;
use App\Models\InterviewAppointmentSuggestion;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;

class InterviewAppointmentConfirmed implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;
    public function __construct(
        public InterviewAppointmentSuggestion $suggestion
    ) {}
    public function broadcastOn(): array
    {
        // employer listens on their own private channel
        return [
            new PrivateChannel('employer.' . $this->suggestion->candidate_id),
        ];
    }
    public function broadcastWith(): array
    {
        return [
            'suggestion' => InterviewAppointmentSuggestionResource::make($this->suggestion),
        ];
    }
}
