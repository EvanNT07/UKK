@extends('layouts.app')
@section('title', 'Akses Ditolak')

@section('content')
<div class="max-w-md mx-auto px-4 py-16 text-center">
    <p class="text-7xl">🚫</p>
    <h1 class="text-2xl font-extrabold mt-4">403 — Akses Ditolak</h1>
    <p class="text-sm text-slate-500 mt-2">{{ $exception->getMessage() ?: 'Hanya admin yang boleh mengakses halaman ini.' }}</p>
    <a href="{{ route('katalog.index') }}" class="inline-block mt-6 bg-indigo-600 text-white text-sm font-bold px-5 py-2.5 rounded-xl">Kembali ke Katalog</a>
</div>
@endsection
