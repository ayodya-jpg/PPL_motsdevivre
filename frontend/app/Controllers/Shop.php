<?php

namespace App\Controllers;

class Shop extends BaseController
{
    // ✅ GUNAKAN INI (Berdasarkan hasil CURL sukses Anda)
    // Tidak perlu pakai port :8090 karena di dalam docker dia pakai port 80
    protected $api_url = 'http://nginx_server/api/products'; 

    public function index()
    {
        $client = \Config\Services::curlrequest();

        $products = [];
        try {
            $response = $client->get($this->api_url, [
                'http_errors' => false,
                'timeout' => 10 
            ]);
            
            if ($response->getStatusCode() === 200) {
                $body = json_decode($response->getBody());
                $products = $body->data ?? [];
            } else {
                log_message('error', 'API Error: ' . $response->getStatusCode());
            }
        } catch (\Exception $e) {
            log_message('error', 'Koneksi Gagal: ' . $e->getMessage());
            // Kita kosongkan saja produknya biar halaman tetap loading (tidak mati/die)
            $products = [];
        }

        // --- HITUNG CART ---
        $cart_count = 0;
        if (session()->get('is_logged_in')) {
            $cart = session()->get('cart') ?? [];
            $cart_count = count($cart);
        }

        $data = [
            'title'      => 'Katalog Toko',
            'products'   => $products,
            'cart_count' => $cart_count,
        ];

        return view('shop/index', $data);
    }
    
    // =============================================================
    // FUNCTION ADD, CART, REMOVE, DLL (TETAP SAMA)
    // =============================================================

    public function add($id)
    {
        $client = \Config\Services::curlrequest();
        try {
            // Update URL disini juga
            $response = $client->get($this->api_url . '/' . $id);
            $body = json_decode($response->getBody());
            $product = $body->data ?? $body; 

            $cart = session()->get('cart') ?? [];

            if (isset($cart[$id])) {
                $cart[$id]['qty']++;
            } else {
                $cart[$id] = [
                    'id'     => $product->id, 
                    'nama'   => $product->nama_produk,
                    'harga'  => $product->harga, 
                    'gambar' => $product->gambar, 
                    'qty'    => 1
                ];
            }
            session()->set('cart', $cart);
            return redirect()->to('/shop')->with('success', 'Produk ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->to('/shop')->with('error', 'Gagal menambahkan produk.');
        }
    }

    public function cart()
    {
        $cart = session()->get('cart') ?? [];
        $total = 0;
        foreach($cart as $item) { 
            $total += $item['harga'] * $item['qty']; 
        }

        return view('shop/cart', [
            'title' => 'Keranjang Belanja',
            'cart'  => $cart,
            'total' => $total
        ]);
    }
    
    public function remove($id)
    {
        $cart = session()->get('cart');
        if(isset($cart[$id])) { 
            unset($cart[$id]); 
            session()->set('cart', $cart); 
        }
        return redirect()->to('/cart');
    }
    
    public function clear()
    {
        session()->remove('cart');
        return redirect()->to('/shop');
    }

    public function about()
    {
        $cart = session()->get('cart') ?? [];
        return view('Shop/about', [
            'title'      => 'Tentang Mots',
            'cart_count' => count($cart),
        ]);
    }
}