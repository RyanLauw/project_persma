@extends('admin.layout')

@section('header_title', 'Data Pelamar per Divisi')

@section('content')

<div class="space-y-8">
    @foreach($departments as $dept)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-slate-800 px-6 py-4 flex justify-between items-center">
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">{{ $dept->name }}</h3>
                <span class="bg-white text-slate-800 text-xs font-bold px-3 py-1 rounded-full">
                    {{ $dept->applicants->count() }} Pelamar
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama & Kontak</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Prioritas Pilihan</th>
                            
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Status Progres</th>
                            
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Status di Divisi Ini</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($dept->applicants as $applicant)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $applicant->name }}</div>
                                    @if($applicant->profile)
                                        <div class="text-sm text-gray-600">{{ $applicant->profile->nrp }} - {{ $applicant->profile->program_studi }}</div>
                                        <div class="text-xs text-gray-400 mt-1">WA: {{ $applicant->profile->no_telepon }}</div>
                                    @else
                                        <span class="text-yellow-500 text-xs italic">Data diri belum lengkap</span>
                                    @endif
                                </td>
                                
                                <td class="px-6 py-4 text-center">
                                    @if($applicant->pivot->choice_order == 1)
                                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded font-bold">Pilihan 1</span>
                                    @else
                                        <span class="inline-block bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded font-bold">Pilihan 2</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @php
                                        $progress = $applicant->profile->progress_step ?? 1;
                                    @endphp
                                    
                                    @if($progress == 6)
                                        <span class="inline-block bg-green-100 text-green-800 text-[11px] px-2 py-1 rounded font-bold border border-green-200">Final (Selesai)</span>
                                    @else
                                        <span class="inline-block bg-orange-100 text-orange-800 text-[11px] px-2 py-1 rounded font-bold border border-orange-200">Draf: Step {{ $progress }}</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($applicant->pivot->status === 'pending')
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">Pending</span>
                                    @elseif($applicant->pivot->status === 'accepted')
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">Diterima</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">Ditolak</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    @php
                                        $isDraft = ($applicant->profile->progress_step ?? 1) < 6;
                                    @endphp
                                    
                                    @if($isDraft)
                                        <a href="{{ route('admin.applicants.show', $applicant->id) }}" class="inline-block border border-orange-300 bg-orange-50 text-orange-600 hover:bg-orange-100 px-4 py-2 rounded-md transition-colors text-xs font-semibold">
                                            Intip Draf &rarr;
                                        </a>
                                    @else
                                        <a href="{{ route('admin.applicants.show', $applicant->id) }}" class="inline-block border border-slate-300 hover:bg-slate-800 hover:text-white text-slate-700 px-4 py-2 rounded-md transition-colors text-xs font-semibold">
                                            Lihat Detail & Nilai &rarr;
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    Belum ada pelamar yang memilih divisi ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>
@endsection