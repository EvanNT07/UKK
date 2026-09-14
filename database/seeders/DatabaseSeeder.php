<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // === Akun sesuai soal UKK ===
        $admin = User::updateOrCreate(
            ['email' => 'admin@solusindo.com'],
            [
                'name' => 'Admin Solusindo',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        $user = User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'Icha User',
                'password' => Hash::make('user123'),
                'role' => 'user',
            ]
        );

        // === Sample Produk Katalog ===
        $produk = [
            ['nama' => 'Kopi Arabika Gayo 200g', 'deskripsi' => 'Kopi arabika premium dari dataran tinggi Gayo, aroma floral dan rasa clean.', 'harga' => 85000],
            ['nama' => 'Tas Ransel Kanvas', 'deskripsi' => 'Tas ransel kanvas waterproof, cocok untuk kerja dan traveling.', 'harga' => 249000],
            ['nama' => 'Sepatu Sneakers Putih', 'deskripsi' => 'Sneakers casual warna putih, ringan dan nyaman dipakai harian.', 'harga' => 329000],
            ['nama' => 'Jam Tangan Kayu', 'deskripsi' => 'Jam tangan kayu jati dengan strap kulit asli, elegan dan natural.', 'harga' => 499000],
            ['nama' => 'Headset Bluetooth', 'deskripsi' => 'Headset bluetooth noise cancelling, baterai tahan 30 jam.', 'harga' => 599000],
            ['nama' => 'Kemeja Batik Modern', 'deskripsi' => 'Kemeja batik cap modern, bahan katun adem cocok untuk acara formal.', 'harga' => 189000],
        ];

        foreach ($produk as $i => $p) {
            $product = Product::updateOrCreate(
                ['nama' => $p['nama']],
                [
                    'user_id' => $admin->id,
                    'deskripsi' => $p['deskripsi'],
                    'harga' => $p['harga'],
                    // pakai placeholder online agar langsung ada fotonya
                    'foto' => 'https://picsum.photos/seed/produk' . ($i + 1) . '/600/400',
                ]
            );

            // 1-2 komentar contoh
            Comment::firstOrCreate(
                ['product_id' => $product->id, 'user_id' => $user->id, 'komentar' => 'Produknya bagus, pengiriman cepat! (' . $p['nama'] . ')']
            );
        }

        Comment::firstOrCreate([
            'product_id' => Product::first()->id,
            'user_id' => $admin->id,
            'komentar' => 'Terima kasih atas ulasannya! Stok ready ya kak.',
        ]);
    }
}
