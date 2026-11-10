@extends('admin.layout')

@section('header_title', 'Manajemen Batch Rekrutmen')

@section('content')

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
        <strong class="font-bold">Gagal!</strong>
        <span class="block sm:inline">{{ session('error') }}</span>
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

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Buat Batch Baru</h3>
            <form action="{{ route('admin.batches.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Batch</label>
                    <input type="text" name="nama_batch" value="{{ old('nama_batch') }}" placeholder="Contoh: Intern 1 2026" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-3 py-2 border">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Batch / Jabatan</label>
                    <select name="tipe_batch" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-3 py-2 border bg-white">
                        <option value="Intern 1" {{ old('tipe_batch') == 'Intern 1' ? 'selected' : '' }}>Intern 1</option>
                        <option value="Intern 2" {{ old('tipe_batch') == 'Intern 2' ? 'selected' : '' }}>Intern 2</option>
                        <option value="Fungsio" {{ old('tipe_batch') == 'Fungsio' ? 'selected' : '' }}>Fungsio</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-3 py-2 border">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-3 py-2 border">
                </div>

                <div class="mt-6 pt-4 border-t border-gray-200">
                    <h4 class="text-sm font-bold text-gray-800 mb-3">Syarat Administrasi Pendaftaran</h4>
                    
                    <div class="flex items-center mb-5 bg-blue-50 p-3 rounded border border-blue-100">
                        <input type="checkbox" name="requires_cv" id="requires_cv" value="1" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-5 h-5">
                        <label for="requires_cv" class="ml-3 text-sm text-blue-900 font-bold">Wajibkan pelamar melampirkan Jadwal Hidup / CV</label>
                    </div>

                    <p class="text-xs text-gray-600 mb-2 font-bold uppercase tracking-wider">Tandai divisi yang Mewajibkan Portofolio:</p>
                    <div class="grid grid-cols-2 gap-2 mb-4">
                        @isset($departments)
                            @foreach($departments as $dept)
                                <label class="flex items-start space-x-2 bg-white border border-gray-200 px-2 py-2 rounded cursor-pointer hover:bg-purple-50 hover:border-purple-200 transition-all shadow-sm">
                                    <input type="checkbox" name="portfolio_departments[]" value="{{ $dept->id }}" {{ $dept->requires_portfolio ? 'checked' : '' }} class="mt-0.5 rounded border-gray-300 text-purple-600 focus:ring-purple-500 w-3.5 h-3.5">
                                    <span class="text-[11px] text-gray-700 font-bold leading-tight">{{ $dept->name }}</span>
                                </label>
                            @endforeach
                        @else
                            <p class="text-xs text-red-500 italic">Data departemen belum dikirim dari Controller.</p>
                        @endisset
                    </div>
                </div>

                <button type="submit" class="w-full mt-4 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition-colors text-sm">
                    Simpan Batch Baru
                </button>
            </form>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Batch</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe/Jabatan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($batches as $batch)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $batch->nama_batch }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 bg-slate-100 text-slate-700 text-xs font-bold rounded border border-slate-200">
                                        {{ $batch->tipe_batch ?? 'Intern 1' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($batch->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($batch->tanggal_selesai)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($batch->is_active)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif (Dibuka)</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Ditutup</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium flex justify-center gap-2">
                                    
                                    <form action="{{ route('admin.batches.toggle', $batch->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-3 py-1.5 rounded-md border text-xs font-bold transition-colors {{ $batch->is_active ? 'border-red-200 bg-red-50 text-red-600 hover:bg-red-100' : 'border-green-200 bg-green-50 text-green-600 hover:bg-green-100' }}">
                                            {{ $batch->is_active ? 'Tutup Pendaftaran' : 'Buka Batch' }}
                                        </button>
                                    </form>

                                    @if(!$batch->is_active)
                                        <form action="{{ route('admin.batches.send_api', $batch->id) }}" method="POST" onsubmit="return confirm('Kirim data mahasiswa yang Diterima ke website utama Persma?')">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 rounded-md border border-purple-200 bg-purple-600 text-white hover:bg-purple-700 text-xs font-bold transition-colors shadow-sm flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                                Kirim API Kelulusan
                                            </button>
                                        </form>
                                    @endif
                                    
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">Belum ada data batch.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection