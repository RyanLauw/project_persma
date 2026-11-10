<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\InterviewSchedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OprecController extends Controller
{
    // ==========================================
    // TAHAP 1: Data Pribadi (Auto Save ke DB)
    // ==========================================
    public function stepOne()
    {
        $user = Auth::user();
        // Tarik data dari DB jika sudah pernah mengisi draf
        $data = $user->profile ? $user->profile->toArray() : [];
        $activeBatch = \App\Models\Batch::where('is_active', true)->first();
        
        return view('oprec.step1', compact('data', 'activeBatch'));
    }

    public function postStepOne(Request $request)
    {
        // 1. DEKLARASI VARIABEL DI AWAL AGAR TIDAK ERROR
        $user = Auth::user();
        $hasProfile = $user->profile; 
        $profileId = optional($user->profile)->id;
        $activeBatch = \App\Models\Batch::where('is_active', true)->first();
        
        // 2. LOGIKA VALIDASI PINTAR (Wajib isi jika belum ada di database)
        $validated = $request->validate([
            'nama_lengkap'    => 'required|string|max:255',
            'nrp'             => 'required|string|unique:applicant_profiles,nrp,' . $profileId,
            'program_studi'   => 'required|string',
            'tempat_lahir'    => 'required|string',
            'tanggal_lahir'   => 'required|date',
            'no_telepon'      => 'required|string',
            'alamat_domisili' => 'required|string',
            'agama'           => 'required|string',
            'instagram'       => 'nullable|string',
            'id_line'         => 'nullable|string',
            'ipk'             => 'required|numeric|min:0|max:4',
            
            // Jadwal Hidup (menyesuaikan aturan Batch)
            'jadwal_hidup'    => (isset($activeBatch) && $activeBatch->requires_cv && (!$hasProfile || !$hasProfile->jadwal_hidup_path)) ? 'required|file|mimes:pdf,jpg,jpeg,png|max:10240' : 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240', 
            
            // Dokumen Baru (Wajib jika profil kosong/belum upload)
            'ktm'             => (!$hasProfile || !$hasProfile->ktm_path) ? 'required|file|mimes:pdf,jpg,jpeg,png|max:10240' : 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'pas_foto'        => (!$hasProfile || !$hasProfile->pas_foto_path) ? 'required|file|mimes:jpg,jpeg,png|max:10240' : 'nullable|file|mimes:jpg,jpeg,png|max:10240',
            'transkrip'       => (!$hasProfile || !$hasProfile->transkrip_path) ? 'required|file|mimes:pdf,jpg,jpeg,png|max:10240' : 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'skkk'            => (!$hasProfile || !$hasProfile->skkk_path) ? 'required|file|mimes:pdf,jpg,jpeg,png|max:10240' : 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ],[
            // Kustomisasi pesan error agar lebih manusiawi
            'nrp.unique' => 'NRP ini sudah terdaftar di sistem. Jika ini adalah kesalahan, silakan hubungi panitia.',
        ]);

        // 3. PROSES UPLOAD FILE (Eksplisit & Anti-Error)
        $jadwal_hidup_path = $request->hasFile('jadwal_hidup') ? $request->file('jadwal_hidup')->store('jadwal_hidup') : optional($user->profile)->jadwal_hidup_path;
        $ktm_path          = $request->hasFile('ktm') ? $request->file('ktm')->store('ktm') : optional($user->profile)->ktm_path;
        $pas_foto_path     = $request->hasFile('pas_foto') ? $request->file('pas_foto')->store('pas_foto') : optional($user->profile)->pas_foto_path;
        $transkrip_path    = $request->hasFile('transkrip') ? $request->file('transkrip')->store('transkrip') : optional($user->profile)->transkrip_path;
        $skkk_path         = $request->hasFile('skkk') ? $request->file('skkk')->store('skkk') : optional($user->profile)->skkk_path;

        // 4. UPDATE PROGRESS PENDAFTARAN
        $currentProgress = optional($user->profile)->progress_step ?? 1;
        $newProgress = max($currentProgress, 2);

        // 5. SIMPAN KE DATABASE
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'batch_id'          => $activeBatch ? $activeBatch->id : null,
                'nama_lengkap'      => $validated['nama_lengkap'],
                'nrp'               => $validated['nrp'],
                'program_studi'     => $validated['program_studi'],
                'tempat_lahir'      => $validated['tempat_lahir'],
                'tanggal_lahir'     => $validated['tanggal_lahir'],
                'no_telepon'        => $validated['no_telepon'],
                'alamat_domisili'   => $validated['alamat_domisili'],
                'agama'             => $validated['agama'],
                'instagram'         => $validated['instagram'] ?? null,
                'id_line'           => $validated['id_line'] ?? null,
                'ipk'               => $validated['ipk'],
                
                'jadwal_hidup_path' => $jadwal_hidup_path,
                'ktm_path'          => $ktm_path,
                'pas_foto_path'     => $pas_foto_path,
                'transkrip_path'    => $transkrip_path,
                'skkk_path'         => $skkk_path,
                
                'progress_step'     => $newProgress
            ]
        );

        return redirect()->route('register.step2');
    }

    // ==========================================
    // TAHAP 2: Pilih Departemen & Portofolio
    // ==========================================
    public function stepTwo()
    {
        $user = Auth::user();
        if (!$user->profile) return redirect()->route('register.step1');

        $activeBatch = \App\Models\Batch::where('is_active', true)->first();
        $departments = \App\Models\Department::where('name', '!=', 'BPH')->get();
        
        // Ambil data draf dari tabel pivot
        $savedDepts = $user->appliedDepartments()->orderBy('pivot_choice_order')->get();
        $data = [
            'pilihan_1'    => $savedDepts->where('pivot.choice_order', 1)->first()->id ?? '',
            'portofolio_1' => $savedDepts->where('pivot.choice_order', 1)->first()->pivot->portfolio_path ?? '',
            'pilihan_2'    => $savedDepts->where('pivot.choice_order', 2)->first()->id ?? '',
            'portofolio_2' => $savedDepts->where('pivot.choice_order', 2)->first()->pivot->portfolio_path ?? '',
        ];
        
        return view('oprec.step2', compact('activeBatch', 'departments', 'data'));
    }

    public function postStepTwo(Request $request)
    {
        $dept1 = \App\Models\Department::find($request->pilihan_1);
        $dept2 = $request->pilihan_2 ? \App\Models\Department::find($request->pilihan_2) : null;

        $validated = $request->validate([
            'pilihan_1'    => 'required|exists:departments,id',
            'pilihan_2'    => 'nullable|exists:departments,id|different:pilihan_1',
            'portofolio_1' => ($dept1 && $dept1->requires_portfolio) ? 'required|url' : 'nullable|url',
            'portofolio_2' => ($dept2 && $dept2->requires_portfolio) ? 'required|url' : 'nullable|url',
        ]);

        $user = Auth::user();
        
        // Simpan langsung ke pivot table!
        $user->appliedDepartments()->detach();
        $user->appliedDepartments()->attach($validated['pilihan_1'], [
            'choice_order'   => 1,
            'portfolio_path' => $validated['portofolio_1'] ?? null,
            'status'         => 'pending'
        ]);

        if (!empty($validated['pilihan_2'])) {
            $user->appliedDepartments()->attach($validated['pilihan_2'], [
                'choice_order'   => 2,
                'portfolio_path' => $validated['portofolio_2'] ?? null,
                'status'         => 'pending'
            ]);
        }

        // Update progress
        $currentProgress = $user->profile?->progress_step ?? 1;
        $user->profile()->update(['progress_step' => max($currentProgress, 3)]);

        return redirect()->route('register.step3');
    }

    // ==========================================
    // TAHAP 3: Pilih Jadwal Wawancara
    // ==========================================
    public function stepThree()
    {
        $user = Auth::user();
        $savedDepts = $user->appliedDepartments;
        if ($savedDepts->isEmpty()) return redirect()->route('register.step2');

        $pilihan1 = $savedDepts->where('pivot.choice_order', 1)->first()->id ?? null;
        $pilihan2 = $savedDepts->where('pivot.choice_order', 2)->first()->id ?? null;

        // Tambahkan with('applicants') agar tidak lemot saat dicek di Blade
        $schedulesChoice1 = InterviewSchedule::with('applicants')->where('department_id', $pilihan1)->get();
        $schedulesChoice2 = $pilihan2 ? InterviewSchedule::with('applicants')->where('department_id', $pilihan2)->get() : collect();
        
        // Ambil jadwal tersimpan
        $savedInterviews = $user->interviews()->get();
        $data = [
            'jadwal_pilihan_1' => $savedInterviews->where('pivot.for_choice_order', 1)->first()->id ?? '',
            'jadwal_pilihan_2' => $savedInterviews->where('pivot.for_choice_order', 2)->first()->id ?? '',
        ];

        return view('oprec.step3', compact('schedulesChoice1', 'schedulesChoice2', 'data'));
    }

    public function postStepThree(Request $request)
    {
        $user = Auth::user();
        $hasPilihan2 = $user->appliedDepartments()->where('choice_order', 2)->exists();
        
        $rules = [
            'jadwal_pilihan_1' => 'required|exists:interview_schedules,id',
        ];
        if ($hasPilihan2) {
            $rules['jadwal_pilihan_2'] = 'required|exists:interview_schedules,id';
        }

        $validated = $request->validate($rules);

        // --- PROTEKSI JADWAL WAWANCARA (ANTI DOUBLE-BOOKING) ---
        
        // Cek Pilihan 1
        $schedule1 = InterviewSchedule::find($validated['jadwal_pilihan_1']);
        $isFull1 = $schedule1->applicants()->where('user_id', '!=', $user->id)->exists();
        if ($isFull1) {
            return redirect()->back()->with('error', 'Maaf, jadwal Wawancara Pilihan 1 baru saja diambil oleh orang lain. Silakan pilih jadwal yang berbeda.');
        }

        // Cek Pilihan 2 (Jika ada)
        if ($hasPilihan2) {
            $schedule2 = InterviewSchedule::find($validated['jadwal_pilihan_2']);
            $isFull2 = $schedule2->applicants()->where('user_id', '!=', $user->id)->exists();
            if ($isFull2) {
                return redirect()->back()->with('error', 'Maaf, jadwal Wawancara Pilihan 2 baru saja diambil oleh orang lain. Silakan pilih jadwal yang berbeda.');
            }
        }

        // --- AKHIR PROTEKSI ---

        // Simpan Jadwal ke DB
        $user->interviews()->detach();
        $user->interviews()->attach($validated['jadwal_pilihan_1'], ['for_choice_order' => 1]);
        if ($hasPilihan2) {
            $user->interviews()->attach($validated['jadwal_pilihan_2'], ['for_choice_order' => 2]);
        }

        // Update progress
        $currentProgress = $user->profile?->progress_step ?? 1;
        $user->profile()->update(['progress_step' => max($currentProgress, 4)]);

        return redirect()->route('register.step4');
    }

    // ==========================================
    // TAHAP 4: Informasi Kontak Departemen
    // ==========================================
    public function stepFour()
    {
        $user = Auth::user();
        $savedDepts = $user->appliedDepartments;
        if ($savedDepts->isEmpty()) return redirect()->route('register.step2');

        $dept1 = $savedDepts->where('pivot.choice_order', 1)->first();
        $dept2 = $savedDepts->where('pivot.choice_order', 2)->first();

        // Update progress ketika melewati halaman ini
        $currentProgress = $user->profile?->progress_step ?? 1;
        $user->profile()->update(['progress_step' => max($currentProgress, 5)]);

        return view('oprec.step4', compact('dept1', 'dept2'));
    }

    public function postStepFour(Request $request)
    {
        // Karena Step 4 hanya informasi, kita langsung arahkan ke Step 5
        return redirect()->route('register.step5');
    }

    // ==========================================
    // TAHAP 5: Konfirmasi & Finalisasi
    // ==========================================
    public function stepFive()
    {
        $user = Auth::user();
        
        if (!$user->profile || $user->profile->progress_step < 5) {
            return redirect()->route('register.step1')->with('error', 'Silakan lengkapi draf pendaftaran terlebih dahulu.');
        }

        // Data Profile
        $step1 = $user->profile->toArray();
        
        // Data Departemen
        $savedDepts = $user->appliedDepartments;
        $dept1 = $savedDepts->where('pivot.choice_order', 1)->first();
        $dept2 = $savedDepts->where('pivot.choice_order', 2)->first();

        // Data Jadwal
        $savedInterviews = $user->interviews;
        $jadwal1 = $savedInterviews->where('pivot.for_choice_order', 1)->first();
        $jadwal2 = $savedInterviews->where('pivot.for_choice_order', 2)->first();

        return view('oprec.step5', compact('step1', 'dept1', 'dept2', 'jadwal1', 'jadwal2'));
    }

    public function submitForm(Request $request)
    {
        $user = Auth::user();
        
        // Kunci pendaftaran dengan mengubah status progress ke 6 (Finalized)
        $user->profile()->update(['progress_step' => 6]);

        return redirect()->route('register.success')->with('success', 'Formulir pendaftaran berhasil dikirim secara permanen!');
    }

    public function redirectToLatestStep()
    {
        $user = Auth::user();
        
        // Ambil progress terakhir, jika belum punya profil, default ke step 1
        $currentStep = $user->profile?->progress_step ?? 1;

        // Arahkan user berdasarkan progress step yang tersimpan di database
        switch ($currentStep) {
            case 1:
                return redirect()->route('register.step1');
            case 2:
                return redirect()->route('register.step2');
            case 3:
                return redirect()->route('register.step3');
            case 4:
                return redirect()->route('register.step4');
            case 5:
                return redirect()->route('register.step5');
            case 6:
                return redirect()->route('register.success');
            default:
                return redirect()->route('register.step1');
        }
    }
    
    public function success()
    {
        return view('oprec.success'); 
    }
}