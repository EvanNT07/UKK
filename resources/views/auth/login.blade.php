@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="max-w-md mx-auto px-4 py-12">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-violet-600 px-8 py-6 text-white">
            <h1 class="text-2xl font-extrabold">Selamat Datang 👋</h1>
            <p class="text-sm text-indigo-100">Login untuk melihat & mengomentari katalog produk.</p>
        </div>
        <form action="{{ route('login.post') }}" method="POST" class="p-8 space-y-4">
            @csrf
            <div>
                <label class="text-sm font-semibold">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com"
                    class="mt-1 w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>
            <div>
                <label class="text-sm font-semibold">Password</label>
                <input type="password" name="password" required placeholder="••••••••"
                    class="mt-1 w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" class="rounded"> Ingat saya
            </label>
            <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-xl shadow">Login</button>
            <p class="text-sm text-center text-slate-500">Belum punya akun? <a href="{{ route('register') }}" class="text-indigo-600 font-bold hover:underline">Register</a></p>
            <div class="bg-slate-50 border rounded-xl p-3 text-xs text-slate-500">
                <p class="font-bold text-slate-700 mb-1">Akun demo:</p>
                <p>Admin — admin@solusindo.com / admin123</p>
                <p>User — user@gmail.com / user123</p>
            </div>
        </form>
    </div>
</div>
@endsection
