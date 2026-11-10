@extends('admin.layout')

@section('header_title', 'Pengaturan Departemen: ' . $department->name)

@section('content')

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
        <strong class="font-bold">Gagal menyimpan data!</strong>
        <ul class="list-disc pl-5 mt-2 text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(!$activeBatch)
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg shadow-sm">
        <div class="flex items-center">
            <svg class="h-8 w-8 text-yellow-500 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
                <h3 class="text-lg font-bold text-yellow-800">Menunggu Event Perekrutan Dibuka</h3>
                <p class="text-sm text-yellow-700 mt-1">Anda belum bisa melengkapi informasi departemen atau membuat jadwal wawancara karena BPH belum membuka gelombang (Batch) rekrutmen yang baru.</p>
            </div>
        </div>
    </div>
@else
    <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg mb-6 flex justify-between items-center">
        <div>
            <p class="text-sm font-bold text-blue-800">Event Aktif: {{ $activeBatch->nama_batch }}</p>
            <p class="text-xs text-blue-600">Pastikan tanggal jadwal wawancara berada dalam rentang event ini.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-fit">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Informasi Publik Departemen</h3>
            
            <div class="mb-5 bg-slate-50 border border-slate-200 rounded-lg p-4">
                <h4 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-3">Data Terkini</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-slate-500 mb-0.5">Nama Contact Person</p>
                        <p class="text-sm font-bold {{ $department->contact_name ? 'text-slate-800' : 'text-red-500 italic' }}">
                            {{ $department->contact_name ?: 'Belum diatur' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-0.5">Nomor WhatsApp</p>
                        <p class="text-sm font-bold {{ $department->contact_wa ? 'text-slate-800' : 'text-red-500 italic' }}">
                            {{ $department->contact_wa ?: 'Belum diatur' }}
                        </p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.departments.update', $department->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Perbarui Nama Contact Person</label>
                    <input type="text" name="contact_name" value="{{ old('contact_name', $department->contact_name) }}" placeholder="Contoh: Budi Santoso" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-3 py-2 border">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Perbarui Nomor WhatsApp</label>
                    <input type="text" name="contact_wa" value="{{ old('contact_wa', $department->contact_wa) }}" placeholder="Contoh: 081234567890" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-3 py-2 border">
                    <p class="text-xs text-gray-500 mt-1">Gunakan format nomor yang valid agar mudah dihubungi pendaftar.</p>
                </div>
                
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition-colors text-sm mt-2">
                    Simpan Perubahan
                </button>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Manajemen Jadwal Wawancara</h3>
            
            <form action="{{ route('admin.departments.schedules.store', $department->id) }}" method="POST" class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                @csrf
                <div class="grid grid-cols-2 gap-4 mb-3">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" required class="w-full rounded-md border-gray-300 shadow-sm text-sm px-3 py-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Jam Mulai</label>
                        <input type="time" name="jam_mulai" required class="w-full rounded-md border-gray-300 shadow-sm text-sm px-3 py-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Jam Selesai</label>
                        <input type="time" name="jam_selesai" required class="w-full rounded-md border-gray-300 shadow-sm text-sm px-3 py-2 border">
                    </div>
                    
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Tipe Wawancara</label>
                        <select name="tipe" required class="w-full rounded-md border-gray-300 shadow-sm text-sm px-3 py-2 border">
                            <option value="offline">Offline</option>
                            <option value="online">Online</option>
                        </select>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Pewawancara</label>
                        <input type="text" name="pewawancara" placeholder="Contoh: Budi & Siti" class="w-full rounded-md border-gray-300 shadow-sm text-sm px-3 py-2 border">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Lokasi / Link Online</label>
                        <input type="text" name="lokasi" placeholder="Contoh: Selasar Gedung T / Link Zoom" class="w-full rounded-md border-gray-300 shadow-sm text-sm px-3 py-2 border">
                    </div>
                </div>
                <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-2 px-4 rounded-md transition-colors text-sm">
                    + Tambah Slot Wawancara
                </button>
            </form>

            <h4 class="text-sm font-bold text-gray-700 mb-2">Slot Jadwal Aktif</h4>
            <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                @forelse($schedules as $schedule)
                    <div class="flex justify-between items-center bg-white border border-gray-200 p-3 rounded-lg shadow-sm">
                        <div>
                            <p class="text-sm font-bold text-gray-800">
                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('d M Y') }}
                                <span class="ml-2 uppercase text-[10px] font-bold px-2 py-0.5 rounded {{ $schedule->tipe === 'online' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700' }}">{{ $schedule->tipe }}</span>
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                🕒 {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} 
                                | 📍 {{ $schedule->lokasi ?? '-' }} 
                                | 🗣️ {{ $schedule->pewawancara ?? '-' }}
                            </p>
                        </div>
                        <form action="{{ route('admin.departments.schedules.destroy', $schedule->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-bold p-1 ml-2" onclick="return confirm('Hapus jadwal ini?')">
                                &times; Hapus
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-xs text-gray-500 text-center italic py-4">Belum ada jadwal wawancara yang dibuat.</p>
                @endforelse
            </div>
        </div>
    </div>
@endif
@endsection