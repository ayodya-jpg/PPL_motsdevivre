<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PromoCode;
use App\Models\Subscription;

class PromoCodeController extends Controller
{
    /**
     * Get available promos for members
     */
    public function getAvailablePromos(Request $request)
    {
        $userId = $request->input('user_id');

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User ID diperlukan'
            ], 400);
        }

        // Cek apakah user adalah member aktif
        $subscription = Subscription::where('user_id', $userId)
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->first();

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Promo khusus member premium',
                'promos' => []
            ], 403);
        }

        // Ambil promo yang aktif dan khusus member
        $promos = PromoCode::where('is_active', true)
            ->where('members_only', true)
            ->where(function($query) {
                $query->whereNull('valid_from')
                      ->orWhere('valid_from', '<=', now());
            })
            ->where(function($query) {
                $query->whereNull('valid_until')
                      ->orWhere('valid_until', '>=', now());
            })
            ->where(function($query) {
                $query->whereNull('usage_limit')
                      ->orWhereRaw('usage_count < usage_limit');
            })
            ->get();

        return response()->json([
            'success' => true,
            'data' => $promos
        ]);
    }

    /**
     * Validate and apply promo code
     */
    public function validatePromo(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'user_id' => 'required|integer',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $code = strtoupper($request->code);
        $userId = $request->user_id;
        $subtotal = $request->subtotal;

        // Cek apakah user adalah member aktif
        $subscription = Subscription::where('user_id', $userId)
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->first();

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Promo khusus member premium. Upgrade sekarang!'
            ], 403);
        }

        // Cari promo code
        $promo = PromoCode::where('code', $code)->first();

        if (!$promo) {
            return response()->json([
                'success' => false,
                'message' => 'Kode promo tidak ditemukan'
            ], 404);
        }

        // Validasi promo
        if (!$promo->isValid($subtotal)) {
            $message = 'Kode promo tidak valid';
            
            if ($subtotal < $promo->min_purchase) {
                $message = 'Minimal belanja Rp ' . number_format($promo->min_purchase, 0, ',', '.');
            } elseif ($promo->usage_limit && $promo->usage_count >= $promo->usage_limit) {
                $message = 'Kode promo sudah mencapai batas penggunaan';
            } elseif (!$promo->is_active) {
                $message = 'Kode promo tidak aktif';
            }

            return response()->json([
                'success' => false,
                'message' => $message
            ], 400);
        }

        // Hitung diskon
        $discount = $promo->calculateDiscount($subtotal);
        
        // Pastikan diskon tidak melebihi subtotal
        if ($discount > $subtotal) {
            $discount = $subtotal;
        }

        $total = $subtotal - $discount;

        return response()->json([
            'success' => true,
            'message' => 'Promo berhasil diterapkan!',
            'data' => [
                'promo_code' => $promo->code,
                'promo_type' => $promo->type,
                'promo_value' => $promo->value,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
            ]
        ]);
    }
}
