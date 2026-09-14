<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// ===== KATALOG PUBLIK (User & Admin bisa lihat Foto Produk) =====
Route::get('/', [ProductController::class, 'index'])->name('katalog.index');
Route::get('/produk/{product}', [ProductController::class, 'show'])->name('katalog.show');

// ===== AUTH =====
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    // Sesuai soal: Register hanya untuk User
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ===== KOMENTAR (User & Admin, harus login) =====
Route::middleware('auth')->group(function () {
    Route::post('/produk/{product}/komentar', [CommentController::class, 'store'])->name('komentar.store');
    Route::delete('/komentar/{comment}', [CommentController::class, 'destroy'])->name('komentar.destroy');
});

// ===== ADMIN ONLY : Tambah / Edit / Hapus Foto Produk =====
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [ProductController::class, 'dashboard'])->name('dashboard');
    Route::get('/produk', [ProductController::class, 'adminIndex'])->name('produk.index');
    Route::get('/produk/create', [ProductController::class, 'create'])->name('produk.create');
    Route::post('/produk', [ProductController::class, 'store'])->name('produk.store');
    Route::get('/produk/{product}/edit', [ProductController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{product}', [ProductController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{product}', [ProductController::class, 'destroy'])->name('produk.destroy');
});
