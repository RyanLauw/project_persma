@extends('layouts.master', ['step' => 4])

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Kontak Departemen</h2>
    <p class="text-sm text-gray-500 mt-1">Harap catat atau simpan kontak di bawah ini. Hubungi contact person terkait jika kamu memiliki pertanyaan seputar jadwal wawancaramu.</p>
</div>

<form action="{{ route('register.step4') }}" method="POST" class="space-y-6">
    @csrf

    <div class="space-y-6">
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-lg mr-4">
                    1
                </div>
                <h3 class="text-xl font-semibold text-gray-800">{{ $dept1->name }}</h3>
            </div>
            
            <div class="ml-14 bg-white p-4 rounded-lg border border-blue-100 shadow-sm">
                <p class="text-sm text-gray-600 mb-2 font-medium">Contact Person (CP):</p>
                
                @if($dept1->contact_wa)
                    <div class="text-gray-800 text-sm leading-relaxed space-y-1">
                        <p><span class="font-medium text-gray-500">Nama:</span> <span class="font-bold text-gray-900">{{ $dept1->contact_name ?? '-' }}</span></p>
                        <p><span class="font-medium text-gray-500">WhatsApp:</span> <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $dept1->contact_wa)) }}" target="_blank" class="font-bold text-blue-600 hover:underline">{{ $dept1->contact_wa }} ↗</a></p>
                    </div>
                @else
                    <p class="text-sm text-gray-500 italic">Informasi kontak belum tersedia.</p>
                @endif
            </div>
        </div>

        @if($dept2)
            <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 font-bold text-lg mr-4">
                        2
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">{{ $dept2->name }}</h3>
                </div>
                
                <div class="ml-14 bg-white p-4 rounded-lg border border-green-100 shadow-sm">
                    <p class="text-sm text-gray-600 mb-2 font-medium">Contact Person (CP):</p>
                    
                    @if($dept2->contact_wa)
                        <div class="text-gray-800 text-sm leading-relaxed space-y-1">
                            <p><span class="font-medium text-gray-500">Nama:</span> <span class="font-bold text-gray-900">{{ $dept2->contact_name ?? '-' }}</span></p>
                            <p><span class="font-medium text-gray-500">WhatsApp:</span> <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $dept2->contact_wa)) }}" target="_blank" class="font-bold text-green-600 hover:underline">{{ $dept2->contact_wa }} ↗</a></p>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 italic">Informasi kontak belum tersedia.</p>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <div class="mt-8 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-md">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    Pastikan kamu sudah mencatat kontak di atas. Di tahap selanjutnya, kamu akan mereview semua data dan mengirimkan formulir pendaftaran.
                </p>
            </div>
        </div>
    </div>

    <div class="flex justify-between pt-6 border-t border-gray-200 mt-8">
        <a href="{{ route('register.step3') }}" class="inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-6 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
            &laquo; Kembali
        </a>
        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
            Lanjut ke Konfirmasi &raquo;
        </button>
    </div>
</form>
@endsection