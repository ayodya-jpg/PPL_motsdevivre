<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun ADMIN
        DB::table('users')->insert([
            'name' => 'Admin Toko',
            'email' => 'admin@toko.com',
            'password' => Hash::make('password123'), // Password: password123
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Buat Akun PELANGGAN
        DB::table('users')->insert([
            'name' => 'Budi Pelanggan',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('password123'), // Password: password123
            'role' => 'pelanggan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Buat Produk Dummy
        DB::table('products')->insert([
            [
                'nama_produk' => 'Mots De Vivre-radiant Blis',
                'deskripsi' => 'parfum unisex
                cocok untuk kegiatan outdoor
                ketika terkena keringat, aroma parfum lebih keluar',
                'harga' => 114000,
                'stok' => 10,
                'gambar' => 'images/radiant_bliss.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_produk' => 'Mots De Vivre - Secret Desire',
                'deskripsi' => 'parfum unisex
                cocok untuk kegiatan formal (undangan nikahan, dll)
                manis',
                'harga' => 114000,
                'stok' => 50,
                'gambar' => 'images/Secret_desire.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
