<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use App\Models\Batch;
use Illuminate\Http\Request;


class BatchController extends Controller
{
    // Menampilkan halaman daftar batch
    public function index()
    {
        $batches = \App\Models\Batch::orderBy('created_at', 'desc')->get();
        // Tarik data departemen (kecuali BPH) untuk ditampilkan checkbox portofolio
        $departments = \App\Models\Department::where('name', '!=', 'BPH')->get();
        
        return view('admin.batches.index', compact('batches', 'departments'));
    }

    // Menyimpan batch baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama_batch' => 'required|string|max:255',
            'tipe_batch' => 'required|in:Fungsio,Intern 1,Intern 2',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'portfolio_departments' => 'nullable|array', // Validasi array checkbox divisi
        ]);

        // 1. Buat Batch Baru
        \App\Models\Batch::create([
            'nama_batch' => $request->nama_batch,
            'tipe_batch' => $request->tipe_batch,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'is_active' => false,
            'requires_cv' => $request->has('requires_cv'),
        ]);

        // 2. Update status Wajib Portofolio untuk Divisi
        // Pertama, matikan (false) semua status portofolio departemen (reset)
        \App\Models\Department::where('name', '!=', 'BPH')->update(['requires_portfolio' => false]);
        
        // Kedua, nyalakan (true) hanya untuk departemen yang dicentang
        if ($request->has('portfolio_departments')) {
            \App\Models\Department::whereIn('id', $request->portfolio_departments)
                                  ->update(['requires_portfolio' => true]);
        }

        return redirect()->back()->with('success', 'Event baru berhasil dibuat & pengaturan syarat portofolio divisi telah diperbarui!');
    }

    public function update(Request $request, Batch $batch)
    {
        $request->validate([
            'nama_batch' => 'required|string|max:255',
            'tipe_batch' => 'required|in:Fungsio,Intern 1,Intern 2',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        // 1. Update data Batch utama
        $batch->update([
            'nama_batch' => $request->nama_batch,
            'tipe_batch' => $request->tipe_batch,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'requires_cv' => $request->has('requires_cv'), 
        ]);

        // 2. Update Syarat Portofolio Departemen Secara Global
        // Reset semuanya (kecuali BPH) jadi false dulu
        \App\Models\Department::where('name', '!=', 'BPH')->update(['requires_portfolio' => false]);
        
        // Jika ada checkbox yang dicentang, ubah status divisi tersebut menjadi true
        if ($request->has('portfolio_departments')) {
            \App\Models\Department::whereIn('id', $request->portfolio_departments)
                                  ->update(['requires_portfolio' => true]);
        }

        return redirect()->back()->with('success', 'Informasi Event dan Persyaratan berhasil diperbarui!');
    }

    // Mengubah status Aktif / Tutup
    public function toggleStatus(Batch $batch)
    {
        // Jika batch ini akan diaktifkan, kita matikan dulu semua batch lain
        // agar tidak ada dua gelombang pendaftaran yang buka bersamaan
        if (!$batch->is_active) {
            Batch::where('is_active', true)->update(['is_active' => false]);
        }

        $batch->update([
            'is_active' => !$batch->is_active
        ]);

        $status = $batch->is_active ? 'diaktifkan' : 'ditutup';
        return redirect()->back()->with('success', "Batch {$batch->nama_batch} berhasil {$status}!");
    }



    // PUNYA JAHE
    public function sendApiToMainWeb($id)
    {
        $batch = \App\Models\Batch::findOrFail($id);

        // 1. Ambil semua user yang mendaftar di Batch ini beserta relasinya
        $usersInBatch = \App\Models\User::whereHas('profile', function($q) use ($batch) {
            $q->where('batch_id', $batch->id);
        })->with(['profile', 'appliedDepartments'])->get();

        $payloadData = [];

        // 2. Susun seluruh komponen data profil pendaftar yang Diterima
        foreach ($usersInBatch as $user) {
            $acceptedDept = $user->appliedDepartments->firstWhere('pivot.status', 'accepted');

            if ($acceptedDept && $user->profile) {
                $payloadData[] = [
                    // Data Kelulusan & Jabatan (Tipe Batch)
                    'nama_lengkap'      => $user->profile->nama_lengkap,
                    'nrp'               => $user->profile->nrp,
                    'program_studi'     => $user->profile->program_studi,
                    'divisi'            => $acceptedDept->name,
                    'jabatan'           => $batch->tipe_batch, // Menghasilkan: Fungsio / Intern 1 / Intern 2
                    
                    // Data Pribadi Lengkap
                    'tempat_lahir'      => $user->profile->tempat_lahir,
                    'tanggal_lahir'     => $user->profile->tanggal_lahir,
                    'no_telepon'        => $user->profile->no_telepon,
                    'alamat_domisili'   => $user->profile->alamat_domisili,
                    'agama'             => $user->profile->agama,
                    'instagram'         => $user->profile->instagram,
                    'id_line'           => $user->profile->id_line,
                    'ipk'               => $user->profile->ipk,
                    
                    // Berkas Dokumen Fisik (Diubah menjadi Link URL agar Website Utama bisa langsung download)
                    'jadwal_hidup_url'  => $user->profile->jadwal_hidup_path ? url('storage/' . $user->profile->jadwal_hidup_path) : null,
                    'ktm_url'           => $user->profile->ktm_path ? url('storage/' . $user->profile->ktm_path) : null,
                    'pas_foto_url'      => $user->profile->pas_foto_path ? url('storage/' . $user->profile->pas_foto_path) : null,
                    'transkrip_url'     => $user->profile->transkrip_path ? url('storage/' . $user->profile->transkrip_path) : null,
                    'skkk_url'          => $user->profile->skkk_path ? url('storage/' . $user->profile->skkk_path) : null,
                    
                    // Tautan Portofolio Drive pendaftar
                    'portfolio_url'     => $acceptedDept->pivot->portfolio_path,
                ];
            }
        }

        // Jika tidak ada pelamar yang diterima pada batch ini
        if (empty($payloadData)) {
            return redirect()->back()->with('error', 'Gagal! Tidak ada pelamar yang berstatus DITERIMA pada Batch ini.');
        }

        // 3. Struktur JSON Bungkus Utama
        $jsonPayload = [
            'batch_name'     => $batch->nama_batch,
            'total_accepted' => count($payloadData),
            'timestamp'      => now()->toIso8601String(),
            'data'           => $payloadData
        ];

        // URL Webhook Destinasi Utama
        $dummyUrl = 'https://webhook.site/07be1fb1-f27c-4b30-9e5d-4f6add5b7b75'; 
        
        try {
            // Mengirim data dengan mem-bypass verifikasi SSL lokal komputer
            $response = \Illuminate\Support\Facades\Http::withoutVerifying()->post($dummyUrl, $jsonPayload);

            if ($response->successful()) {
                return redirect()->back()->with('success', 'Data API Lengkap berhasil dikirim ke Website Utama!');
            } else {
                return redirect()->back()->with('error', 'Gagal mengirim API. Status: ' . $response->status());
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Koneksi ke server tujuan terputus: ' . $e->getMessage());
        }
    }
}