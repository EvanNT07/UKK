# UKK — Web Katalog Produk (Laravel)

Aplikasi web katalog produk berbasis client-server untuk Latihan Soal UKK — **PT Indonesia Solusindo**.
Dibangun dengan Laravel 13 + MySQL + Blade + Tailwind CSS.

> Dokumentasi pendalaman (mapping soal → implementasi, UI/UX, checklist testing): lihat [`README_UKK.md`](README_UKK.md).

---

## 1. Fitur (sesuai tabel soal UKK)

| No | Fitur | Guest | User | Admin |
|----|-------|:-----:|:----:|:-----:|
| 1 | Login (`GET/POST /login`) | ✅ | ✅ | ✅ |
| 2 | Logout (`POST /logout`) | – | ✅ | ✅ |
| 3 | Register — role otomatis `user` (`GET/POST /register`) | ✅ | – | – |
| 4 | Lihat Foto Produk — katalog `/` + detail `/produk/{id}` (bisa tanpa login) | ✅ | ✅ | ✅ |
| 5 | Tambah Foto Produk — upload `jpg/jpeg/png/webp` maks 2 MB | – | – | ✅ |
| 6 | Edit Foto Produk (+ hapus file lama otomatis) | – | – | ✅ |
| 7 | Hapus Foto Produk (+ hapus file dari storage) | – | – | ✅ |
| 8 | Tambah Komentar (`POST /produk/{product}/komentar`, harus login) | – | ✅ | ✅ |

**Fitur tambahan:** hapus komentar (admin = semua komentar, user = milik sendiri), pencarian produk (`?q=`), pagination (9/katalog, 10/admin), dashboard admin berisi statistik, validasi berbahasa Indonesia, tampilan responsif.

**Akun demo** (dibuat oleh `DatabaseSeeder`):

| Role | Email | Password | Redirect setelah login |
|------|-------|----------|------------------------|
| Admin | `admin@solusindo.com` | `admin123` | `/admin/dashboard` |
| User | `user@gmail.com` | `user123` | `/` (katalog) |

---

## 2. Teknologi

- **Backend:** PHP 8.3, Laravel 13.17 (`laravel/framework`), Eloquent ORM, Blade
- **Database:** MySQL — database `ukk_katalog`
- **Frontend:** Tailwind CSS via CDN + font Plus Jakarta Sans (tanpa build Vite, siap demo)
- **Upload:** `storage/app/public/products` via disk `public` (`php artisan storage:link`)
- **Dev tools:** Composer, Laravel Pint, PHPUnit 12, Faker

---

## 3. Struktur proyek (file yang ditulis untuk UKK)

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php       # login / register / logout + redirect by role
│   │   ├── ProductController.php    # katalog publik + CRUD produk + dashboard admin
│   │   └── CommentController.php    # tambah / hapus komentar
│   └── Middleware/
│       └── IsAdmin.php              # guard halaman /admin/* (403 jika bukan admin)
├── Models/
│   ├── User.php                     # role admin|user, isAdmin(), relasi products/comments
│   ├── Product.php                  # accessor foto_url, relasi user/comments
│   └── Comment.php                  # relasi product/user
bootstrap/
└── app.php                          # alias middleware 'admin' => IsAdmin
routes/
└── web.php                          # seluruh rute + middleware guest/auth/admin
database/
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php   # bawaan Laravel
│   ├── *_add_role_to_users_table.php              # kolom role ENUM(admin,user)
│   ├── *_create_products_table.php                # tabel products
│   └── *_create_comments_table.php                # tabel comments
└── seeders/DatabaseSeeder.php       # 2 akun demo + 6 produk + 7 komentar
resources/views/
├── layouts/app.blade.php            # navbar sticky, flash message, footer + akun demo
├── katalog/index.blade.php          # hero + search + grid katalog + pagination
├── katalog/show.blade.php           # detail + komentar + produk terkait
├── admin/dashboard.blade.php        # 3 kartu statistik + tabel terbaru
├── admin/produk/{index,create,edit}.blade.php
├── auth/{login,register}.blade.php
└── errors/403.blade.php
```

---

## 4. Dokumentasi kode

### 4.1 Rute — `routes/web.php`

| Method | URL | Controller | Middleware | Keterangan |
|--------|-----|------------|------------|------------|
| GET | `/` | `ProductController@index` | – | Katalog + search `?q=` + pagination 9 |
| GET | `/produk/{product}` | `ProductController@show` | – | Detail + komentar + 4 produk terkait |
| GET/POST | `/login` | `AuthController@showLogin/login` | `guest` | Redirect otomatis by role jika sudah login |
| GET/POST | `/register` | `AuthController@showRegister/register` | `guest` | Role dipaksa `user` |
| POST | `/logout` | `AuthController@logout` | `auth` | Invalidasi session + regenerate token |
| POST | `/produk/{product}/komentar` | `CommentController@store` | `auth` | Maks 1000 karakter |
| DELETE | `/komentar/{comment}` | `CommentController@destroy` | `auth` | Admin semua / user milik sendiri |
| GET | `/admin/dashboard` | `ProductController@dashboard` | `auth, admin` | Statistik produk/user/komentar |
| GET/POST | `/admin/produk`, `/admin/produk/create` | `ProductController@adminIndex/create/store` | `auth, admin` | Kelola + tambah (foto wajib) |
| GET/PUT | `/admin/produk/{product}/edit` | `ProductController@edit/update` | `auth, admin` | Foto opsional saat edit |
| DELETE | `/admin/produk/{product}` | `ProductController@destroy` | `auth, admin` | Hapus record + file foto |

### 4.2 Controller

**`AuthController`** — `showLogin/login/showRegister/register/logout` + `redirectByRole()`
(admin → `admin.dashboard`, user → `katalog.index`). Login memakai `Auth::attempt()` +
`session()->regenerate()` (anti session fixation); register memvalidasi
`password|confirmed|min:6` dan `email|unique`; semua pesan validasi berbahasa Indonesia.

**`ProductController`**
- `index()` — eager load `user` + `withCount('comments')`, filter `nama/deskripsi LIKE %q%`,
  `latest()->paginate(9)->withQueryString()` (query search tidak hilang saat pindah halaman).
- `show()` — route-model binding `Product $product`, load `comments.user`, 4 produk terkait.
- `dashboard()` — `Product::count()`, `User::where(role=user)->count()`, `Comment::count()`,
  5 produk & 5 komentar terbaru.
- `store()` — foto **wajib** (`image|mimes:jpg,jpeg,png,webp|max:2048`), simpan via
  `$request->file('foto')->store('products','public')`, `user_id = auth()->id()`.
- `update()` — foto opsional; jika ada file baru, file lama dihapus dari disk `public`.
- `destroy()` — hapus file foto (jika ada) lalu hapus record.

**`CommentController`** — `store()` membuat komentar (`product_id`, `user_id = auth()->id()`);
`destroy()` mengizinkan admin menghapus semua komentar, user hanya miliknya
(selain itu `abort(403)`).

### 4.3 Model & relasi

```php
User    :: isAdmin(): bool            // $this->role === 'admin'
User    :: hasMany(Product)  ->products()
User    :: hasMany(Comment)  ->comments()
Product :: belongsTo(User)   ->user()
Product :: hasMany(Comment)->latest() ->comments()
Product :: getFotoUrlAttribute()      // null jika kosong; dukung URL penuh (seed picsum)
                                     // maupun path storage → Storage::disk('public')->url()
Comment :: belongsTo(Product)->product()
Comment :: belongsTo(User)   ->user()
```

Password otomatis di-hash lewat cast `'password' => 'hashed'`; `harga` di-cast `decimal:2`.

### 4.4 Middleware — `IsAdmin` (alias `admin` di `bootstrap/app.php`)

```php
if (!auth()->check() || auth()->user()->role !== 'admin') {
    abort(403, 'Hanya admin yang boleh mengakses halaman ini.');
}
```

Seluruh grup `/admin/*` memakai `['auth','admin']`; halaman 403 custom ada di
`resources/views/errors/403.blade.php`.

### 4.5 Database

Database MySQL **`ukk_katalog`**:

```
users    (id, name, email UNIQUE, role ENUM[admin,user] DEFAULT user,
          password, remember_token, timestamps)
  1──N products (id, user_id FK→users NULL ON DELETE SET NULL,
                 nama, deskripsi NULL, harga DECIMAL(12,2), foto NULL, timestamps)
  1──N comments (id, product_id FK→products CASCADE,
                 user_id FK→users CASCADE, komentar TEXT, timestamps)
products 1──N comments
```

`DatabaseSeeder` memakai `updateOrCreate`/`firstOrCreate` sehingga aman dijalankan ulang:
2 akun demo + 6 produk contoh (foto placeholder `picsum.photos`) + 7 komentar contoh.

---

## 5. Cara menjalankan

**Syarat:** PHP 8.3, Composer 2, MySQL (mis. Laragon, user `root` tanpa password).

```powershell
# 1. Masuk folder proyek
Set-Location -LiteralPath "D:\laragon\www\L.S UKK"

# 2. Buat database (sekali saja)
mysql -u root -e "CREATE DATABASE IF NOT EXISTS ukk_katalog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 3. Install dependency & .env
composer install
Copy-Item .env.example .env   # jika .env belum ada
php artisan key:generate

# 4. Sesuaikan .env
# DB_CONNECTION=mysql, DB_HOST=127.0.0.1, DB_PORT=3306
# DB_DATABASE=ukk_katalog, DB_USERNAME=root, DB_PASSWORD=

# 5. Migrasi + seed + symlink storage
php artisan migrate:fresh --seed
php artisan storage:link

# 6. Jalankan
php artisan serve --port=8000
# buka http://127.0.0.1:8000
```

**Clone dari GitHub di komputer lain:** langkah yang sama, plus `npm install` (opsional —
Tailwind dipakai via CDN sehingga `npm run build` tidak wajib untuk demo).

---

## 6. Testing manual (checklist UKK)

1. Buka `/` sebagai guest → katalog tampil; klik produk → detail tampil.
2. Coba komentar sebagai guest → diarahkan login. Register user baru → otomatis login → bisa komentar.
3. Login admin → `/admin/dashboard` statistik muncul → Kelola Produk → Tambah (upload jpg ≤ 2 MB) → Edit → Hapus → verifikasi perubahan di katalog.
4. Login sebagai user, buka `/admin/produk` → 403.
5. Logout → kembali ke katalog dengan flash message sukses.

Cek cepat via tinker:

```powershell
php artisan tinker --execute="echo App\Models\User::count().' users, '.App\Models\Product::count().' products';"
```

---

## 7. Catatan keamanan

- `.env` **tidak** di-commit (lihat `.gitignore`); yang di-commit hanya `.env.example`.
- Password di-hash (`hashed` cast + `Hash::make` di seeder).
- Proteksi CSRF di semua form (`@csrf`), method spoofing `@method('PUT'/'DELETE')`.
- Otorisasi ganda: middleware `admin` untuk rute + cek kepemilikan di `CommentController@destroy`.

---

© 2026 PT Indonesia Solusindo — Latihan Soal UKK (Tim Web Developer: Icha & Ahnaf).
Framework: Laravel (MIT license).
