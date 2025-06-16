<?php

namespace App\Models;

use App\Events\InterviewAppointmentAccepted;
use App\Events\InterviewAppointmentDeclined;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\User;

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
    public function user()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }
    /* called by applicant (the candidate, me :)) */
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
