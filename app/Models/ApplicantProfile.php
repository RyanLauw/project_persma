<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantProfile extends Model
{
    protected $fillable = [
        'user_id', 'batch_id', 'nama_lengkap', 'nrp', 'program_studi', 
        'tempat_lahir', 'tanggal_lahir', 'no_telepon', 'alamat_domisili', 
        'agama', 'instagram', 'id_line', 'progress_step', 'jadwal_hidup_path',
        'ipk', 'ktm_path', 'pas_foto_path', 'transkrip_path', 'skkk_path' // <--- TAMBAHAN BARU
    ];

    // Relasi ke tabel Users
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}