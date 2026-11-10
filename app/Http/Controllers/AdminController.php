<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // Tarik batch yang sedang aktif
        $activeBatch = \App\Models\Batch::where('is_active', true)->first();
        
        // Data khusus BPH (Status Departemen)
        $departments = \App\Models\Department::withCount('interviewSchedules')->get();

        // Data khusus KWK (Jadwal & Pelamar)
        $kwkSchedules = collect();
        if ($user->role === 'kwk') {
            $kwkSchedules = \App\Models\InterviewSchedule::where('department_id', $user->department_id)
                                ->with('applicants') // Tarik relasi pelamarnya
                                ->orderBy('start_time', 'asc')
                                ->get();
        }

        return view('admin.dashboard', compact('user', 'activeBatch', 'departments', 'kwkSchedules'));
    }

    public function togglePortfolio(\App\Models\Department $department)
    {
        $department->update([
            'requires_portfolio' => !$department->requires_portfolio
        ]);

        return redirect()->back()->with('success', "Syarat portofolio untuk divisi {$department->name} berhasil diubah!");
    }
}