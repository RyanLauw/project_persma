home.blade.php
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Recruitment Persma PCU</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <style>
        .swiper {
            width: 100%;
            padding: 40px 0px 60px 0px; 
        }
        .swiper-slide {
            width: 280px; /* Lebar spesifik untuk efek Coverflow */
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .swiper-button-next,
        .swiper-button-prev {
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(4px);
            border-radius: 999px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            color: #1e3a8a;
            z-index: 50 !important; 
            transition: all 0.3s ease;
        }
        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            background: #1e3a8a;
            color: white;
            transform: scale(1.1);
        }
        .swiper-button-next:after,
        .swiper-button-prev:after {
            font-size: 18px;
            font-weight: 900;
        }
        .swiper-pagination-bullet {
            background: #cbd5e1;
            opacity: 1;
            margin: 0 6px !important;
        }
        .swiper-pagination-bullet-active {
            background: #1e3a8a;
            opacity: 1;
            width: 30px;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        .dept-card {
            background: linear-gradient(135deg, #1e3a8a 0%, #6d28d9 100%);
            border-radius: 2rem;
            padding: 2.5rem 1.5rem;
            width: 100%;
            height: 340px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            box-shadow: 0 20px 40px -10px rgba(109, 40, 217, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .dept-card img {
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.2));
            transition: transform 0.4s ease;
        }
        .swiper-slide-active .dept-card img {
            transform: scale(1.1) translateY(-10px);
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 font-sans antialiased pb-0 overflow-x-hidden">

    <nav class="fixed top-0 w-full bg-white/80 backdrop-blur-md shadow-sm z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center gap-3 cursor-pointer" onclick="window.scrollTo(0,0)">
                    <img src="{{ asset('assets/logo_text.png') }}" alt="Genta Logo" class="h-10 md:h-12 w-auto object-contain">
                </div>
                
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="#" class="text-sm font-bold text-gray-600 hover:text-blue-700 transition">Beranda</a>
                    <a href="#departments" class="text-sm font-bold text-gray-600 hover:text-blue-700 transition">Divisi</a>
                    <a href="#contact" class="text-sm font-bold text-gray-600 hover:text-blue-700 transition">Kontak</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="w-full pt-28 flex flex-col justify-center items-center">
        <div class="text-center w-full max-w-7xl mx-auto px-4">
            <img src="{{ asset('assets/foto-persma.jpeg') }}" class="w-full rounded-3xl shadow-2xl object-cover h-[400px]" alt="Banner Persma" />
        </div>
    </div>
    
    @php 
        // Mengecek apakah ada batch pendaftaran yang sedang aktif
        $isOprecOpen = \App\Models\Batch::where('is_active', true)->exists();
    @endphp

    <div class="w-full flex flex-col justify-center items-center mt-12 px-4">
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative mb-6 max-w-2xl w-full text-center" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="flex justify-center w-full max-w-2xl">
            @auth
                <div class="flex flex-col items-center gap-3 bg-white p-8 rounded-3xl shadow-sm border border-gray-100 w-full relative overflow-hidden">
                    <div class="absolute top-0 w-full h-2 bg-blue-600"></div>
                    <p class="text-sm font-medium text-gray-500 mt-2">Masuk sebagai: <span class="font-bold text-gray-800">{{ auth()->user()->email }}</span></p>
                    
                    @if(auth()->user()->role === 'applicant')
                        @if($isOprecOpen)
                            <a href="{{ route('register.continue') }}" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-8 rounded-full shadow-lg transition-transform transform hover:scale-105 w-full text-center md:w-auto">
                                Lanjutkan Pendaftaran &raquo;
                            </a>
                        @else
                            <button disabled class="mt-4 bg-gray-200 cursor-not-allowed text-gray-500 font-bold py-3.5 px-8 rounded-full shadow-inner w-full text-center md:w-auto">
                                🔒 Pendaftaran Sedang Ditutup
                            </button>
                        @endif
                    @else
                        <a href="{{ route('admin.dashboard') }}" class="mt-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-8 rounded-full shadow-lg transition-transform transform hover:scale-105 w-full text-center md:w-auto">
                            Masuk ke Dashboard Admin &raquo;
                        </a>
                    @endif
                    
                    <form action="{{ route('logout') }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="text-sm text-red-500 hover:text-red-700 hover:underline font-bold transition-colors">Keluar Akun</button>
                    </form>
                </div>
            @else
                @if($isOprecOpen)
                    <a href="{{ route('auth.google') }}" class="bg-white border-2 border-gray-100 hover:border-blue-500 hover:bg-blue-50 text-gray-700 font-bold py-4 px-8 rounded-full shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-4 w-full md:w-auto text-lg transform hover:-translate-y-1">
                        <svg class="w-7 h-7" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Daftar dengan Email Petra
                    </a>
                @else
                    <button disabled class="bg-gray-100 border-2 border-gray-200 text-gray-400 font-bold py-4 px-8 rounded-full shadow-sm flex items-center justify-center gap-3 w-full md:w-auto text-lg cursor-not-allowed">
                        <span class="text-xl">🔒</span> Pendaftaran Sedang Ditutup
                    </button>
                @endif
            @endauth
        </div>
    </div>

    <div id="departments" class="pt-24 flex flex-col justify-center items-center px-0 overflow-hidden w-full">
        <div class="text-center w-full">
            <h1 class="text-3xl md:text-5xl font-black text-blue-900 mb-2 tracking-tight">
                Pilihan Divisi
            </h1>
            <p class="text-gray-500 mt-2">Geser untuk melihat semua divisi yang tersedia.</p>

            <div class="max-w-[1400px] mx-auto py-6 w-full relative px-4">
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        
                        <div class="swiper-slide">
                            <div class="dept-card">
                                <div class="bg-white/20 p-5 rounded-full mb-6 backdrop-blur-sm border border-white/30">
                                    <img src="{{ asset('assets/CC.png') }}" class="w-20 h-20 object-contain">
                                </div>
                                <h5 class="text-2xl font-extrabold text-center tracking-wide">Konten<br/>Kreatif</h5>
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <div class="dept-card">
                                <div class="bg-white/20 p-5 rounded-full mb-6 backdrop-blur-sm border border-white/30">
                                    <img src="{{ asset('assets/FOTON.png') }}" class="w-20 h-20 object-contain">
                                </div>
                                <h5 class="text-2xl font-extrabold text-center tracking-wide">Fotografi</h5>
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <div class="dept-card">
                                <div class="bg-white/20 p-5 rounded-full mb-6 backdrop-blur-sm border border-white/30">
                                    <img src="{{ asset('assets/IT.png') }}" class="w-20 h-20 object-contain">
                                </div>
                                <h5 class="text-2xl font-extrabold text-center tracking-wide">Teknologi<br/>Informasi</h5>
                            </div>
                        </div>
                        
                        <div class="swiper-slide">
                            <div class="dept-card">
                                <div class="bg-white/20 p-5 rounded-full mb-6 backdrop-blur-sm border border-white/30">
                                    <img src="{{ asset('assets/LAYOUT.png') }}" class="w-20 h-20 object-contain">
                                </div>
                                <h5 class="text-2xl font-extrabold text-center tracking-wide">Ilustrasi &<br/>Tata Letak</h5>
                            </div>
                        </div>
                        
                        <div class="swiper-slide">
                            <div class="dept-card">
                                <div class="bg-white/20 p-5 rounded-full mb-6 backdrop-blur-sm border border-white/30">
                                    <img src="{{ asset('assets/REPCET.png') }}" class="w-20 h-20 object-contain">
                                </div>
                                <h5 class="text-2xl font-extrabold text-center tracking-wide">Reportase<br/>Cetak</h5>
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <div class="dept-card">
                                <div class="bg-white/20 p-5 rounded-full mb-6 backdrop-blur-sm border border-white/30">
                                    <img src="{{ asset('assets/REPON.png') }}" class="w-20 h-20 object-contain">
                                </div>
                                <h5 class="text-2xl font-extrabold text-center tracking-wide">Reportase<br/>Daring</h5>
                            </div>
                        </div>
                        
                        <div class="swiper-slide">
                            <div class="dept-card">
                                <div class="bg-white/20 p-5 rounded-full mb-6 backdrop-blur-sm border border-white/30">
                                    <img src="{{ asset('assets/RND.png') }}" class="w-20 h-20 object-contain">
                                </div>
                                <h5 class="text-2xl font-extrabold text-center tracking-wide">Riset &<br/>Pengembangan</h5>
                            </div>
                        </div>
                        
                        <div class="swiper-slide">
                            <div class="dept-card">
                                <div class="bg-white/20 p-5 rounded-full mb-6 backdrop-blur-sm border border-white/30">
                                    <img src="{{ asset('assets/SPONSOR.png') }}" class="w-20 h-20 object-contain">
                                </div>
                                <h5 class="text-2xl font-extrabold text-center tracking-wide">Kewirausahaan</h5>
                            </div>
                        </div>
                        
                        <div class="swiper-slide">
                            <div class="dept-card">
                                <div class="bg-white/20 p-5 rounded-full mb-6 backdrop-blur-sm border border-white/30">
                                    <img src="{{ asset('assets/BPH.png') }}" class="w-20 h-20 object-contain">
                                </div>
                                <h5 class="text-2xl font-extrabold text-center tracking-wide">BPH</h5>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-button-prev hidden md:flex !left-4 lg:!left-10"></div>
                    <div class="swiper-button-next hidden md:flex !right-4 lg:!right-10"></div>
                    <div class="swiper-pagination !-bottom-2"></div>
                </div>
            </div>
        </div>
    </div>

    <div id="contact" class="pt-24 pb-10 flex flex-col justify-center items-center px-4">
        <div class="text-center w-full max-w-6xl mx-auto">
            <h1 class="text-3xl md:text-5xl font-extrabold text-blue-900 mb-4 tracking-tight">
                Hubungi Kami 
            </h1>
            <p class="text-base text-gray-500 mb-12">
                Kami siap menerima kritik, saran, maupun pertanyaan Anda.
            </p>

            <div class="grid sm:grid-cols-1 md:grid-cols-3 gap-6 w-full mx-auto px-2 max-w-5xl">
                
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition-shadow flex flex-col items-center text-center h-full">
                    <img src="{{ asset('assets/logo-mail.jpeg') }}" class="w-16 h-16 mb-4 rounded-full" alt="Email">
                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-2">Email</h4>
                    <p class="text-lg font-bold text-blue-900 mb-6">persma@genta.ac.id</p>
                    <a href="mailto:persma@genta.ac.id" class="mt-auto px-8 py-3 bg-blue-50 text-blue-700 rounded-full text-sm font-bold hover:bg-blue-700 hover:text-white transition w-full">
                        Kirim Pesan
                    </a>
                </div>

                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition-shadow flex flex-col items-center text-center h-full">
                    <img src="{{ asset('assets/logo-insta.jpeg') }}" class="w-16 h-16 mb-4 rounded-full" alt="Instagram">
                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-2">Instagram</h4>
                    <p class="text-lg font-bold text-blue-900 mb-6">@genta.pcu</p>
                    <a href="https://instagram.com/genta.pcu" target="_blank" class="mt-auto px-8 py-3 bg-blue-50 text-blue-700 rounded-full text-sm font-bold hover:bg-blue-700 hover:text-white transition w-full">
                        Follow Kami
                    </a>
                </div>

                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition-shadow flex flex-col items-center text-center h-full">
                    <img src="{{ asset('assets/logo-line.jpeg') }}" class="w-16 h-16 mb-4 rounded-full" alt="Line">
                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-2">OA LINE</h4>
                    <p class="text-lg font-bold text-blue-900 mb-6">@tdy8464u</p>
                    <a href="https://line.me/R/ti/p/@tdy8464u" target="_blank" class="mt-auto px-8 py-3 bg-blue-50 text-blue-700 rounded-full text-sm font-bold hover:bg-blue-700 hover:text-white transition w-full">
                        Tambah Teman
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-12 flex flex-col justify-center items-center bg-gradient-to-br from-blue-900 to-indigo-900 p-12 rounded-t-[3rem] w-full shadow-2xl relative overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mx-auto w-full max-w-5xl relative z-10">
            <div class="flex flex-col items-center md:items-start text-center md:text-left">
                <img src="{{ asset('assets/logo_text.png') }}" class="w-48 mb-6 bg-white p-3 rounded-2xl" alt="Logo" />
                <p class="text-base text-blue-100 font-medium">Pers Mahasiswa GENTA</p>
                <p class="text-base text-blue-100 font-medium">Universitas Kristen Petra</p>
            </div>
            
            <div class="flex flex-col items-center md:items-end">
                <h5 class="text-lg font-bold text-white mb-6">Ikuti Perjalanan Kami</h5>
                <div class="flex flex-row gap-4">
                    <a href="#" class="w-14 h-14 bg-white/10 hover:bg-white/30 backdrop-blur-md transition-all rounded-full flex items-center justify-center text-2xl transform hover:-translate-y-1">🌐</a>
                    <a href="#" class="w-14 h-14 bg-white/10 hover:bg-white/30 backdrop-blur-md transition-all rounded-full flex items-center justify-center text-2xl transform hover:-translate-y-1">📷</a>
                    <a href="#" class="w-14 h-14 bg-white/10 hover:bg-white/30 backdrop-blur-md transition-all rounded-full flex items-center justify-center text-2xl transform hover:-translate-y-1">✉️</a>
                </div>
            </div>
        </div>
        
        <div class="text-sm text-blue-200/60 font-medium border-t border-white/10 w-full text-center pt-8 mt-12 relative z-10">
            © 2026 Pers Mahasiswa GENTA. Hak Cipta Dilindungi.
        </div>
    </div>

<script>
    new Swiper(".mySwiper", {
        effect: "coverflow",
        grabCursor: true,
        centeredSlides: true, 
        slidesPerView: "auto",
        loop: true,
        coverflowEffect: {
            rotate: 15,
            stretch: 0,
            depth: 200,
            modifier: 1,
            slideShadows: false,
        },
        autoplay: {
            delay: 2500,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
            dynamicBullets: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev"
        }
    });
</script>
</body>
</html>