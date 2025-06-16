<?php

namespace App\Events;

use App\Http\Resources\InterviewAppointmentSuggestionResource;
use App\Models\InterviewAppointmentSuggestion;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InterviewAppointmentDeclined implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public function __construct(
        public InterviewAppointmentSuggestion $suggestion
    ) {}
    public function broadcastOn(): array
    {
        // employer listens on their own private channel
        return [
            new PrivateChannel('employer.' . $this->suggestion->employer_id),
        ];
    }
    public function broadcastWith(): array
    {
        return [
            'suggestion' => InterviewAppointmentSuggestionResource::make($this->suggestion),
        ];
    }
}
