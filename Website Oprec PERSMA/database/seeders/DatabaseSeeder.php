<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\InterviewSchedule;
use App\Models\User; // <-- Jangan lupa tambahkan ini
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. DATA DEPARTEMEN PERSMA
        $departments = [
            ['name' => 'BPH', 'requires_portfolio' => false],
            ['name' => 'FOTOGRAFI', 'requires_portfolio' => true],
            ['name' => 'REPORTASE DARING', 'requires_portfolio' => true],
            ['name' => 'KONTEN KREATIF', 'requires_portfolio' => true],
            ['name' => 'RISET & PENGEMBANGAN', 'requires_portfolio' => false],
            ['name' => 'TEKNOLOGI INFORMASI', 'requires_portfolio' => true],
            ['name' => 'KEWIRAUSAHAAN', 'requires_portfolio' => false],
            ['name' => 'REPORTASE CETAK', 'requires_portfolio' => true],
            ['name' => 'ILUSTRASI & TATA LETAK', 'requires_portfolio' => true],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }

        // 2. BUAT AKUN BPH (SUPER ADMIN)
        User::create([
            'name' => 'Admin BPH',
            'email' => 'bph@persma.petra.ac.id',
            'password' => Hash::make('password'), // Password default: password
            'role' => 'bph',
            'department_id' => 1, // Mengikat ke divisi BPH
        ]);

        // 3. BUAT AKUN KWK & JADWAL UNTUK SETIAP DEPARTEMEN
        $allDepartments = Department::all();
        
        foreach ($allDepartments as $dept) {
            // Kita tidak perlu membuat KWK untuk BPH karena sudah dibuat di atas
            if ($dept->name !== 'BPH') {
                
                // Format email dinamis (contoh: kwk_fotografi@persma.petra.ac.id)
                $emailPrefix = strtolower(str_replace([' ', '&'], ['_', 'dan'], $dept->name));
                
                User::create([
                    'name' => 'KWK ' . $dept->name,
                    'email' => 'kwk_' . $emailPrefix . '@persma.petra.ac.id',
                    'password' => Hash::make('password'), // Password default: password
                    'role' => 'kwk',
                    'department_id' => $dept->id,
                ]);
            }

            // Sekalian buatkan 1 jadwal wawancara dummy per departemen
            InterviewSchedule::create([
                'department_id' => $dept->id,
                'start_time' => '2026-06-01 10:00:00',
                'end_time' => '2026-06-01 10:30:00', // Dibuat per 30 menit
                'tipe' => 'offline',
                'lokasi' => 'Ruang Persma, Gedung T',
                'pewawancara' => 'Ketua ' . $dept->name,
            ]);
        }
    }
}