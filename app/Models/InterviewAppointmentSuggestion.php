<?php

namespace App\Models;

use App\Events\InterviewAppointmentAccepted;
use App\Events\InterviewAppointmentConfirmed;
use App\Events\InterviewAppointmentDeclined;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Carbon;

class InterviewAppointmentSuggestion extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $fillable = ['employer_id', 'suggested_date_time', 'appointment_status', 'responded_at', 'candidate_id'];
    protected $casts = [
        'suggested_datime_time' => 'datetime',
        'responded_at' => 'datetime',
    ];
    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }
    public function candidate()
    {
        return $this->belongsTo(User::class, 'candidate_id');
    }
    public function confirm(Carbon $newDate): void
    {
        DB::transaction(function () use ($newDate) {
            $this->update([
                'suggested_date_time' => $newDate,
                'appointment_status'  => 'confirmed',
            ]);
            $this->load(['candidate', 'employer']);
            InterviewAppointmentConfirmed::dispatch($this);
        });
    }
    public function accept(): self
    {
        DB::transaction(function () {
            $this->update([
                'appointment_status' => 'accepted',
                'responded_at' => now(),
            ]);
            InterviewAppointmentAccepted::dispatch($this);
        });
        return $this->fresh();
    }
    public function decline(): self
    {
        DB::transaction(function () {
            $this->update([
                'appointment_status' => 'declined',
                'responded_at' => now(),
            ]);
            InterviewAppointmentDeclined::dispatch($this);
        });
        return $this->fresh();
    }
}
