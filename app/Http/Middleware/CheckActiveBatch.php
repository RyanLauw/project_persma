<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Batch;

class CheckActiveBatch
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah ada batch yang sedang aktif
        $activeBatch = Batch::where('is_active', true)->exists();

        // Jika tidak ada yang aktif, tendang user kembali ke halaman utama (home)
        if (!$activeBatch) {
            return redirect('/')->with('error', 'Maaf, pendaftaran Persma PCU saat ini sedang ditutup.');
        }

        // Jika ada yang aktif, persilakan masuk ke form pendaftaran
        return $next($request);
    }
}