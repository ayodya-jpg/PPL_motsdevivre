<?php

namespace App\Controllers;

class Checkout extends BaseController
{
    private $api_url_profile = 'http://localhost:8000/api/profile';
    private $api_url_checkout = 'http://localhost:8000/api/checkout';

    public function index()
    {
        // 1. Cek Login
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/auth');
        }

        // 2. Cek Keranjang
        $cart = session()->get('cart') ?? [];
        if (empty($cart)) {
            return redirect()->to('/shop');
        }

        // 3. Ambil Data User (Alamat & Promo)
        $client = \Config\Services::curlrequest();
        $userAddress = null;
        $userPromo = null;

        try {
            $userId = session()->get('user_id');
            $response = $client->get($this->api_url_profile . '?user_id=' . $userId);
            $body = json_decode($response->getBody());
            
            if($body->success) {
                if(isset($body->data->address)) {
                    $userAddress = $body->data->address;
                }
                // Ambil kode promo dari database untuk ditampilkan di dropdown
                if(isset($body->data->promo_code)) {
                    $userPromo = $body->data->promo_code;
                }
            }
        } catch (\Exception $e) {
            $userAddress = null;
            $userPromo = null;
        }

        // 4. Hitung Subtotal
        $subtotal = 0;
        foreach($cart as $item) {
            $subtotal += $item['harga'] * $item['qty'];
        }

        $data = [
            'title' => 'Checkout',
            'cart' => $cart,
            'subtotal' => $subtotal,
            'address' => $userAddress,
            'user_promo' => $userPromo
        ];

        return view('shop/checkout', $data);
    }

    public function process()
    {
        // 1. Ambil Input dari Form View
        $shippingMethodRaw = $this->request->getPost('shipping_method'); // Format: "NamaKurir|Harga"
        $shippingEstimation = $this->request->getPost('shipping_estimation'); 
        $paymentMethod = $this->request->getPost('payment_method');
        $address = $this->request->getPost('address');
        
        // Ambil kode promo dari input hidden/select
        // Kita prioritaskan final_promo_code jika ada (dari JS), atau promo_code (dari dropdown langsung)
        $promoCode = $this->request->getPost('final_promo_code') ?: $this->request->getPost('promo_code');

        if(!$address) {
            return redirect()->back()->with('error', 'Silahkan pilih atau tambah alamat pengiriman.');
        }

        // 2. Pecah Data Shipping
        $parts = explode('|', $shippingMethodRaw);
        if(count($parts) < 2) {
             return redirect()->back()->with('error', 'Pilih metode pengiriman yang valid.');
        }
        $shipName = $parts[0];
        $shipCost = (int)$parts[1];

        // 3. Hitung Ulang Total (Server Side Calculation)
        $cart = session()->get('cart');
        $subtotal = 0;
        foreach($cart as $item) {
            $subtotal += $item['harga'] * $item['qty'];
        }

        // --- LOGIKA HITUNG DISKON ---
        $discountAmount = 0;
        
        // Validasi Promo: Harus "NEWUSER20_FREESHIP"
        if ($promoCode === 'NEWUSER20_FREESHIP') {
            // Diskon 20% dari Subtotal Produk
            $discProduk = $subtotal * 0.20;
            
            // Diskon 8% dari Ongkir (Sesuai request)
            $discOngkir = $shipCost * 0.08;
            
            $discountAmount = $discProduk + $discOngkir;
        }

        // Total Bayar = (Subtotal + Ongkir) - Diskon
        $totalBayar = ($subtotal + $shipCost) - $discountAmount;
        
        // Pastikan total tidak minus
        if($totalBayar < 0) $totalBayar = 0;


        // 4. Persiapkan Data untuk Backend Laravel
        $payload = [
            'user_id' => session()->get('user_id'),
            'items' => array_values($cart),
            'total_harga' => $totalBayar, // Total yang sudah didiskon
            'payment_method' => $paymentMethod,
            'shipping_method' => $shipName,
            'shipping_cost' => $shipCost,
            'shipping_estimation' => $shippingEstimation, 
            'delivery_address' => $address,
            'status' => 'pending'
        ];

        // 5. Kirim ke API
        $client = \Config\Services::curlrequest();
        try {
            $response = $client->post($this->api_url_checkout, [
                'json' => $payload,
                'http_errors' => false 
            ]);
            
            $body = json_decode($response->getBody());

            if ($response->getStatusCode() == 201 && $body->success) {
                // SUKSES
                session()->remove('cart'); // Kosongkan Keranjang
                return redirect()->to('/orders')->with('success', 'Pesanan berhasil dibuat! Segera lakukan pembayaran.');
            } else {
                return redirect()->back()->with('error', 'Gagal Checkout: ' . ($body->message ?? 'Error API'));
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan koneksi ke server API.');
        }
    }
}