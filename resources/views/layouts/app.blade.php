<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Katalog Produk') - PT Indonesia Solusindo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: { brand: { 50:'#eef2ff', 500:'#6366f1', 600:'#4f46e5', 700:'#4338ca' } }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col">

<!-- NAVBAR -->
<nav class="bg-white/90 backdrop-blur border-b border-slate-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <a href="{{ route('katalog.index') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-600 to-violet-600 flex items-center justify-center text-white font-extrabold text-lg">S</div>
                <div>
                    <p class="font-extrabold text-slate-900 leading-tight">Solusindo<span class="text-indigo-600">Katalog</span></p>
                    <p class="text-[11px] text-slate-500 leading-tight">PT Indonesia Solusindo</p>
                </div>
            </a>
            <div class="hidden md:flex items-center gap-6 text-sm font-medium">
                <a href="{{ route('katalog.index') }}" class="hover:text-indigo-600 {{ request()->routeIs('katalog.*') ? 'text-indigo-600' : 'text-slate-600' }}">Katalog</a>
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600' : 'text-slate-600' }}">Dashboard</a>
                        <a href="{{ route('admin.produk.index') }}" class="hover:text-indigo-600 {{ request()->routeIs('admin.produk.*') ? 'text-indigo-600' : 'text-slate-600' }}">Kelola Produk</a>
                    @endif
                @endauth
            </div>
            <div class="flex items-center gap-2">
                @guest
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 px-3 py-2">Login</a>
                    <a href="{{ route('register') }}" class="text-sm font-semibold bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow">Register</a>
                @else
                    <span class="hidden sm:inline text-xs px-2.5 py-1 rounded-full font-bold {{ auth()->user()->role === 'admin' ? 'bg-amber-100 text-amber-700' : 'bg-indigo-100 text-indigo-700' }}">
                        {{ strtoupper(auth()->user()->role) }}
                    </span>
                    <span class="hidden sm:block text-sm font-semibold text-slate-700">{{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button class="text-sm font-semibold bg-slate-900 hover:bg-slate-700 text-white px-4 py-2 rounded-lg">Logout</button>
                    </form>
                @endguest
            </div>
        </div>
    </div>
    <!-- mobile admin links -->
    @auth
    @if(auth()->user()->role === 'admin')
    <div class="md:hidden border-t border-slate-100 px-4 py-2 flex gap-4 text-sm">
        <a href="{{ route('admin.dashboard') }}" class="text-slate-600">Dashboard</a>
        <a href="{{ route('admin.produk.index') }}" class="text-slate-600">Kelola Produk</a>
    </div>
    @endif
    @endauth
</nav>

<!-- FLASH -->
@if(session('success'))
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-4">
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
</div>
@endif
@if($errors->any())
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-4">
    <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm">
        <ul class="list-disc ml-5">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
</div>
@endif

<main class="flex-1">
    @yield('content')
</main>

<footer class="mt-12 bg-slate-900 text-slate-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 grid md:grid-cols-3 gap-8">
        <div>
            <p class="font-extrabold text-white text-lg">Solusindo<span class="text-indigo-400">Katalog</span></p>
            <p class="text-sm mt-2 text-slate-400">Web katalog produk client-server untuk PT Indonesia Solusindo. Dibuat oleh Icha & Ahnaf — Latihan Soal UKK.</p>
        </div>
        <div>
            <p class="font-bold text-white mb-2">Fitur UKK</p>
            <ul class="text-sm space-y-1 text-slate-400">
                <li>✓ Login / Logout (User & Admin)</li>
                <li>✓ Register (User)</li>
                <li>✓ Lihat Foto Produk (User & Admin)</li>
                <li>✓ Tambah / Edit / Hapus Foto Produk (Admin)</li>
                <li>✓ Komentar (User & Admin)</li>
            </ul>
        </div>
        <div>
            <p class="font-bold text-white mb-2">Akun Demo</p>
            <div class="text-sm text-slate-400 space-y-1">
                <p><span class="text-amber-300 font-semibold">Admin:</span> admin@solusindo.com / admin123</p>
                <p><span class="text-indigo-300 font-semibold">User:</span> user@gmail.com / user123</p>
            </div>
        </div>
    </div>
    <div class="border-t border-white/10 py-4 text-center text-xs text-slate-500">© 2026 PT Indonesia Solusindo — UKK Web Katalog</div>
</footer>

</body>
</html>
