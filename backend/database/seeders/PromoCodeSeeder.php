<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PromoCode;
use Carbon\Carbon;

class PromoCodeSeeder extends Seeder
{
    public function run(): void
    {
        $promos = [
            [
                'code' => 'MEMBER10',
                'type' => 'percentage',
                'value' => 10, // Diskon 10%
                'min_purchase' => 100000, // Minimal belanja Rp 100.000
                'usage_limit' => null, // Unlimited
                'members_only' => true,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonths(3), // Berlaku 3 bulan
                'is_active' => true,
            ],
            [
                'code' => 'VIPPREMIUM',
                'type' => 'fixed',
                'value' => 50000, // Diskon Rp 50.000
                'min_purchase' => 500000, // Minimal belanja Rp 500.000
                'usage_limit' => 100, // Maksimal 100x pakai
                'members_only' => true,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonths(6),
                'is_active' => true,
            ],
            [
                'code' => 'WELCOME15',
                'type' => 'percentage',
                'value' => 15, // Diskon 15%
                'min_purchase' => 200000,
                'usage_limit' => 50,
                'members_only' => true,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonth(1),
                'is_active' => true,
            ],
        ];

        foreach ($promos as $promo) {
            PromoCode::create($promo);
        }
    }
}
