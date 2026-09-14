@extends('layouts.app')
@section('title', 'Katalog Produk')

@section('content')
<!-- HERO -->
<div class="bg-gradient-to-br from-indigo-700 via-indigo-600 to-violet-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
        <div class="md:flex items-center justify-between gap-8">
            <div class="max-w-xl">
                <span class="inline-block text-xs font-bold bg-white/20 px-3 py-1 rounded-full mb-3">🛍️ WEB KATALOG • CLIENT SERVER • UKK</span>
                <h1 class="text-3xl md:text-5xl font-extrabold leading-tight">Jelajahi Katalog Produk Terbaik</h1>
                <p class="mt-3 text-indigo-100">Lihat foto produk, detail harga, dan berikan komentar. Admin dapat mengelola foto produk.</p>
                <form action="{{ route('katalog.index') }}" method="GET" class="mt-6 flex gap-2">
                    <input type="text" name="q" value="{{ $search }}" placeholder="Cari produk... (cth: kopi, tas, sepatu)"
                        class="flex-1 rounded-xl px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-300">
                    <button class="bg-amber-400 hover:bg-amber-300 text-slate-900 font-bold px-5 py-3 rounded-xl text-sm">Cari</button>
                    @if($search)
                        <a href="{{ route('katalog.index') }}" class="bg-white/20 hover:bg-white/30 px-4 py-3 rounded-xl text-sm font-semibold">Reset</a>
                    @endif
                </form>
            </div>
            <div class="hidden md:grid grid-cols-2 gap-3 mt-8 md:mt-0">
                <div class="bg-white/15 backdrop-blur rounded-2xl p-5 text-center">
                    <p class="text-3xl font-extrabold">{{ $products->total() }}</p>
                    <p class="text-xs text-indigo-100">Total Produk</p>
                </div>
                <div class="bg-white/15 backdrop-blur rounded-2xl p-5 text-center">
                    <p class="text-3xl font-extrabold">{{ $products->sum(fn($p) => $p->comments_count) }}</p>
                    <p class="text-xs text-indigo-100">Komentar</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    @if($search)
        <p class="text-sm text-slate-500 mb-4">Hasil pencarian untuk: <span class="font-bold text-slate-800">"{{ $search }}"</span> ({{ $products->total() }} ditemukan)</p>
    @endif

    @if($products->count() === 0)
        <div class="bg-white border rounded-2xl p-12 text-center">
            <p class="text-5xl">📦</p>
            <p class="font-bold text-lg mt-3">Produk tidak ditemukan</p>
            <p class="text-sm text-slate-500">Coba kata kunci lain atau hubungi admin.</p>
        </div>
    @else
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($products as $p)
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition group">
                <a href="{{ route('katalog.show', $p->id) }}" class="block relative">
                    @if($p->foto_url)
                        <img src="{{ $p->foto_url }}" alt="{{ $p->nama }}" class="w-full h-52 object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-full h-52 bg-slate-200 flex items-center justify-center text-5xl">🖼️</div>
                    @endif
                    <span class="absolute top-3 left-3 text-xs font-bold bg-slate-900/80 text-white px-2.5 py-1 rounded-full">💬 {{ $p->comments_count }} komentar</span>
                </a>
                <div class="p-5">
                    <a href="{{ route('katalog.show', $p->id) }}" class="font-bold text-slate-900 hover:text-indigo-600 line-clamp-2">{{ $p->nama }}</a>
                    <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ $p->deskripsi }}</p>
                    <div class="flex items-center justify-between mt-4">
                        <p class="text-lg font-extrabold text-indigo-600">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                        <a href="{{ route('katalog.show', $p->id) }}" class="text-sm font-bold bg-slate-900 text-white px-4 py-2 rounded-lg hover:bg-indigo-600">Lihat</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $products->links() }}</div>
    @endif
</div>
@endsection
