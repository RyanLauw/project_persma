@extends('admin.layout')

@php
    // Mengecek apakah pelamar ini sudah punya status 'accepted' di divisi mana pun
    $hasBeenAccepted = $applicant->appliedDepartments->where('pivot.status', 'accepted')->count() > 0;
    
    // Mengecek apakah pelamar masih dalam tahap draf
    $isDraft = ($applicant->profile->progress_step ?? 1) < 6;
@endphp

@section('header_title', 'Detail Pelamar: ' . $applicant->name)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.applicants.index') }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm flex items-center gap-1">
        &larr; Kembali ke Daftar Pelamar
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Data Diri & Dokumen</h3>
            
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-500 font-medium">Nama Lengkap</p>
                    <p class="text-gray-900 font-semibold">{{ $applicant->name }}</p>
                </div>
                <div>
                    <p class="text-gray-500 font-medium">Email SSO</p>
                    <p class="text-gray-900">{{ $applicant->email }}</p>
                </div>
                
                @if($applicant->profile)
                    <div>
                        <p class="text-gray-500 font-medium">NRP</p>
                        <p class="text-gray-900">{{ $applicant->profile->nrp }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 font-medium">Program Studi</p>
                        <p class="text-gray-900">{{ $applicant->profile->program_studi }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 font-medium">IPK Terakhir</p>
                        <p class="text-gray-900 font-bold text-blue-700">{{ $applicant->profile->ipk }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 font-medium">No. WhatsApp</p>
                        <p class="text-gray-900">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $applicant->profile->no_telepon) }}" target="_blank" class="text-green-600 hover:underline">
                                {{ $applicant->profile->no_telepon }} ↗
                            </a>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500 font-medium">Line ID</p>
                        <p class="text-gray-900">{{ $applicant->profile->id_line ?: '-' }}</p>
                    </div>


                    <div class="pt-4 border-t mt-4 space-y-3">
                        <p class="text-gray-800 font-bold mb-2">Lampiran Dokumen</p>
                        
                        <div class="flex justify-between items-center bg-gray-50 p-2 rounded border border-gray-100">
                            <span class="text-xs text-gray-600 font-medium">Jadwal Hidup / CV</span>
                            @if($applicant->profile->jadwal_hidup_path)
                                <a href="{{ route('admin.document.view', ['path' => $applicant->profile->jadwal_hidup_path]) }}" target="_blank" class="text-blue-600 hover:underline text-xs font-bold">Buka ↗</a>
                            @else <span class="text-red-400 text-xs italic">Kosong</span> @endif
                        </div>
                        
                        <div class="flex justify-between items-center bg-gray-50 p-2 rounded border border-gray-100">
                            <span class="text-xs text-gray-600 font-medium">Scan KTM</span>
                            @if($applicant->profile->ktm_path)
                                <a href="{{ route('admin.document.view', ['path' => $applicant->profile->ktm_path]) }}" target="_blank" class="text-blue-600 hover:underline text-xs font-bold">Buka ↗</a>
                            @else <span class="text-red-400 text-xs italic">Kosong</span> @endif
                        </div>
                        
                        <div class="flex justify-between items-center bg-gray-50 p-2 rounded border border-gray-100">
                            <span class="text-xs text-gray-600 font-medium">Pas Foto 3x4</span>
                            @if($applicant->profile->pas_foto_path)
                                <a href="{{ route('admin.document.view', ['path' => $applicant->profile->pas_foto_path]) }}" target="_blank" class="text-blue-600 hover:underline text-xs font-bold">Buka ↗</a>
                            @else <span class="text-red-400 text-xs italic">Kosong</span> @endif
                        </div>

                        <div class="flex justify-between items-center bg-gray-50 p-2 rounded border border-gray-100">
                            <span class="text-xs text-gray-600 font-medium">Transkrip KHS</span>
                            @if($applicant->profile->transkrip_path)
                                <a href="{{ route('admin.document.view', ['path' => $applicant->profile->transkrip_path]) }}" target="_blank" class="text-blue-600 hover:underline text-xs font-bold">Buka ↗</a>
                            @else <span class="text-red-400 text-xs italic">Kosong</span> @endif
                        </div>

                        <div class="flex justify-between items-center bg-gray-50 p-2 rounded border border-gray-100">
                            <span class="text-xs text-gray-600 font-medium">Bukti SKKK</span>
                            @if($applicant->profile->skkk_path)
                                <a href="{{ route('admin.document.view', ['path' => $applicant->profile->skkk_path]) }}" target="_blank" class="text-blue-600 hover:underline text-xs font-bold">Buka ↗</a>
                            @else <span class="text-red-400 text-xs italic">Kosong</span> @endif
                        </div>
                    </div>
                @else
                    <div class="p-3 bg-yellow-50 text-yellow-700 rounded-md text-xs">
                        Pelamar ini belum melengkapi form tahap 1 (Data Diri).
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-6">
        
        @if($isDraft)
            <div class="bg-orange-50 border-l-4 border-orange-400 p-4 rounded shadow-sm">
                <div class="flex items-start">
                    <span class="text-orange-500 text-xl mr-3">⚠️</span>
                    <div>
                        <p class="text-sm text-orange-800 font-bold">Pendaftaran Masih Berstatus Draf (Step {{ $applicant->profile->progress_step ?? 1 }})</p>
                        <p class="text-xs text-orange-600 mt-1">Pelamar belum melakukan Final Submit. Tombol penilaian dan eksekusi dikunci untuk sementara.</p>
                    </div>
                </div>
            </div>
        @endif

        @foreach($applicant->appliedDepartments as $dept)
            @if($admin->role === 'bph' || $admin->department_id === $dept->id)
                
                <div class="bg-white rounded-xl shadow-sm border {{ $dept->pivot->choice_order == 1 ? 'border-blue-200' : 'border-gray-200' }} overflow-hidden">
                    
                    <div class="{{ $dept->pivot->choice_order == 1 ? 'bg-blue-50' : 'bg-gray-50' }} px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider {{ $dept->pivot->choice_order == 1 ? 'text-blue-600' : 'text-gray-500' }}">
                                Pilihan {{ $dept->pivot->choice_order }}
                            </span>
                            <h4 class="text-lg font-bold text-gray-800">{{ $dept->name }}</h4>
                        </div>
                        
                        <div>
                            @if($dept->pivot->status === 'pending')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">PENDING</span>
                            @elseif($dept->pivot->status === 'accepted')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">DITERIMA</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">DITOLAK</span>
                            @endif
                        </div>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="md:col-span-1">
                            
                            @if($admin->role === 'kwk')
                                <form action="{{ route('admin.applicants.notes', ['id' => $applicant->id, 'department_id' => $dept->id]) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Catatan Wawancara (KWK)</label>
                                    <textarea name="interview_notes" rows="5" {{ $isDraft ? 'disabled' : '' }} placeholder="Ketik hasil wawancara di sini..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm p-3 border mb-3 {{ $isDraft ? 'bg-gray-100 text-gray-400' : '' }}">{{ $dept->pivot->interview_notes }}</textarea>
                                    
                                    @if(!$isDraft)
                                        <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded text-xs font-bold transition-colors w-full">
                                            💾 Simpan Catatan
                                        </button>
                                    @endif
                                </form>
                            @else
                                <label class="block text-sm font-bold text-gray-700 mb-2">Catatan Wawancara dari KWK</label>
                                <div class="w-full rounded-md border border-gray-200 bg-gray-50 text-sm p-3 mb-3 min-h-[120px] whitespace-pre-wrap text-gray-700 italic">
                                    {{ $dept->pivot->interview_notes ?: 'Belum ada catatan wawancara yang diberikan oleh KWK divisi ini.' }}
                                </div>
                            @endif

                            <div class="mt-4 pt-4 border-t">
                                <p class="text-sm font-bold text-gray-700 mb-2">Portofolio Pelamar</p>
                                @if($dept->pivot->portfolio_path)
                                    <a href="{{ $dept->pivot->portfolio_path }}" target="_blank" class="inline-block bg-purple-50 hover:bg-purple-100 border border-purple-200 text-purple-700 px-3 py-2 rounded text-xs font-bold transition-colors">
                                        🔗 Buka Drive Portofolio ↗
                                    </a>
                                @else
                                    <span class="text-gray-400 text-xs italic">Tidak melampirkan portofolio URL</span>
                                @endif
                            </div>
                        </div>

                        <div class="md:col-span-1 bg-gray-50 p-4 rounded-lg border border-gray-200 flex flex-col justify-center">
                            @if($admin->role === 'bph')
                                <h5 class="text-sm font-bold text-gray-800 mb-3 text-center">Keputusan BPH</h5>
                                <p class="text-xs text-gray-500 text-center mb-4">Ubah status kelulusan pelamar untuk divisi ini.</p>
                                
                                @if($hasBeenAccepted && $dept->pivot->status !== 'accepted')
                                    <div class="text-center p-4 bg-gray-100 rounded-lg border border-gray-200 shadow-inner mt-2">
                                        <p class="text-xl mb-1">🔒</p>
                                        <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wide">Pilihan Dikunci</h5>
                                        <p class="text-[11px] text-gray-400 mt-1 leading-tight">Pelamar telah diterima di divisi lain. Pilihan ini otomatis ditolak.</p>
                                    </div>
                                @elseif($isDraft)
                                    <div class="text-center p-4 bg-gray-100 rounded-lg border border-gray-200 shadow-inner mt-2">
                                        <p class="text-xl mb-1">⏳</p>
                                        <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wide">Status Dikunci</h5>
                                        <p class="text-[11px] text-gray-400 mt-1 leading-tight">Menunggu pelamar menyelesaikan Final Submit.</p>
                                    </div>
                                @else
                                    <form action="{{ route('admin.applicants.status', ['id' => $applicant->id, 'department_id' => $dept->id]) }}" method="POST" class="flex flex-col gap-2">
                                        @csrf
                                        @method('PUT')
                                        
                                        <button type="submit" name="status" value="accepted" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-bold transition-colors">
                                            ✅ TERIMA DI DIVISI INI
                                        </button>
                                        
                                        <button type="submit" name="status" value="rejected" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-bold transition-colors">
                                            ❌ TOLAK
                                        </button>
                                        
                                        <button type="submit" name="status" value="pending" class="w-full bg-yellow-400 hover:bg-yellow-500 text-yellow-900 px-4 py-2 rounded text-sm font-bold transition-colors mt-2">
                                            ⏳ Kembalikan ke Pending
                                        </button>
                                    </form>
                                @endif
                            @else
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    <h5 class="text-sm font-bold text-gray-700">Menunggu Keputusan BPH</h5>
                                    <p class="text-xs text-gray-500 mt-1">Hanya BPH yang berhak mengeksekusi status akhir kelulusan pelamar.</p>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            @endif
        @endforeach

    </div>
</div>
@endsection