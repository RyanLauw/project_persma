<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'role',
        'department_id', // <-- Jangan lupa tambahkan ini agar role KWK bisa disimpan
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];  

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // 1. Relasi One-to-One ke data pribadi (CUKUP SATU KALI SAJA)
    public function profile()
    {
        return $this->hasOne(ApplicantProfile::class, 'user_id', 'id');
    }

    // 2. Relasi untuk role KWK (Milik departemen mana)
    public function kwkDepartment()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    // 3. Relasi Many-to-Many ke Departemen (Menyimpan pilihan 1 & 2 serta portofolio)
    public function departments()
    {
        return $this->belongsToMany(Department::class, 'applicant_department')
                    ->withPivot('choice_order', 'portfolio_path')
                    ->withTimestamps()
                    ->orderBy('pivot_choice_order', 'asc');
    }

    // 4. Relasi Many-to-Many ke Departemen Khusus untuk Admin (Membawa notes dan status BPH)
    public function appliedDepartments()
    {
        return $this->belongsToMany(Department::class, 'applicant_department')
                    ->withPivot('interview_notes', 'status', 'choice_order', 'portfolio_path')
                    ->withTimestamps()
                    // UBAH BARIS INI: Gunakan orderByPivot, dan buang kata 'pivot_'
                    ->orderByPivot('choice_order', 'asc'); 
    }

    // 5. Relasi Many-to-Many ke Jadwal Wawancara
    public function interviews()
    {
        return $this->belongsToMany(InterviewSchedule::class, 'applicant_interview')
                    ->withPivot('for_choice_order')
                    ->withTimestamps();
    }
}
