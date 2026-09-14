@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold">Dashboard Admin 👑</h1>
            <p class="text-sm text-slate-500">Halo, {{ auth()->user()->name }}! Kelola foto produk & pantau aktivitas.</p>
        </div>
        <a href="{{ route('admin.produk.create') }}" class="text-sm font-bold bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl shadow">+ Tambah Foto Produk</a>
    </div>

    <div class="grid sm:grid-cols-3 gap-4 mt-6">
        <div class="bg-gradient-to-br from-indigo-600 to-violet-600 text-white rounded-2xl p-6 shadow">
            <p class="text-sm opacity-80">📸 Total Foto Produk</p>
            <p class="text-4xl font-extrabold mt-1">{{ $totalProduk }}</p>
        </div>
        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 text-white rounded-2xl p-6 shadow">
            <p class="text-sm opacity-80">👥 Total User</p>
            <p class="text-4xl font-extrabold mt-1">{{ $totalUser }}</p>
        </div>
        <div class="bg-gradient-to-br from-amber-500 to-orange-600 text-white rounded-2xl p-6 shadow">
            <p class="text-sm opacity-80">💬 Total Komentar</p>
            <p class="text-4xl font-extrabold mt-1">{{ $totalKomentar }}</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6 mt-6">
        <div class="bg-white border rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold">Produk Terbaru</h2>
                <a href="{{ route('admin.produk.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">Kelola →</a>
            </div>
            <div class="space-y-3">
                @forelse($produkTerbaru as $p)
                    <div class="flex items-center gap-3 border-b last:border-0 pb-3">
                        @if($p->foto_url)
                            <img src="{{ $p->foto_url }}" class="w-12 h-12 rounded-lg object-cover">
                        @else
                            <div class="w-12 h-12 rounded-lg bg-slate-200 flex items-center justify-center">🖼️</div>
                        @endif
                        <div class="flex-1">
                            <p class="text-sm font-bold">{{ $p->nama }}</p>
                            <p class="text-xs text-slate-500">Rp {{ number_format($p->harga, 0, ',', '.') }} • 💬 {{ $p->comments_count }}</p>
                        </div>
                        <a href="{{ route('admin.produk.edit', $p->id) }}" class="text-xs font-bold text-amber-600 hover:underline">Edit</a>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Belum ada produk.</p>
                @endforelse
            </div>
        </div>
        <div class="bg-white border rounded-2xl p-6">
            <h2 class="font-bold mb-4">Komentar Terbaru</h2>
            <div class="space-y-3">
                @forelse($komentarTerbaru as $k)
                    <div class="text-sm border-b last:border-0 pb-3">
                        <p><span class="font-bold">{{ $k->user->name }}</span> <span class="text-slate-400">pada</span> <a href="{{ route('katalog.show', $k->product_id) }}" class="text-indigo-600 font-semibold hover:underline">{{ $k->product->nama ?? '-' }}</a></p>
                        <p class="text-slate-600 mt-0.5">"{{ \Illuminate\Support\Str::limit($k->komentar, 80) }}"</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Belum ada komentar.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
