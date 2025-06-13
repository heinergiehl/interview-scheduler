<?php

namespace App\Models;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable = ['name', 'email', 'password', 'is_applicant'];
    protected $hidden   = ['password', 'remember_token'];
    protected $casts    = ['email_verified_at' => 'datetime', 'is_applicant' => 'boolean'];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function jobAppointmentSuggestions()
    {
        return $this->hasMany(InterviewAppointmentSuggestion::class, 'employer_id');
    }
    public function isApplicant(): bool
    {
        return $this->is_applicant;
    }
    public function isEmployer(): bool
    {
        return !$this->is_applicant;
    }
}
