web route
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OprecController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\DepartmentController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// ==========================================
// 1. HALAMAN PUBLIK (Pendaftar)
// ==========================================
Route::get('/', function () {
    // Halaman utama sekarang murni hanya desain publik
    return view('home'); 
})->name('home');

Route::get('/login', function () {
    return redirect()->route('home')->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman tersebut.');
})->name('login');


// ==========================================
// 2. HALAMAN TESTING DEVELOPER (Rahasia)
// ==========================================
Route::get('/testing', function () {
    // Data KWK hanya ditarik untuk halaman testing panel
    $kwkDepartments = \App\Models\Department::where('name', '!=', 'BPH')->get();
    return view('testing', compact('kwkDepartments')); 
})->name('testing');


// ==========================================
// 3. AUTENTIKASI GOOGLE SSO
// ==========================================
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);
Route::post('/logout', [GoogleAuthController::class, 'logout'])->name('logout');


// ==========================================
// 4. ALUR PENDAFTARAN MAHASISWA (APPLICANT)
// ==========================================
Route::middleware(['auth', \App\Http\Middleware\CheckActiveBatch::class])->group(function () {
    // Untuk mendeteksi progress terakhir
    Route::get('/register/continue', [App\Http\Controllers\OprecController::class, 'redirectToLatestStep'])->name('register.continue');
    
    // Step 1: Data Pribadi
    Route::get('/register/step-1', [OprecController::class, 'stepOne'])->name('register.step1');
    Route::post('/register/step-1', [OprecController::class, 'postStepOne']);

    // Step 2: Pilih Departemen & Upload Portofolio
    Route::get('/register/step-2', [OprecController::class, 'stepTwo'])->name('register.step2');
    Route::post('/register/step-2', [OprecController::class, 'postStepTwo']);

    // Step 3: Pilih Jadwal Wawancara
    Route::get('/register/step-3', [OprecController::class, 'stepThree'])->name('register.step3');
    Route::post('/register/step-3', [OprecController::class, 'postStepThree']);

    // Step 4: Informasi Kontak (Narahubung)
    Route::get('/register/step-4', [OprecController::class, 'stepFour'])->name('register.step4');
    Route::post('/register/step-4', [OprecController::class, 'postStepFour']);

    // Step 5: Konfirmasi & Submit Final
    Route::get('/register/step-5', [OprecController::class, 'stepFive'])->name('register.step5');
    Route::post('/register/submit', [OprecController::class, 'submitForm'])->name('register.submit');
    
    // Halaman Sukses
    Route::get('/register/success', [OprecController::class, 'success'])->name('register.success');
});


// ==========================================
// 5. RUTE ADMIN (BPH & KWK)
// ==========================================
Route::middleware(['auth', 'role:bph,kwk'])->prefix('admin')->group(function () {
    
    // Dashboard & Pelamar (Bisa diakses BPH & KWK)
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/applicants', [ApplicantController::class, 'index'])->name('admin.applicants.index');
    Route::get('/applicants/{id}', [ApplicantController::class, 'show'])->name('admin.applicants.show');
    Route::put('/applicants/{id}/notes/{department_id}', [ApplicantController::class, 'updateNotes'])->name('admin.applicants.notes');
    Route::get('/document/{path}', [ApplicantController::class, 'viewDocument'])->where('path', '.*')->name('admin.document.view');

    // Rute KHUSUS BPH
    Route::middleware(['role:bph'])->group(function () {
        Route::put('/batches/{batch}', [BatchController::class, 'update'])->name('admin.batches.update');
        Route::get('/batches', [BatchController::class, 'index'])->name('admin.batches.index');
        Route::post('/batches', [BatchController::class, 'store'])->name('admin.batches.store');
        Route::patch('/batches/{batch}/toggle', [BatchController::class, 'toggleStatus'])->name('admin.batches.toggle');
        Route::put('/applicants/{id}/status/{department_id}', [ApplicantController::class, 'updateStatus'])->name('admin.applicants.status');
        Route::patch('/departments/{department}/toggle-portfolio', [AdminController::class, 'togglePortfolio'])->name('admin.departments.toggle_portfolio');
        
        // Penulisan URL dirapikan agar tidak double /admin/admin/
        Route::post('/batches/{id}/send-api', [BatchController::class, 'sendApiToMainWeb'])->name('admin.batches.send_api');
    });

    // Rute KHUSUS KWK
    Route::middleware(['role:kwk'])->group(function () {
        Route::get('/my-department', [DepartmentController::class, 'index'])->name('admin.departments.index');
        Route::put('/my-department/{department}', [DepartmentController::class, 'update'])->name('admin.departments.update');
        Route::post('/my-department/{department}/schedules', [DepartmentController::class, 'storeSchedule'])->name('admin.departments.schedules.store');
        Route::delete('/my-department/schedules/{schedule}', [DepartmentController::class, 'destroySchedule'])->name('admin.departments.schedules.destroy');
    });
});


// ==========================================
// 6. RUTE TESTING DEVELOPER (BYPASS LOGIN)
// ==========================================

// Auto Login sebagai Pelamar (Applicant Dummy)
Route::get('/dev/login/applicant', function () {
    $user = User::firstOrCreate(
        ['email' => 'dummy@john.petra.ac.id'],
        ['name' => 'Tester Mahasiswa', 'role' => 'applicant', 'password' => bcrypt('password')]
    );
    Auth::login($user);
    return redirect()->route('register.step1');
})->name('dev.login.applicant');

// Auto Login sebagai Super Admin (BPH dari Seeder)
Route::get('/dev/login/bph', function () {
    $user = User::where('email', 'bph@persma.petra.ac.id')->firstOrFail();
    Auth::login($user);
    return redirect()->route('admin.dashboard');
})->name('dev.login.bph');

// Auto Login sebagai KWK berdasarkan ID Departemen
Route::get('/dev/login/kwk/{department_id}', function ($department_id) {
    $user = User::where('role', 'kwk')
                ->where('department_id', $department_id)
                ->firstOrFail();
                
    Auth::login($user);
    return redirect()->route('admin.dashboard');
})->name('dev.login.kwk');
