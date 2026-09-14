@extends('layouts.app')
@section('title', 'Edit Foto Produk')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <a href="{{ route('admin.produk.index') }}" class="text-sm font-semibold text-slate-500 hover:text-indigo-600">← Kembali</a>
    <div class="bg-white border rounded-2xl shadow-sm p-8 mt-3">
        <h1 class="text-xl font-extrabold">Edit Foto Produk ✏️</h1>
        <p class="text-sm text-slate-500 mb-6">{{ $product->nama }}</p>

        @if($product->foto_url)
        <div class="mb-4">
            <p class="text-sm font-semibold mb-2">Foto saat ini:</p>
            <img src="{{ $product->foto_url }}" class="w-full h-56 object-cover rounded-xl border">
        </div>
        @endif

        <form action="{{ route('admin.produk.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="text-sm font-semibold">Nama Produk *</label>
                <input type="text" name="nama" value="{{ old('nama', $product->nama) }}" required
                    class="mt-1 w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>
            <div>
                <label class="text-sm font-semibold">Harga (Rp) *</label>
                <input type="number" name="harga" value="{{ old('harga', $product->harga) }}" required min="0"
                    class="mt-1 w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>
            <div>
                <label class="text-sm font-semibold">Deskripsi</label>
                <textarea name="deskripsi" rows="4"
                    class="mt-1 w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">{{ old('deskripsi', $product->deskripsi) }}</textarea>
            </div>
            <div>
                <label class="text-sm font-semibold">Ganti Foto (opsional, kosongkan jika tidak diganti)</label>
                <input type="file" name="foto" accept="image/*"
                    class="mt-1 w-full border border-dashed border-slate-300 rounded-xl px-4 py-3 text-sm bg-slate-50">
            </div>
            <div class="flex gap-2 pt-2">
                <button class="flex-1 bg-amber-400 hover:bg-amber-300 text-slate-900 font-bold py-2.5 rounded-xl text-sm">Update Produk</button>
                <a href="{{ route('admin.produk.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-bold border hover:bg-slate-50">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
