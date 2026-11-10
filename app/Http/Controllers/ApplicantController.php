<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicantController extends Controller
{
    public function index()
    {
        $admin = Auth::user();
        
        // 1. Cari tahu batch mana yang sedang aktif
        $activeBatch = \App\Models\Batch::where('is_active', true)->first();

        // 2. Buat filter khusus: Hanya ambil pelamar yang batch_id-nya sama dengan batch aktif
        $applicantFilter = function ($query) use ($activeBatch) {
            $query->where('role', 'applicant');
            
            if ($activeBatch) {
                $query->whereHas('profile', function($q) use ($activeBatch) {
                    $q->where('batch_id', $activeBatch->id);
                });
            } else {
                // Jika tidak ada batch yang aktif, buat kondisi mustahil agar daftar kosong
                $query->where('id', '<', 0); 
            }
            
            $query->with('profile');
        };

        if ($admin->role === 'bph') {
            // BPH melihat semua departemen beserta pelamarnya (sesuai batch aktif)
            $departments = \App\Models\Department::with(['applicants' => $applicantFilter])->get();
        } elseif ($admin->role === 'kwk') {
            // KWK melihat departemennya sendiri beserta pelamarnya (sesuai batch aktif)
            $departments = \App\Models\Department::where('id', $admin->department_id)
                ->with(['applicants' => $applicantFilter])->get();
        } else {
            abort(403);
        }

        // Kirimkan juga variabel $activeBatch ke blade agar bisa ditampilkan infonya
        return view('admin.applicants.index', compact('departments', 'activeBatch'));
    }
    // Menampilkan halaman detail pelamar
    public function show($id)
    {
        $admin = Auth::user();
        
        $applicant = User::where('role', 'applicant')
                    ->with(['profile', 'appliedDepartments'])
                    ->findOrFail($id);

        // Keamanan ekstra: Jika KWK, pastikan pelamar ini melamar ke departemennya
        if ($admin->role === 'kwk') {
            $melamarKeSini = $applicant->appliedDepartments->contains('id', $admin->department_id);
            if (!$melamarKeSini) {
                abort(403, 'Akses ditolak. Pelamar ini tidak mendaftar ke departemen Anda.');
            }
        }

        return view('admin.applicants.show', compact('applicant', 'admin'));
    }

    // Menyimpan Notes Wawancara (Bisa dilakukan KWK dan BPH)
    public function updateNotes(Request $request, $applicant_id, $department_id)
    {
        $request->validate(['interview_notes' => 'nullable|string']);
        
        $applicant = User::findOrFail($applicant_id);
        
        // Update data di tabel pivot applicant_department
        $applicant->appliedDepartments()->updateExistingPivot($department_id, [
            'interview_notes' => $request->interview_notes
        ]);

        return redirect()->back()->with('success', 'Catatan wawancara berhasil disimpan!');
    }

    // Mengubah Status Kelulusan (Hanya BPH)
    public function updateStatus(Request $request, $applicant_id, $department_id)
    {
        // Pastikan hanya BPH yang bisa menembak endpoint ini
        if (Auth::user()->role !== 'bph') {
            abort(403);
        }

        $request->validate(['status' => 'required|in:pending,accepted,rejected']);
        
        $applicant = User::findOrFail($applicant_id);
        
        // 1. Update status divisi yang sedang diklik
        $applicant->appliedDepartments()->updateExistingPivot($department_id, [
            'status' => $request->status
        ]);

        // 2. LAKUKAN AUTO-REJECT JIKA STATUSNYA DITERIMA
        if ($request->status === 'accepted') {
            // Cari ID divisi lain yang dipilih oleh pelamar ini
            $otherDeptIds = $applicant->appliedDepartments->where('id', '!=', $department_id)->pluck('id');
            
            // Ubah status divisi lain menjadi 'rejected'
            foreach ($otherDeptIds as $otherId) {
                $applicant->appliedDepartments()->updateExistingPivot($otherId, [
                    'status' => 'rejected'
                ]);
            }
        }

        return redirect()->back()->with('success', 'Status kelulusan berhasil diperbarui!');
    }

    // Membuka file rahasia dari storage/app/ (termasuk kompatibilitas dengan file lama)
    public function viewDocument($path)
    {
        // 1. Cek di lokasi baru (Brankas Tertutup)
        $privatePath = storage_path('app/' . $path);
        
        // 2. Cek di lokasi lama (Folder Publik) -> Untuk file yang di-upload sebelum kode diubah
        $publicPath = storage_path('app/public/' . $path);
        
        if (file_exists($privatePath)) {
            return response()->file($privatePath);
        } elseif (file_exists($publicPath)) {
            return response()->file($publicPath);
        } else {
            abort(404, 'Dokumen tidak ditemukan atau file aslinya sudah terhapus dari server.');
        }
    }
}
