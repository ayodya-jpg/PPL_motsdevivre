<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserPromoSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua user
        $users = User::all();

        foreach ($users as $user) {
            // Cek apakah user sudah punya promo
            $hasPromo = DB::table('user_promos')->where('user_id', $user->id)->exists();
            
            if (!$hasPromo) {
                // Berikan 2 promo untuk setiap user baru
                DB::table('user_promos')->insert([
                    [
                        'user_id' => $user->id,
                        'promo_code' => 'NEWUSER20',
                        'promo_type' => 'product',
                        'discount_percent' => 20,
                        'is_used' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'user_id' => $user->id,
                        'promo_code' => 'FREESHIP10',
                        'promo_type' => 'shipping',
                        'discount_percent' => 10,
                        'is_used' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ]);
            }
        }
    }
}
