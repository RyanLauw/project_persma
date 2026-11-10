<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterviewSchedule extends Model
{
    protected $fillable = [
        'department_id', 
        'start_time', 
        'end_time', 
        'tipe', 
        'lokasi', 
        'pewawancara'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // Relasi ke Departemen (jadwal ini milik departemen mana)
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Relasi balik untuk melihat siapa saja yang memilih jadwal ini
    public function applicants()
    {
        return $this->belongsToMany(User::class, 'applicant_interview', 'interview_schedule_id', 'user_id')
                    ->withPivot('for_choice_order')
                    ->withTimestamps();
    }
}