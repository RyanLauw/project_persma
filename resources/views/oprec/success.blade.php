@extends('layouts.master', ['step' => 5])

@section('content')
<div class="flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white p-8 md:p-12 rounded-3xl shadow-sm border border-gray-100 text-center max-w-2xl w-full">
        
        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
            <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4 tracking-tight">
            Pendaftaran Berhasil!
        </h2>
        
        <p class="text-base text-gray-600 mb-8 leading-relaxed">
            Terima kasih telah mendaftar sebagai calon anggota Lembaga Pers Mahasiswa (Persma) PCU. Data diri, pilihan divisi, portofolio, dan jadwal wawancara kamu telah berhasil kami rekam di dalam sistem.
        </p>

        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 mb-10 text-left">
            <h4 class="text-sm font-bold text-blue-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                <span>📌</span> Langkah Selanjutnya
            </h4>
            <ul class="list-disc pl-5 space-y-2 text-sm text-blue-800">
                <li>Catat tanggal, jam, dan lokasi wawancara yang sudah kamu pilih.</li>
                <li>Siapkan dirimu dengan baik dan datang tepat waktu.</li>
                <li>Pantau terus Email Institusi dan WhatsApp/LINE kamu untuk pengumuman lebih lanjut dari narahubung divisi terkait.</li>
            </ul>
        </div>

        <a href="{{ route('home') }}" class="inline-block w-full sm:w-auto bg-slate-800 hover:bg-slate-900 text-white font-bold py-3.5 px-8 rounded-xl shadow-md transition-all hover:scale-105">
            Kembali ke Halaman Utama
        </a>
        
    </div>
</div>
@endsection