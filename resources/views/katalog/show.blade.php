@extends('layouts.app')
@section('title', $product->nama)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('katalog.index') }}" class="text-sm font-semibold text-slate-500 hover:text-indigo-600">← Kembali ke Katalog</a>

    <div class="grid lg:grid-cols-2 gap-8 mt-4">
        <!-- FOTO PRODUK -->
        <div class="bg-white rounded-2xl border overflow-hidden shadow-sm">
            @if($product->foto_url)
                <img src="{{ $product->foto_url }}" alt="{{ $product->nama }}" class="w-full h-[420px] object-cover">
            @else
                <div class="w-full h-[420px] bg-slate-200 flex items-center justify-center text-7xl">🖼️</div>
            @endif
        </div>

        <!-- INFO -->
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900">{{ $product->nama }}</h1>
            <p class="text-3xl font-extrabold text-indigo-600 mt-2">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
            <p class="text-sm text-slate-500 mt-2">Diupload oleh {{ $product->user->name ?? 'Admin' }} • {{ $product->created_at->diffForHumans() }}</p>
            <div class="bg-slate-50 border rounded-2xl p-5 mt-4">
                <p class="font-bold text-sm mb-1">Deskripsi Produk</p>
                <p class="text-sm text-slate-600 leading-relaxed">{{ $product->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
            </div>

            @auth
                @if(auth()->user()->role === 'admin')
                    <div class="flex gap-2 mt-4">
                        <a href="{{ route('admin.produk.edit', $product->id) }}" class="text-sm font-bold bg-amber-400 hover:bg-amber-300 px-4 py-2 rounded-lg">✏️ Edit Foto Produk</a>
                        <form action="{{ route('admin.produk.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus foto produk ini?')">
                            @csrf @method('DELETE')
                            <button class="text-sm font-bold bg-rose-600 hover:bg-rose-500 text-white px-4 py-2 rounded-lg">🗑️ Hapus</button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>
    </div>

    <!-- KOMENTAR -->
    <div class="grid lg:grid-cols-3 gap-8 mt-10">
        <div class="lg:col-span-2">
            <h2 class="font-extrabold text-xl">💬 Komentar ({{ $product->comments->count() }})</h2>
            <div class="space-y-3 mt-4">
                @forelse($product->comments as $c)
                    <div class="bg-white border rounded-2xl p-4 flex gap-3">
                        <div class="w-10 h-10 rounded-full {{ $c->user->role === 'admin' ? 'bg-amber-100' : 'bg-indigo-100' }} flex items-center justify-center font-extrabold shrink-0">
                            {{ strtoupper(substr($c->user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <p class="font-bold text-sm">{{ $c->user->name }}</p>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $c->user->role === 'admin' ? 'bg-amber-100 text-amber-700' : 'bg-indigo-100 text-indigo-700' }}">{{ strtoupper($c->user->role) }}</span>
                                <span class="text-xs text-slate-400">{{ $c->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-slate-600 mt-1">{{ $c->komentar }}</p>
                        </div>
                        @auth
                            @if(auth()->user()->role === 'admin' || auth()->id() === $c->user_id)
                                <form action="{{ route('komentar.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Hapus komentar ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-rose-500 hover:text-rose-700 text-sm">✕</button>
                                </form>
                            @endif
                        @endauth
                    </div>
                @empty
                    <div class="bg-white border rounded-2xl p-8 text-center text-sm text-slate-500">Belum ada komentar. Jadilah yang pertama! ✨</div>
                @endforelse
            </div>
        </div>

        <div>
            <div class="bg-white border rounded-2xl p-6 shadow-sm sticky top-24">
                <h3 class="font-bold">Tambahkan Komentar</h3>
                @auth
                    <form action="{{ route('komentar.store', $product->id) }}" method="POST" class="mt-3">
                        @csrf
                        <textarea name="komentar" rows="4" required maxlength="1000" placeholder="Tulis pendapatmu tentang produk ini..."
                            class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                        <button class="w-full mt-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-xl text-sm">Kirim Komentar</button>
                    </form>
                @else
                    <p class="text-sm text-slate-500 mt-3">Silakan <a href="{{ route('login') }}" class="text-indigo-600 font-bold">login</a> untuk menambahkan komentar.</p>
                @endauth
            </div>

            @if($related->count())
            <div class="mt-6">
                <h3 class="font-bold mb-3">Produk Lainnya</h3>
                <div class="space-y-3">
                    @foreach($related as $r)
                    <a href="{{ route('katalog.show', $r->id) }}" class="flex gap-3 bg-white border rounded-xl p-3 hover:shadow">
                        @if($r->foto_url)
                            <img src="{{ $r->foto_url }}" class="w-16 h-16 rounded-lg object-cover">
                        @else
                            <div class="w-16 h-16 rounded-lg bg-slate-200 flex items-center justify-center">🖼️</div>
                        @endif
                        <div>
                            <p class="text-sm font-bold line-clamp-2">{{ $r->nama }}</p>
                            <p class="text-sm font-extrabold text-indigo-600">Rp {{ number_format($r->harga, 0, ',', '.') }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
