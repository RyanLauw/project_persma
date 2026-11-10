<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use GuzzleHttp\Client;

class GoogleAuthController extends Controller
{
    // Mengarahkan pendaftar ke halaman login Google
    public function redirect()
    {
        // Membuat client Guzzle yang mengabaikan verifikasi SSL
        $guzzleClient = new Client(['verify' => false]);
        
        return Socialite::driver('google')
            ->setHttpClient($guzzleClient)
            ->redirect();
    }
    // Menangkap respon balik dari Google setelah login
    public function callback()
    {
        try {
            $guzzleClient = new Client(['verify' => false]);
            
            $googleUser = Socialite::driver('google')
                ->setHttpClient($guzzleClient)
                ->stateless()
                ->user();
            $email = $googleUser->getEmail();

            // OPSIONAL (Saringan Ekstra): Pastikan email berasal dari domain petra.ac.id
            if (!Str::endsWith($email, '@petra.ac.id') && !Str::endsWith($email, '@john.petra.ac.id')) {
                return redirect()->route('home')->with('error', 'Akses ditolak! Anda harus menggunakan email institusi Petra (civitas akademika).');
            }

            // Cek apakah user sudah terdaftar di database kita
            $user = User::where('email', $email)->first();

            if ($user) {
                // Jika user sudah ada (baik applicant, bph, maupun kwk), langsung login
                Auth::login($user);
            } else {
                // Jika ini pendaftar baru, buatkan akun otomatis sebagai pelamar (applicant)
                $newUser = User::create([
                    'name'          => $googleUser->getName(),
                    'email'         => $email,
                    'password'      => bcrypt(Str::random(16)), // Password acak karena login menggunakan SSO
                    'role'          => 'applicant', // Role default untuk pendaftar awal
                    'department_id' => null,
                ]);

                Auth::login($newUser);
            }

            // Arahkan admin ke dashboard, dan pendaftar ke langkah 1 oprec
            if (Auth::user()->role === 'applicant') {
                return redirect()->route('register.step1');
            } else {
                return redirect()->route('admin.dashboard');
            }

        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Terjadi kesalahan saat autentikasi dengan Google: ' . $e->getMessage());
        }
    }

    // Fungsi Logout
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}