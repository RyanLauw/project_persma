<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Persma PCU</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased flex h-screen overflow-hidden">

    <aside class="w-64 bg-slate-800 text-white flex flex-col h-full">
        <div class="h-16 flex items-center justify-center border-b border-slate-700">
            <h1 class="text-xl font-bold tracking-wider">PERSMA <span class="text-blue-400">ADMIN</span></h1>
        </div>
        
        <nav class="flex-1 overflow-y-auto py-4">
            <ul class="space-y-1 px-3">
                
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white transition-colors' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('admin.applicants.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium {{ request()->routeIs('admin.applicants.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white transition-colors' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Data Pelamar
                    </a>
                </li>

                @if(auth()->user()->role === 'bph')
                    <li>
                        <a href="{{ route('admin.batches.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium {{ request()->routeIs('admin.batches.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white transition-colors' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Manajemen Batch
                        </a>
                    </li>
                @endif

                @if(auth()->user()->role === 'kwk')
                    <li>
                        <a href="{{ route('admin.departments.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium {{ request()->routeIs('admin.departments.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white transition-colors' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            Divisi & Jadwal
                        </a>
                    </li>
                @endif
                
            </ul>
        </nav>

        <div class="p-4 border-t border-slate-700">
            <div class="text-sm mb-2">
                <p class="font-semibold truncate">{{ auth()->user()->name }}</p>
                <p class="text-slate-400 text-xs uppercase">{{ auth()->user()->role }}</p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left text-sm text-red-400 hover:text-red-300 py-1 transition-colors">
                    Logout &rarr;
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-full overflow-hidden bg-gray-50">
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
            <h2 class="text-xl font-semibold text-gray-800">@yield('header_title', 'Admin Panel')</h2>
        </header>

        <div class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </div>
    </main>

</body>
</html>