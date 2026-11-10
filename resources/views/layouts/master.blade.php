<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran | Persma PCU</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-gray-800 font-sans antialiased min-h-screen flex flex-col selection:bg-blue-200">

    <header class="w-full max-w-5xl mx-auto px-4 py-6 flex justify-between items-center">
        <a href="{{ url('/') }}" class="group inline-flex items-center gap-2 bg-white border border-gray-200 hover:border-blue-300 hover:bg-blue-50 text-gray-600 hover:text-blue-700 px-5 py-2.5 rounded-full text-sm font-bold shadow-sm transition-all transform hover:-translate-x-1">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Beranda
        </a>
        
        <div class="hidden sm:flex items-center gap-3">
            <img src="{{ asset('assets/logo_text.png') }}" alt="Logo Persma" class="h-8 md:h-10 object-contain">
        </div>
    </header>

    <main class="flex-grow flex items-start justify-center py-6 px-4 sm:px-6">
        <div class="w-full max-w-4xl bg-white shadow-2xl shadow-blue-900/5 rounded-3xl overflow-hidden border border-gray-100">
            
            <div class="bg-gradient-to-b from-blue-50/50 to-white px-6 sm:px-12 py-8 border-b border-gray-100">
                <div class="relative">
                    @php
                        // Menentukan step aktif berdasarkan URL atau variabel dari controller
                        $currentStep = $step ?? 1;
                        
                        // Menghitung persentase lebar garis biru (0%, 25%, 50%, 75%, 100%)
                        $progressWidth = (($currentStep - 1) / 4) * 100;
                    @endphp

                    <div class="absolute top-5 left-5 right-5 transform -translate-y-1/2 h-1.5 bg-gray-200 rounded-full z-0">
                        <div class="h-full bg-blue-600 rounded-full transition-all duration-700 ease-out" style="width: {{ $progressWidth }}%;"></div>
                    </div>
                    
                    <div class="relative z-10 flex justify-between items-center w-full">
                        @for ($i = 1; $i <= 5; $i++)
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-500 shadow-sm
                                    {{ $currentStep > $i ? 'bg-blue-600 text-white' : ($currentStep == $i ? 'bg-blue-600 text-white ring-4 ring-blue-100 scale-110' : 'bg-white border-2 border-gray-300 text-gray-400') }}">
                                    
                                    @if($currentStep > $i)
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    @else
                                        {{ $i }}
                                    @endif
                                </div>
                                
                                <span class="hidden sm:block text-[11px] mt-3 font-extrabold uppercase tracking-wider {{ $currentStep >= $i ? 'text-blue-700' : 'text-gray-400' }}">
                                    @if($i == 1) Data Diri 
                                    @elseif($i == 2) Divisi
                                    @elseif($i == 3) Jadwal
                                    @elseif($i == 4) Kontak
                                    @else Selesai
                                    @endif
                                </span>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <div class="p-6 sm:p-10">
                @yield('content')
            </div>

        </div>
    </main>

    <footer class="mt-auto pb-6 pt-4">
        <div class="max-w-5xl mx-auto px-4 text-center">
            <p class="text-xs font-medium text-gray-400">
                &copy; {{ date('Y') }} Pers Mahasiswa Universitas Kristen Petra. All rights reserved.
            </p>
        </div>
    </footer>

</body>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            text: '{!! session("error") !!}',
            confirmButtonText: 'Saya Mengerti',
            buttonsStyling: false,
            background: '#ffffff',
            backdrop: `rgba(0,0,0,0.5) backdrop-blur-sm`,
            customClass: {
                popup: 'rounded-3xl shadow-2xl border border-gray-100 p-6',
                title: 'text-2xl font-bold text-gray-800 mt-2',
                htmlContainer: 'text-gray-600 text-sm mt-4',
                confirmButton: 'bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-8 rounded-full shadow-md transition-transform transform hover:scale-105 mt-6 w-full md:w-auto',
                icon: 'border-red-500 text-red-500'
            }
        });
    </script>
@endif

@if($errors->any())
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Cek Kembali Data Anda!',
            html: `
                <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 mt-2">
                    <ul class="text-left text-sm space-y-2 ml-4 list-disc text-gray-600 font-medium">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            `,
            confirmButtonText: 'Perbaiki Data',
            buttonsStyling: false,
            backdrop: `rgba(0,0,0,0.5) backdrop-blur-sm`,
            customClass: {
                popup: 'rounded-3xl shadow-2xl border border-gray-100 p-6',
                title: 'text-2xl font-bold text-gray-800 mt-2',
                confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-full shadow-md transition-transform transform hover:scale-105 mt-6 w-full md:w-auto',
            }
        });
    </script>
@endif
</html>