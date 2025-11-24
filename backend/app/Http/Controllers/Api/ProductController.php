<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // GET /api/products (Digunakan oleh Admin & Pelanggan)
    public function index()
    {
        $products = Product::all();
        return response()->json([
            'success' => true,
            'data' => $products
        ], 200);
    }

    // Nanti kita tambahkan store, update, destroy untuk Admin
}
