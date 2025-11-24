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
                'nama_produk' => 'Laptop Gaming ASUS',
                'deskripsi' => 'Laptop spek tinggi untuk gaming dan kerja berat.',
                'harga' => 15000000,
                'stok' => 10,
                'gambar' => 'laptop.jpg', // Nanti kita urus gambar
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_produk' => 'Mouse Wireless Logitech',
                'deskripsi' => 'Mouse tanpa kabel, baterai awet.',
                'harga' => 150000,
                'stok' => 50,
                'gambar' => 'mouse.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_produk' => 'Keyboard Mechanical',
                'deskripsi' => 'Keyboard enak buat ngetik koding.',
                'harga' => 500000,
                'stok' => 20,
                'gambar' => 'keyboard.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
