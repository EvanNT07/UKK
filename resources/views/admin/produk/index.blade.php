@extends('layouts.app')
@section('title', 'Kelola Produk')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-extrabold">Kelola Foto Produk 🛠️</h1>
            <p class="text-sm text-slate-500">Tambah, edit, dan hapus foto produk (khusus Admin).</p>
        </div>
        <a href="{{ route('admin.produk.create') }}" class="text-sm font-bold bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl shadow">+ Tambah Foto Produk</a>
    </div>

    <form method="GET" class="mt-4 flex gap-2">
        <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama produk..."
            class="flex-1 sm:max-w-xs border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        <button class="bg-slate-900 text-white text-sm font-bold px-4 py-2.5 rounded-xl">Cari</button>
        @if($search)<a href="{{ route('admin.produk.index') }}" class="text-sm font-semibold px-3 py-2.5">Reset</a>@endif
    </form>

    <div class="bg-white border rounded-2xl overflow-hidden mt-4 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                    <tr>
                        <th class="text-left px-5 py-3">Foto</th>
                        <th class="text-left px-5 py-3">Nama / Deskripsi</th>
                        <th class="text-left px-5 py-3">Harga</th>
                        <th class="text-left px-5 py-3">Komentar</th>
                        <th class="text-right px-5 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                    <tr class="border-t hover:bg-slate-50">
                        <td class="px-5 py-3">
                            @if($p->foto_url)
                                <img src="{{ $p->foto_url }}" class="w-16 h-12 rounded-lg object-cover border">
                            @else
                                <div class="w-16 h-12 rounded-lg bg-slate-200 flex items-center justify-center">🖼️</div>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <p class="font-bold">{{ $p->nama }}</p>
                            <p class="text-xs text-slate-500">{{ \Illuminate\Support\Str::limit($p->deskripsi, 60) }}</p>
                        </td>
                        <td class="px-5 py-3 font-bold text-indigo-600 whitespace-nowrap">Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                        <td class="px-5 py-3"><span class="text-xs font-bold bg-slate-100 px-2 py-1 rounded-full">💬 {{ $p->comments_count }}</span></td>
                        <td class="px-5 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('katalog.show', $p->id) }}" class="text-xs font-bold text-slate-600 hover:underline mr-2">Lihat</a>
                            <a href="{{ route('admin.produk.edit', $p->id) }}" class="text-xs font-bold text-amber-600 hover:underline mr-2">Edit</a>
                            <form action="{{ route('admin.produk.destroy', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus foto produk ini?')">
                                @csrf @method('DELETE')
                                <button class="text-xs font-bold text-rose-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-5 py-10 text-center text-slate-500">Belum ada produk. <a href="{{ route('admin.produk.create') }}" class="text-indigo-600 font-bold">Tambah sekarang →</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $products->links() }}</div>
</div>
@endsection
