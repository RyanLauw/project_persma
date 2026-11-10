@extends('admin.layout')

@section('header_title', 'Dashboard')

@section('content')

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
        {{ session('success') }}
    </div>
@endif

<div class="mb-8">
    <h3 class="text-2xl font-bold text-gray-800">Halo, {{ $user->name }}! 👋</h3>
    <p class="text-gray-600">Selamat datang di Panel Admin Rekrutmen Persma.</p>
</div>

@if($user->role === 'bph')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- PANEL 1: EDIT BATCH AKTIF -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-blue-50 px-6 py-4 border-b border-blue-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-blue-900">Event Perekrutan Aktif</h3>
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
            </div>
            
            <div class="p-6">
                @if($activeBatch)
                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
                            <p class="text-sm text-red-700 font-bold mb-1">Gagal menyimpan perubahan:</p>
                            <ul class="list-disc pl-5 text-xs text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.batches.update', $activeBatch->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Event / Batch</label>
                            <input type="text" name="nama_batch" value="{{ old('nama_batch', $activeBatch->nama_batch) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-3 py-2 border">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Batch / Jabatan</label>
                            <select name="tipe_batch" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-3 py-2 border bg-white">
                                <option value="Intern 1" {{ old('tipe_batch', $activeBatch->tipe_batch) == 'Intern 1' ? 'selected' : '' }}>Intern 1</option>
                                <option value="Intern 2" {{ old('tipe_batch', $activeBatch->tipe_batch) == 'Intern 2' ? 'selected' : '' }}>Intern 2</option>
                                <option value="Fungsio" {{ old('tipe_batch', $activeBatch->tipe_batch) == 'Fungsio' ? 'selected' : '' }}>Fungsio</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $activeBatch->tanggal_mulai) }}" class="w-full rounded-md border-gray-300 shadow-sm text-sm px-3 py-2 border">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $activeBatch->tanggal_selesai) }}" class="w-full rounded-md border-gray-300 shadow-sm text-sm px-3 py-2 border">
                            </div>
                        </div>
                        
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <h4 class="text-sm font-bold text-gray-800 mb-3">Syarat Administrasi Pendaftaran</h4>
                            
                            <div class="flex items-center mb-4 bg-blue-50 p-3 rounded border border-blue-100">
                                <input type="checkbox" name="requires_cv" id="requires_cv" value="1" {{ old('requires_cv', $activeBatch->requires_cv) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-5 h-5">
                                <label for="requires_cv" class="ml-3 text-sm text-blue-900 font-bold">Wajibkan pelamar melampirkan Jadwal Hidup / CV</label>
                            </div>

                            <p class="text-xs text-gray-600 mb-2 font-bold uppercase tracking-wider">Tandai divisi yang Mewajibkan Portofolio:</p>
                            <div class="grid grid-cols-2 gap-2 mb-2">
                                @foreach($departments as $dept)
                                    @if($dept->name !== 'BPH') 
                                        <label class="flex items-start space-x-2 bg-white border border-gray-200 px-2 py-2 rounded cursor-pointer hover:bg-purple-50 hover:border-purple-200 transition-all shadow-sm">
                                            <input type="checkbox" name="portfolio_departments[]" value="{{ $dept->id }}" {{ $dept->requires_portfolio ? 'checked' : '' }} class="mt-0.5 rounded border-gray-300 text-purple-600 focus:ring-purple-500 w-3.5 h-3.5">
                                            <span class="text-[11px] text-gray-700 font-bold leading-tight">{{ $dept->name }}</span>
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition-colors text-sm w-full md:w-auto">
                                Simpan Perubahan Event
                            </button>
                            <a href="{{ route('admin.batches.index') }}" class="mt-3 md:mt-0 md:ml-2 text-sm text-gray-500 hover:text-blue-600 inline-block">Ke Manajemen Batch &rarr;</a>
                        </div>
                    </form>
                @else
                    <div class="text-center py-6">
                        <p class="text-gray-500 mb-3">Tidak ada event pendaftaran yang sedang dibuka.</p>
                        <a href="{{ route('admin.batches.index') }}" class="inline-block bg-slate-800 text-white px-4 py-2 rounded-md text-sm font-bold">
                            Buka Batch Baru
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- PANEL 2: STATUS KESIAPAN DEPARTEMEN -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Status Kesiapan KWK</h3>
            <p class="text-xs text-gray-500 mb-4">Pantau apakah KWK sudah mengisi info departemen dan jadwal wawancara.</p>
            
            <div class="space-y-3">
                @foreach($departments as $dept)
                    @if(strtolower($dept->name) === 'bph' || strtolower($dept->name) === 'badan pengurus harian')
                        @continue
                    @endif
                    @php
                        // LOGIKA BARU: Mengecek contact_name dan contact_wa
                        $isComplete = $dept->contact_name && $dept->contact_wa && $dept->interview_schedules_count > 0;
                    @endphp
                    
                    <div class="flex items-center justify-between p-3 border rounded-lg {{ $isComplete ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <p class="font-bold text-sm {{ $isComplete ? 'text-green-800' : 'text-red-800' }}">{{ $dept->name }}</p>
                                
                                <form action="{{ route('admin.departments.toggle_portfolio', $dept->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-[10px] font-bold px-2 py-0.5 rounded transition-all {{ $dept->requires_portfolio ? 'bg-purple-100 text-purple-800 hover:bg-purple-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                        {!! $dept->requires_portfolio ? '✨ Wajib Portofolio' : '⚙️ Tanpa Portofolio' !!}
                                    </button>
                                </form>
                            </div>
                            
                            <div class="text-xs text-gray-600 mt-1 flex gap-3">
                                <span>Contact Person (CP): {!! ($dept->contact_name && $dept->contact_wa) ? '✅' : '❌' !!}</span>
                                <span>Jadwal Wawancara: {!! $dept->interview_schedules_count > 0 ? '✅ (' . $dept->interview_schedules_count . ' slot)' : '❌' !!}</span>
                            </div>
                        </div>
                        
                        <div>
                            @if($isComplete)
                                <span class="bg-green-200 text-green-800 p-1.5 rounded-full inline-block">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </span>
                            @else
                                <span class="bg-red-200 text-red-800 p-1.5 rounded-full inline-block">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77-1.333.192 3 1.732 3z"></path></svg>
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
@else
    <!-- PANEL INFORMASI DIVISI -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-blue-500 mb-8 flex justify-between items-center">
        <div>
            <h3 class="text-xl font-bold text-gray-800">Departemen {{ $user->kwkDepartment->name ?? '' }}</h3>
            <p class="text-gray-600 mt-1 text-sm">Pantau jadwal wawancara Anda di sini. Untuk menambah slot jadwal, silakan ke menu <a href="{{ route('admin.departments.index') }}" class="font-bold text-blue-600 hover:underline">Divisi & Jadwal</a>.</p>
        </div>
        <div>
            @if($activeBatch)
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold border border-green-200">
                    Event Aktif: {{ $activeBatch->nama_batch }}
                </span>
            @else
                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-bold border border-red-200">
                    Event Pendaftaran Ditutup
                </span>
            @endif
        </div>
    </div>

    <!-- PANEL DAFTAR JADWAL DAN PELAMAR -->
    <div class="flex items-center justify-between mb-4 border-b pb-2">
        <h3 class="text-lg font-bold text-gray-800">Pantauan Sesi Wawancara</h3>
        <span class="text-sm text-gray-500">Total: {{ $kwkSchedules->count() }} Sesi</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($kwkSchedules as $schedule)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col h-full">
                <!-- Header Card (Tanggal & Waktu) -->
                <div class="bg-slate-800 px-4 py-3 flex justify-between items-start">
                    <div class="text-white">
                        <p class="font-bold text-sm">{{ \Carbon\Carbon::parse($schedule->start_time)->format('d M Y') }}</p>
                        <p class="text-xs text-slate-300">Pukul {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="{{ $schedule->tipe === 'online' ? 'bg-blue-500' : 'bg-orange-500' }} text-white text-[10px] uppercase font-bold px-2 py-1 rounded shadow-sm">
                            {{ $schedule->tipe }}
                        </span>
                    </div>
                </div>
                
                <!-- Info Tambahan: Lokasi & Pewawancara -->
                <div class="bg-slate-700 px-4 py-2 text-xs text-slate-300 flex flex-col gap-1 border-t border-slate-600">
                    <div class="flex items-center gap-2">
                        <span>📍</span> <span class="truncate">{{ $schedule->lokasi ?? 'Lokasi belum diatur' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span>🗣️</span> <span class="truncate">{{ $schedule->pewawancara ?? 'Pewawancara belum diatur' }}</span>
                    </div>
                </div>
                
                <!-- Daftar Pelamar di Sesi Ini -->
                <div class="p-4 flex-grow flex flex-col justify-center">
                    @if($schedule->applicants->count() > 0)
                        @php 
                            // Tarik 1 orang pertama (dan satu-satunya) yang mengambil jadwal ini
                            $applicant = $schedule->applicants->first(); 
                        @endphp
                        
                        <div class="bg-blue-50 border border-blue-100 rounded-lg p-3 text-center">
                            <span class="inline-block bg-blue-600 text-white text-[10px] uppercase font-bold px-2 py-1 rounded-full mb-2 shadow-sm">
                                🔒 Sesi Terisi
                            </span>
                            <p class="font-bold text-gray-900 text-sm">{{ $applicant->name }}</p>
                            
                            @if($applicant->profile)
                                <p class="text-xs text-gray-600 mt-0.5">{{ $applicant->profile->nrp }}</p>
                            @endif
                            
                            <div class="mt-2 pt-2 border-t border-blue-200">
                                <span class="text-[10px] uppercase font-bold text-blue-700">
                                    Kandidat Pilihan {{ $applicant->pivot->for_choice_order }}
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="bg-green-50 border border-green-100 rounded-lg p-4 flex flex-col items-center justify-center text-center h-full">
                            <span class="inline-block bg-green-100 text-green-700 border border-green-200 text-[10px] font-bold px-2 py-1 rounded-full mb-2">
                                ✅ Tersedia
                            </span>
                            <p class="text-xs text-gray-500">Slot kosong.<br>Belum diambil oleh pelamar.</p>
                        </div>
                    @endif
                </div>
                
                <!-- Tombol Pintasan Aksi -->
                <div class="bg-gray-50 border-t px-4 py-3">
                    <a href="{{ route('admin.applicants.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 flex items-center justify-center gap-1 w-full text-center">
                        Beri Nilai Pendaftar &rarr;
                    </a>
                </div>
            </div>
        @empty
            <!-- State Jika Belum Ada Jadwal -->
            <div class="col-span-full bg-gray-50 p-8 text-center rounded-xl border-2 border-dashed border-gray-300 flex flex-col items-center justify-center">
                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <p class="text-gray-600 font-medium mb-1">Jadwal Wawancara Masih Kosong</p>
                <p class="text-sm text-gray-500 mb-4">Anda belum membuat slot jadwal wawancara untuk departemen ini.</p>
                @if($activeBatch)
                    <a href="{{ route('admin.departments.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md text-sm transition-colors shadow-sm">
                        + Buat Jadwal Sekarang
                    </a>
                @endif
            </div>
        @endforelse
    </div>
@endif

@endsection