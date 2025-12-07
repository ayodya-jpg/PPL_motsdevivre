<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;


// Route Produk (CRUD Sederhana - buat controller-nya nanti)
Route::resource('products', ProductController::class);

// Route Checkout (Integrasi Stok)
Route::post('checkout', [TransactionController::class, 'checkout']);

// Route List Order (Untuk Dashboard Admin)
Route::get('orders', [TransactionController::class, 'index']);

//auth
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

//profile
Route::get('/profile', [ProfileController::class, 'index']);
Route::post('/profile/address', [ProfileController::class, 'updateAddress']);

//history
Route::get('/orders/history', [App\Http\Controllers\Api\TransactionController::class, 'history']);

Route::get('/admin/orders', [App\Http\Controllers\Api\TransactionController::class, 'indexAdmin']);
Route::get('/admin/orders/{id}', [App\Http\Controllers\Api\TransactionController::class, 'show']);
Route::post('/admin/orders/{id}/status', [App\Http\Controllers\Api\TransactionController::class, 'updateStatus']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
