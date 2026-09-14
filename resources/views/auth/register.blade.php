@extends('layouts.app')
@section('title', 'Register')

@section('content')
<div class="max-w-md mx-auto px-4 py-12">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-6 text-white">
            <h1 class="text-2xl font-extrabold">Buat Akun User 📝</h1>
            <p class="text-sm text-emerald-50">Register khusus untuk User sesuai ketentuan UKK.</p>
        </div>
        <form action="{{ route('register.post') }}" method="POST" class="p-8 space-y-4">
            @csrf
            <div>
                <label class="text-sm font-semibold">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="cth: Icha"
                    class="mt-1 w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>
            <div>
                <label class="text-sm font-semibold">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com"
                    class="mt-1 w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-sm font-semibold">Password</label>
                    <input type="password" name="password" required placeholder="min. 6"
                        class="mt-1 w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
                <div>
                    <label class="text-sm font-semibold">Konfirmasi</label>
                    <input type="password" name="password_confirmation" required placeholder="ulangi"
                        class="mt-1 w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>
            <button class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-xl shadow">Register</button>
            <p class="text-sm text-center text-slate-500">Sudah punya akun? <a href="{{ route('login') }}" class="text-emerald-600 font-bold hover:underline">Login</a></p>
        </form>
    </div>
</div>
@endsection
