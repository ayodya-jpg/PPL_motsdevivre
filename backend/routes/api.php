<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Import Controllers
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\ProfileController; // ✅ Pastikan ini ada
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\EggPriceController;
use App\Http\Controllers\Api\PromoCodeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// =========================================================================
// 1. AUTHENTICATION
// =========================================================================
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
// ✅ FIX: Cukup satu kali definisi route ini
Route::post('/auth/google-login', [AuthController::class, 'googleLogin']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Helper: Get user by ID
Route::get('/user/{id}', function($id) {
    $user = App\Models\User::find($id);
    return $user ? response()->json($user) : response()->json(['error' => 'User not found'], 404);
});


// =========================================================================
// 2. PROFILE & ADDRESS (✅ PERBAIKAN UTAMA DISINI)
// =========================================================================
// Route ini yang dicari oleh Frontend Profile Controller Anda
Route::post('/get-address', [ProfileController::class, 'getAddress']);
Route::post('/update-address', [ProfileController::class, 'updateAddress']);


// =========================================================================
// 3. PRODUCTS (Toko)
// =========================================================================
Route::resource('products', ProductController::class);
Route::get('/admin/products', [ProductController::class, 'index']);


// =========================================================================
// 4. TRANSAKSI PRODUK (Checkout & Orders)
// =========================================================================
// Checkout Barang
Route::post('checkout', [TransactionController::class, 'checkout']);
Route::post('/checkout/pay', [CheckoutController::class, 'pay']);

// Riwayat Order User
Route::get('/orders/history', [TransactionController::class, 'history']);

// Sync Status Pembayaran
Route::get('/orders/{id}/payment-status', [TransactionController::class, 'checkPaymentStatus']);

// Count Total Order (Helper)
Route::get('/orders/count/{userId}', function($userId) {
    // Bisa dipindahkan ke ProfileController::countOrders kedepannya
    $total = DB::table('orders')->where('user_id', $userId)->count();
    return response()->json(['success' => true, 'total' => $total]);
});

// Grafik Harga Telur
Route::get('/egg-prices', [EggPriceController::class, 'history']);

// =========================================================================
// 6. PROMO CODES
// =========================================================================
Route::get('/promo-codes/available', [PromoCodeController::class, 'getAvailablePromos']);
Route::post('/promo-codes/validate', [PromoCodeController::class, 'validatePromo']);

Route::get('/user-promos/{userId}', function($userId) {
    $promos = DB::table('user_promos')
        ->where('user_id', $userId)
        ->where('is_used', false)
        ->get();
    return response()->json(['success' => true, 'data' => $promos]);
});


// =========================================================================
// 7. ADMIN DASHBOARD ROUTES
// =========================================================================

// --- A. Manajemen Order Barang ---
Route::get('orders', [TransactionController::class, 'index']);
Route::get('/admin/orders', [TransactionController::class, 'indexAdmin']);
Route::get('/admin/orders/{id}', [TransactionController::class, 'show']);
Route::post('/orders/{id}/status', [TransactionController::class, 'updateStatus']);

