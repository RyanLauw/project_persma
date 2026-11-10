<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Pastikan user sudah login
        if (!auth()->check()) {
            return redirect()->route('home');
        }

        // Cek apakah role user saat ini ada di dalam daftar role yang diizinkan
        if (!in_array(auth()->user()->role, $roles)) {
            abort(403, 'Akses Ditolak. Halaman ini khusus untuk kepanitiaan Persma.');
        }

        return $next($request);
    }
}

