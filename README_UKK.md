# Web Katalog Produk — Latihan Soal UKK (Laravel)

**PT Indonesia Solusindo • Tim Web Developer: Icha & Ahnaf**
Aplikasi web katalog produk berbasis client-server sesuai soal [Image 1].

## 1. Ketentuan Soal → Implementasi

| No | Fitur (Soal) | User | Admin | Implementasi di Laravel |
|----|--------------|------|-------|--------------------------|
| 1 | Login | X | X | `GET/POST /login` → `AuthController@login`, redirect by role |
| 2 | Logout | X | X | `POST /logout` → `AuthController@logout` |
| 3 | Register | X | – | `GET/POST /register` → role dipaksa `user` |
| 4 | Foto Produk (Lihat) | X | X | `GET /` katalog + `GET /produk/{id}` detail, bisa diakses guest & login |
| 5 | Hapus Foto Produk | – | X | `DELETE /admin/produk/{id}` + middleware `admin` |
| 6 | Edit Foto Produk | – | X | `GET/PUT /admin/produk/{id}/edit` + middleware `admin` |
| 7 | Tambah Foto Produk | – | X | `GET/POST /admin/produk/create` + middleware `admin`, upload `storage/app/public/products` |
| 8 | Menambahkan Komentar | X | X | `POST /produk/{product}/komentar` → `CommentController@store` (middleware `auth`) |

Tambahan (nilai plus UI/UX): hapus komentar (admin = semua, user = milik sendiri), search produk, pagination, dashboard admin, validasi Indonesia, responsive Tailwind.

## 2. Desain UI/UX (Kaidah)
- Font Plus Jakarta Sans, warna indigo/violet + aksen amber.
- Layout: `layouts/app.blade.php` — navbar sticky, flash success/error, footer + akun demo.
- Publik: hero gradient + search + statistik + grid card 3 kolom + badge jumlah komentar + harga format Rupiah.
- Detail: foto besar 420px + deskripsi + tombol Edit/Hapus (hanya admin) + list komentar + form komentar + produk terkait.
- Admin: 3 kartu statistik gradient + tabel kelola + form tambah/edit dengan preview foto.
- Mobile friendly (grid → 1 kolom, tabel scroll-x).

## 3. Database
Database MySQL: **`ukk_katalog`**

```
users (id, name, email UNIQUE, role ENUM[admin,user] DEFAULT user, password, remember_token, timestamps)
  1──N products (id, user_id FK→users NULL ON DELETE SET NULL, nama, deskripsi NULL, harga DECIMAL 12,2, foto NULL, timestamps)
  1──N comments (id, product_id FK→products CASCADE, user_id FK→users CASCADE, komentar TEXT, timestamps)
products 1──N comments
```

Migration:
- `0001_01_01_000000_create_users_table.php` (bawaan)
- `..._add_role_to_users_table.php`
- `..._create_products_table.php`
- `..._create_comments_table.php`

Model: `User::isAdmin(), products(), comments()` • `Product::foto_url accessor, user(), comments()` • `Comment::product(), user()`

## 4. Akun Demo (Seeder `DatabaseSeeder`)
- Admin: `admin@solusindo.com` / `admin123` → ke `/admin/dashboard`
- User: `user@gmail.com` / `user123` → ke `/`
- 6 produk contoh (foto via picsum) + 7 komentar contoh.

## 5. Cara Running & Testing
Persyaratan: PHP 8.3, Composer 2, MySQL Laragon (user `root` tanpa password).

```powershell
# 1. Masuk folder project
Set-Location -LiteralPath "D:\laragon\www\L.S UKK"

# 2. Buat DB (sekali saja)
mysql -u root -e "CREATE DATABASE IF NOT EXISTS ukk_katalog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 3. Sesuaikan .env (sudah diset)
# DB_CONNECTION=mysql, DB_HOST=127.0.0.1, DB_PORT=3306, DB_DATABASE=ukk_katalog, DB_USERNAME=root, DB_PASSWORD=

# 4. Migrate + seed + storage link
php artisan migrate:fresh --seed
php artisan storage:link

# 5. Jalankan (pilih salah satu)
php artisan serve --port=8000
# buka http://127.0.0.1:8000
```

Testing manual (checklist UKK):
1. Buka `/` sebagai guest → katalog tampil, klik produk → detail tampil.
2. Coba komentar sebagai guest → diminta login. Register user baru → otomatis login → bisa komentar.
3. Login admin → dashboard statistik muncul → Kelola Produk → Tambah (upload jpg max 2MB) → Edit → Hapus → verifikasi di katalog.
4. Login user coba buka `/admin/produk` → 403.
5. Logout → kembali ke katalog.

Testing otomatis cepat:
```powershell
php artisan tinker --execute="echo App\Models\User::count().' users, '.App\Models\Product::count().' products';"
```

## 6. Dokumentasi Kode (penting untuk UKK)
- `routes/web.php` — semua rute + middleware `guest/auth/admin`.
- `app/Http/Controllers/AuthController.php` — login/register/logout + `redirectByRole()`.
- `app/Http/Controllers/ProductController.php` — `index/show` (publik) + `dashboard/adminIndex/create/store/edit/update/destroy` (admin, hapus file lama di `Storage::disk('public')`).
- `app/Http/Controllers/CommentController.php` — `store` (auth) + `destroy` (admin semua / user milik sendiri).
- `app/Http/Middleware/IsAdmin.php` + alias `admin` di `bootstrap/app.php`.
- `resources/views/...` — blade Tailwind CDN (tanpa build Vite, cocok untuk demo UKK).
- Upload foto: `$request->file('foto')->store('products','public')`, tampil via `$product->foto_url`.

## 7. Upload Portofolio ke GitHub
```powershell
Set-Location -LiteralPath "D:\laragon\www\L.S UKK"
git init; git add .; git commit -m "UKK Web Katalog - Laravel (Icha & Ahnaf)"
gh repo create ukk-katalog-solusindo --public --source=. --push
# atau manual: buat repo di github.com → git remote add origin <url> → git push -u origin main
```
Jangan push `.env` asli (pakai `.env.example`); sertakan file ini sebagai dokumentasi.

---
© 2026 PT Indonesia Solusindo — Dibuat untuk Latihan Soal UKK.
