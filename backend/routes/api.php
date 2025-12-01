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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
