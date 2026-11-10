@extends('layouts.master', ['step' => 5])

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Konfirmasi Data</h2>
    <p class="text-sm text-gray-500 mt-1">Periksa kembali seluruh data pendaftaranmu. Pastikan semua informasi sudah benar sebelum mengirimkan formulir.</p>
</div>

<div class="space-y-8 mb-8">
    
    <div class="border border-gray-200 rounded-xl overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-700">Data Diri</h3>
            <a href="{{ route('register.step1') }}" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">Edit Data</a>
        </div>
        <div class="p-6">
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $step1['nama_lengkap'] }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">NRP</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $step1['nrp'] }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Program Studi</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $step1['program_studi'] }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">TTL</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $step1['tempat_lahir'] }}, {{ \Carbon\Carbon::parse($step1['tanggal_lahir'])->format('d/m/Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">No. Telepon / WA</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $step1['no_telepon'] }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Sosial Media</dt>
                    <dd class="mt-1 text-sm text-gray-900">IG: {{ $step1['instagram'] ?? '-' }} | Line: {{ $step1['id_line'] ?? '-' }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Alamat Domisili</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $step1['alamat_domisili'] }}</dd>
                </div>
                
                <div class="sm:col-span-2 border-t pt-4 mt-2 border-gray-100">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Berkas Lampiran</h4>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div>
                            <dt class="text-[11px] font-medium text-gray-500">Scan / Foto KTM</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if(isset($step1['ktm_path']))
                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Terlampir</span>
                                @else
                                    <span class="text-gray-400 italic text-xs">Belum ada</span>
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt class="text-[11px] font-medium text-gray-500">Pas Foto 3x4</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if(isset($step1['pas_foto_path']))
                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Terlampir</span>
                                @else
                                    <span class="text-gray-400 italic text-xs">Belum ada</span>
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt class="text-[11px] font-medium text-gray-500">Transkrip KHS</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if(isset($step1['transkrip_path']))
                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Terlampir</span>
                                @else
                                    <span class="text-gray-400 italic text-xs">Belum ada</span>
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt class="text-[11px] font-medium text-gray-500">Bukti Poin SKKK</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if(isset($step1['skkk_path']))
                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Terlampir</span>
                                @else
                                    <span class="text-gray-400 italic text-xs">Belum ada</span>
                                @endif
                            </dd>
                        </div>
                    </div>
                </div>

                <div class="sm:col-span-2 border-t pt-4 border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Jadwal Hidup</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        @if(isset($step1['jadwal_hidup_path']))
                            <span class="inline-flex items-center rounded-md bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Terlampir</span>
                        @else
                            <span class="text-gray-400 italic">Tidak melampirkan</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="border border-gray-200 rounded-xl overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-700">Pilihan & Jadwal</h3>
            <div class="space-x-4">
                <a href="{{ route('register.step2') }}" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">Edit Divisi</a>
                <a href="{{ route('register.step3') }}" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">Edit Jadwal</a>
            </div>
        </div>
        
        <div class="p-6 space-y-6">
            <div>
                <h4 class="text-sm font-bold text-gray-800 mb-3 border-b pb-1">Pilihan 1: {{ $dept1->name }}</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <span class="block text-xs font-medium text-gray-500">Waktu Wawancara:</span>
                        <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($jadwal1->start_time)->translatedFormat('l, d F Y') }} ({{ \Carbon\Carbon::parse($jadwal1->start_time)->format('H:i') }} WIB)</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500">Pewawancara:</span>
                        <span class="text-sm text-gray-900 font-bold">{{ $jadwal1->pewawancara ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500">Lokasi / Tautan:</span>
                        <span class="text-sm text-gray-900 font-medium">{{ $jadwal1->lokasi ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500">Tipe Wawancara:</span>
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold uppercase tracking-wider {{ $jadwal1->tipe == 'online' ? 'bg-green-100 text-green-800' : 'bg-indigo-100 text-indigo-800' }}">
                            {{ $jadwal1->tipe }}
                        </span>
                    </div>
                    @if($dept1->requires_portfolio)
                        <div class="sm:col-span-2">
                            <p class="text-sm text-gray-700">
                                <strong>Portofolio Pilihan 1:</strong> 
                                <a href="{{ $dept1->pivot->portfolio_path }}" target="_blank" class="text-blue-600 hover:underline font-medium inline-flex items-center gap-1">
                                    Buka Link Portofolio ↗
                                </a>
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            @if($dept2 && $jadwal2)
            <div class="pt-4 border-t border-gray-100">
                <h4 class="text-sm font-bold text-gray-800 mb-3 border-b pb-1">Pilihan 2: {{ $dept2->name }}</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <span class="block text-xs font-medium text-gray-500">Waktu Wawancara:</span>
                        <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($jadwal2->start_time)->translatedFormat('l, d F Y') }} ({{ \Carbon\Carbon::parse($jadwal2->start_time)->format('H:i') }} WIB)</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500">Pewawancara:</span>
                        <span class="text-sm text-gray-900 font-bold">{{ $jadwal2->pewawancara ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500">Lokasi / Tautan:</span>
                        <span class="text-sm text-gray-900 font-medium">{{ $jadwal2->lokasi ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500">Tipe Wawancara:</span>
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold uppercase tracking-wider {{ $jadwal2->tipe == 'online' ? 'bg-green-100 text-green-800' : 'bg-indigo-100 text-indigo-800' }}">
                            {{ $jadwal2->tipe }}
                        </span>
                    </div>
                    @if($dept2->requires_portfolio)
                        <div class="sm:col-span-2">
                            <p class="text-sm text-gray-700">
                                <strong>Portofolio Pilihan 2:</strong> 
                                <a href="{{ $dept2->pivot->portfolio_path }}" target="_blank" class="text-blue-600 hover:underline font-medium inline-flex items-center gap-1">
                                    Buka Link Portofolio ↗
                                </a>
                            </p>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<form action="{{ route('register.submit') }}" method="POST">
    @csrf

    <div class="bg-blue-50 rounded-xl p-4 border border-blue-200 flex items-start mb-8">
        <div class="flex-shrink-0 mt-0.5">
            <svg class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3 text-sm text-blue-800">
            <p>
                Dengan menekan tombol <strong>Kirim Formulir</strong>, saya menyatakan bahwa seluruh data yang saya isikan adalah benar. Saya memahami bahwa data tidak dapat diubah kembali setelah dikirimkan.
            </p>
        </div>
    </div>

    <div class="flex justify-between pt-6 border-t border-gray-200">
        <a href="{{ route('register.step4') }}" class="inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-6 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
            &laquo; Kembali
        </a>
        <button type="submit" onclick="return confirm('Apakah kamu yakin data sudah benar dan siap dikirim?')" class="inline-flex justify-center rounded-md border border-transparent bg-green-600 py-2 px-8 text-base font-bold text-white shadow-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
            Kirim Formulir &raquo;
        </button>
    </div>
</form>
@endsection