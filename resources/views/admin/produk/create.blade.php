@extends('layouts.app')
@section('title', 'Tambah Foto Produk')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <a href="{{ route('admin.produk.index') }}" class="text-sm font-semibold text-slate-500 hover:text-indigo-600">← Kembali</a>
    <div class="bg-white border rounded-2xl shadow-sm p-8 mt-3">
        <h1 class="text-xl font-extrabold">Tambah Foto Produk 📸</h1>
        <p class="text-sm text-slate-500 mb-6">Upload foto + isi nama, harga, dan deskripsi produk.</p>
        <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="text-sm font-semibold">Nama Produk *</label>
                <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="cth: Kopi Arabika Gayo"
                    class="mt-1 w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>
            <div>
                <label class="text-sm font-semibold">Harga (Rp) *</label>
                <input type="number" name="harga" value="{{ old('harga') }}" required min="0" step="500" placeholder="cth: 85000"
                    class="mt-1 w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>
            <div>
                <label class="text-sm font-semibold">Deskripsi</label>
                <textarea name="deskripsi" rows="4" placeholder="Ceritakan keunggulan produk..."
                    class="mt-1 w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">{{ old('deskripsi') }}</textarea>
            </div>
            <div>
                <label class="text-sm font-semibold">Foto Produk * (jpg/png/webp, max 2MB)</label>
                <input type="file" name="foto" required accept="image/*"
                    class="mt-1 w-full border border-dashed border-slate-300 rounded-xl px-4 py-3 text-sm bg-slate-50">
            </div>
            <div class="flex gap-2 pt-2">
                <button class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-xl text-sm">Simpan Produk</button>
                <a href="{{ route('admin.produk.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-bold border hover:bg-slate-50">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
