<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Orders extends BaseController
{
    public function index()
    {
        // Sementara: data pesanan kosong dulu
        $ongoing   = []; // pesanan sedang berjalan
        $completed = []; // pesanan selesai

        // Jika kamu punya hitung cart di tempat lain, bisa diambil dari session
        $cart = session()->get('cart') ?? [];
        $cart_count = count($cart);

        return view('Shop/orders', [
            'title'       => 'Riwayat Pesanan',
            'ongoing'     => $ongoing,
            'completed'   => $completed,
            'cart_count'  => $cart_count,
        ]);
    }
}
