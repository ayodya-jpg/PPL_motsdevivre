<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\UserPromo; // Tambahkan Model UserPromo
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function pay(Request $request)
    {
        try {
            // Log untuk debug (Fitur Asli)
            Log::info('Checkout pay request', $request->all());

            // Validasi input (Ditambahkan user_id & promo_codes agar tidak error)
            $validated = $request->validate([
                'total'       => 'required', // Dilepas numeric-nya agar bisa disanitasi manual
                'name'        => 'required|string',
                'email'       => 'required|email',
                'user_id'     => 'required|exists:users,id', // Tambahan wajib untuk promo
                'promo_codes' => 'nullable|array'           // Tambahan opsional
            ]);

            // --- PERBAIKAN: Sanitasi Total (Mencegah Midtrans Error 500) ---
            // Membersihkan karakter non-angka (Rp, titik, koma)
            $totalRaw = preg_replace('/[^0-9]/', '', $request->total);
            $total = (int) $totalRaw;

            // Load Midtrans secara manual (Fitur Asli)
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            // Log config untuk debug (Fitur Asli)
            Log::info('Midtrans Config', [
                'server_key' => substr(env('MIDTRANS_SERVER_KEY'), 0, 10) . '...',
                'is_production' => env('MIDTRANS_IS_PRODUCTION', false)
            ]);

            // Prepare transaction parameters (Fitur Asli dengan Order ID Unik)
            $params = [
                'transaction_details' => [
                    // Prefix TJS- ditambahkan agar Order ID selalu unik di Sandbox
                    'order_id' => 'TJS-' . time() . '-' . rand(1000, 9999),
                    'gross_amount' => $total,
                ],
                'customer_details' => [
                    'first_name' => $validated['name'],
                    'email' => $validated['email'],
                ],
            ];

            Log::info('Midtrans params', $params);

            // Get Snap Token (Fitur Asli)
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // --- BARU: LOGIKA PENGHANGUSAN VOUCHER ---
            // Jika user menggunakan promo, tandai sebagai 'is_used' di database
            if (!empty($request->promo_codes)) {
                UserPromo::where('user_id', $validated['user_id'])
                    ->whereIn('promo_code', $request->promo_codes)
                    ->update(['is_used' => true]);

                Log::info('Promos marked as used', ['promo_codes' => $request->promo_codes]);
            }

            Log::info('Snap token generated', ['token' => substr($snapToken, 0, 20) . '...']);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Fitur Asli
            Log::error('Validation error', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'error' => 'Validasi gagal',
                'details' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            // Fitur Asli
            Log::error('Checkout pay error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
