@extends('layouts.master', ['step' => 3])

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Jadwal Wawancara</h2>
    <p class="text-sm text-gray-500 mt-1">Pilih slot waktu wawancara untuk departemen yang kamu pilih. Pastikan jadwal tidak bentrok.</p>
</div>

<form action="{{ route('register.step3') }}" method="POST" class="space-y-8">
    @csrf

    <div>
        <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Jadwal Wawancara - Pilihan Pertama <span class="text-red-500">*</span></h3>
        
        @if($schedulesChoice1->isEmpty())
            <div class="p-4 bg-yellow-50 text-yellow-700 rounded-lg border border-yellow-200 text-sm">
                Belum ada jadwal wawancara yang tersedia untuk departemen ini. Silakan hubungi panitia.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($schedulesChoice1 as $schedule)
                    @php
                        // Cek apakah jadwal sudah diambil oleh pendaftar lain
                        $isFull = $schedule->applicants->where('id', '!=', auth()->id())->count() >= 1;
                    @endphp
                    
                    <label class="{{ $isFull ? 'cursor-not-allowed opacity-60' : 'cursor-pointer' }} relative block">
                        <input type="radio" name="jadwal_pilihan_1" value="{{ $schedule->id }}" 
                            {{ $isFull ? 'disabled' : 'required' }}
                            class="peer sr-only"
                            {{ old('jadwal_pilihan_1', $data['jadwal_pilihan_1'] ?? '') == $schedule->id ? 'checked' : '' }}>
                        
                        <div class="rounded-lg border border-gray-300 p-4 transition-all flex flex-col justify-between h-full
                                    {{ $isFull ? 'bg-gray-100' : 'bg-white hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:ring-1 peer-checked:ring-blue-500' }}">
                            
                            <div>
                                <div class="flex justify-between items-start mb-2">
                                    <div class="font-bold text-base {{ $isFull ? 'text-gray-500 line-through' : 'text-gray-900' }}">
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->translatedFormat('l, d F Y') }}
                                    </div>
                                    
                                    @if($isFull)
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold bg-red-100 text-red-800 shrink-0">
                                            Sudah Dibooking
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold shrink-0 uppercase tracking-wider {{ $schedule->tipe == 'online' ? 'bg-green-100 text-green-800' : 'bg-indigo-100 text-indigo-800' }}">
                                            {{ $schedule->tipe }}
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="text-sm text-gray-600 font-semibold mb-3">
                                    ⏰ {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} WIB
                                </div>
                            </div>

                            <div class="border-t border-gray-100 pt-3 mt-auto space-y-1.5 text-xs text-gray-500">
                                <div class="flex items-start gap-1.5">
                                    <span class="text-base leading-none">🗣️</span>
                                    <div>
                                        <span class="font-bold text-gray-400 uppercase tracking-wider block text-[9px] mb-0.5">Pewawancara</span>
                                        <span class="font-medium text-gray-700">{{ $schedule->pewawancara ?: 'Belum ditentukan' }}</span>
                                    </div>
                                </div>
                                <div class="flex items-start gap-1.5 pt-1">
                                    <span class="text-base leading-none">📍</span>
                                    <div class="flex-1">
                                        <span class="font-bold text-gray-400 uppercase tracking-wider block text-[9px] mb-0.5">Lokasi / Tautan</span>
                                        <span class="font-medium text-gray-700 break-words">{{ $schedule->lokasi ?: '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>
            @error('jadwal_pilihan_1') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror
        @endif
    </div>

    @if($schedulesChoice2->isNotEmpty())
        <div>
            <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Jadwal Wawancara - Pilihan Kedua <span class="text-red-500">*</span></h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($schedulesChoice2 as $schedule)
                    @php
                        // Cek apakah jadwal sudah diambil oleh pendaftar lain
                        $isFull = $schedule->applicants->where('id', '!=', auth()->id())->count() >= 1;
                    @endphp
                    
                    <label class="{{ $isFull ? 'cursor-not-allowed opacity-60' : 'cursor-pointer' }} relative block">
                        <input type="radio" name="jadwal_pilihan_2" value="{{ $schedule->id }}" 
                            {{ $isFull ? 'disabled' : 'required' }}
                            class="peer sr-only"
                            {{ old('jadwal_pilihan_2', $data['jadwal_pilihan_2'] ?? '') == $schedule->id ? 'checked' : '' }}>
                        
                        <div class="rounded-lg border border-gray-300 p-4 transition-all 
                                    {{ $isFull ? 'bg-gray-100' : 'bg-white hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:ring-1 peer-checked:ring-blue-500' }}">
                            
                            <div class="flex justify-between items-start mb-2">
                                <div class="font-medium {{ $isFull ? 'text-gray-500 line-through' : 'text-gray-900' }}">
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->translatedFormat('l, d F Y') }}
                                </div>
                                
                                @if($isFull)
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold bg-red-100 text-red-800">
                                        Sudah Dibooking
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $schedule->type == 'online' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ ucfirst($schedule->type) }}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="text-sm text-gray-500 mb-1">
                                ⏰ {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} WIB
                            </div>
                            <div class="text-sm text-gray-500 flex items-center">
                                📍 {{ $schedule->location }}
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>
            @error('jadwal_pilihan_2') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror
        </div>
    @endif

    <div class="flex justify-between pt-4 border-t border-gray-200">
        <a href="{{ route('register.step2') }}" class="inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-6 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
            &laquo; Kembali
        </a>
        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
            Selanjutnya &raquo;
        </button>
    </div>
</form>
@endsection