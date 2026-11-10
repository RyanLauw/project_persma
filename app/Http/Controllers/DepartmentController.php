<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\InterviewSchedule;
use App\Models\Batch; // <-- JANGAN LUPA IMPORT INI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    // Menampilkan halaman
    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'kwk') {
            abort(403);
        }

        $department = $user->kwkDepartment;
        $schedules = InterviewSchedule::where('department_id', $department->id)
                                    ->orderBy('start_time', 'asc') // Pastikan ini sesuai dengan nama kolom migrationmu
                                    ->get();
                                    
        // Cek apakah ada Batch aktif
        $activeBatch = Batch::where('is_active', true)->first();

        return view('admin.departments.index', compact('department', 'schedules', 'activeBatch'));
    }

    // Update info (Hanya bisa jika ada Batch aktif)
    public function update(Request $request, Department $department)
    {
        if (!Batch::where('is_active', true)->exists()) {
            return redirect()->back()->with('error', 'Akses ditolak! BPH belum membuka Batch pendaftaran.');
        }

        $request->validate([
            'contact_person' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $department->update($request->only(['contact_person', 'description']));
        return redirect()->back()->with('success', 'Informasi departemen berhasil diperbarui!');
    }

    // Tambah Jadwal (Hanya bisa jika ada Batch aktif)
    public function storeSchedule(Request $request, Department $department)
    {
        if (!Batch::where('is_active', true)->exists()) {
            return redirect()->back()->with('error', 'Akses ditolak! BPH belum membuka Batch pendaftaran.');
        }

        $request->validate([
            'tanggal' => 'required|date',
            'jam_mulai' => 'required', // Hapus date_format:H:i
            'jam_selesai' => 'required', // Hapus date_format:H:i|after:jam_mulai
            'tipe' => 'required|in:online,offline',
            'lokasi' => 'nullable|string|max:255',
            'pewawancara' => 'nullable|string|max:255',
        ]);

        $jamMulaiStr = strlen($request->jam_mulai) == 5 ? $request->jam_mulai . ':00' : $request->jam_mulai;
        $jamSelesaiStr = strlen($request->jam_selesai) == 5 ? $request->jam_selesai . ':00' : $request->jam_selesai;

        $waktuMulai = $request->tanggal . ' ' . $jamMulaiStr;
        $waktuSelesai = $request->tanggal . ' ' . $jamSelesaiStr;

        // Validasi manual agar jam selesai tidak sebelum jam mulai
        if (strtotime($waktuSelesai) <= strtotime($waktuMulai)) {
            return redirect()->back()->withErrors(['jam_selesai' => 'Jam selesai harus lebih akhir dari jam mulai.']);
        }

        InterviewSchedule::create([
            'department_id' => $department->id,
            'start_time' => $waktuMulai, 
            'end_time' => $waktuSelesai, 
            'tipe' => $request->tipe,
            'lokasi' => $request->lokasi,
            'pewawancara' => $request->pewawancara,
        ]);

        return redirect()->back()->with('success', 'Slot jadwal wawancara berhasil ditambahkan!');
    }

    // Hapus Jadwal (Hanya bisa jika ada Batch aktif)
    public function destroySchedule(InterviewSchedule $schedule)
    {
        if (!Batch::where('is_active', true)->exists()) {
            return redirect()->back()->with('error', 'Akses ditolak! BPH belum membuka Batch pendaftaran.');
        }

        $schedule->delete();
        return redirect()->back()->with('success', 'Jadwal wawancara berhasil dihapus!');
    }
}