<?php

namespace App\Controllers;

class Analytics extends BaseController
{
    public function index()
    {
        // Cek apakah user sudah login
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth')->with('error', 'Silakan login terlebih dahulu');
        }

        // Get cart count untuk navbar
        $cart = session()->get('cart') ?? [];
        $cart_count = count($cart);

        $data = [
            'title' => 'Analytics Dashboard - Telur Josjis',
            'cart_count' => $cart_count,
        ];

        return view('Shop/analytics', $data);
    }
}
