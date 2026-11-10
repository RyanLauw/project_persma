<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'contact_name',
        'contact_wa',
        'requires_portfolio',
    ];

    protected $casts = [
        'requires_portfolio' => 'boolean',
    ];

    // Relasi untuk menarik data pelamar yang melamar ke departemen ini
    public function applicants()
    {
        return $this->belongsToMany(User::class, 'applicant_department', 'department_id', 'user_id')
                    ->withPivot('choice_order', 'interview_notes', 'status', 'portfolio_path')
                    ->withTimestamps()
                    ->orderBy('pivot_choice_order', 'asc');
    }

    // Relasi untuk mengecek jadwal wawancara
    public function interviewSchedules()
    {
        return $this->hasMany(InterviewSchedule::class);
    }
    
    public function kwks()
    {
        return $this->hasMany(User::class)->where('role', 'kwk');
    }
}