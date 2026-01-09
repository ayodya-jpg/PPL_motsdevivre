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
            'name' => 'Test Pelanggan',
            'email' => 'test@gmail.com',
            'password' => Hash::make('password123'), // Password: password123
            'role' => 'pelanggan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

         DB::table('users')->insert([
            'name' => 'Adam Pelanggan',
            'email' => 'adam@gmail.com',
            'password' => Hash::make('password123'), // Password: password123
            'role' => 'pelanggan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Buat Produk Dummy
        DB::table('products')->insert([
            [
                'nama_produk' => 'Mots De Vivre - Radiant Bliss',
                'deskripsi' => 'Extrait De Parfum',
                'harga' => 114000,
                'stok' => 10,
                'gambar' => '', // Nanti kita urus gambar
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_produk' => 'Mots De Vivre - Secret Desire',
                'deskripsi' => 'Extrait De Parfum',
                'harga' => 114000,
                'stok' => 50,
                'gambar' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
