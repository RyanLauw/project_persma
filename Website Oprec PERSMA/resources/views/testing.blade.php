<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Developer Testing Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-5xl">
        <div class="text-center mb-8">
            <span class="bg-slate-800 text-white text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-widest shadow-md">
                Developer Testing Panel
            </span>
            <h1 class="text-3xl font-extrabold text-gray-800 mt-4">Simulasi Login Sistem</h1>
            <p class="text-gray-500 mt-2">Gunakan panel ini untuk menguji hak akses BPH, KWK, dan Pelamar.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-md hover:shadow-lg transition-shadow flex flex-col justify-between">
                <div>
                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Akses Pelamar</h4>
                    <p class="text-sm text-gray-500 mb-6 leading-relaxed">Uji coba alur pendaftaran, pengisian data diri, portofolio, hingga memilih sesi wawancara.</p>
                </div>
                <a href="{{ route('dev.login.applicant') }}" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 px-4 rounded-xl text-sm text-center transition-colors flex items-center justify-center gap-2">
                    🧑‍🎓 Login Pelamar Dummy
                </a>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-md hover:shadow-lg transition-shadow flex flex-col justify-between">
                <div>
                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Akses Inti (BPH)</h4>
                    <p class="text-sm text-gray-500 mb-6 leading-relaxed">Uji coba kontrol buka/tutup batch, pengaturan syarat portofolio, dan eksekusi kelulusan.</p>
                </div>
                <a href="{{ route('dev.login.bph') }}" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-3 px-4 rounded-xl text-sm text-center transition-colors flex items-center justify-center gap-2">
                    👑 Login Admin BPH
                </a>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-md hover:shadow-lg transition-shadow flex flex-col justify-between">
                <div>
                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Akses Divisi (KWK)</h4>
                    <p class="text-sm text-gray-500 mb-4 leading-relaxed">Pilih divisi spesifik untuk menguji jadwal wawancara, pemantauan sesi, dan penilaian.</p>
                </div>
                
                <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-100 p-2 rounded-xl bg-gray-50 scrollbar-hide">
                    @forelse($kwkDepartments as $dept)
                        <a href="{{ route('dev.login.kwk', $dept->id) }}" class="block bg-white hover:bg-blue-600 hover:text-white border border-gray-200 text-gray-700 text-xs font-bold py-2.5 px-3 rounded-lg transition-colors shadow-sm">
                            👔 {{ $dept->name }}
                        </a>
                    @empty
                        <p class="text-xs text-red-400 italic text-center py-4 font-semibold">Jalankan seeder database terlebih dahulu!</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="text-center mt-12">
            <a href="{{ url('/') }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm hover:underline">
                &larr; Kembali ke Halaman Utama
            </a>
        </div>
    </div>

</body>
</html>