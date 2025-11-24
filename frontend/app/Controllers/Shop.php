<?php

namespace App\Controllers;

class Shop extends BaseController
{
    public function index()
    {
        // Ambil data produk (Sama seperti admin, sumbernya satu: API Laravel)
        $client = \Config\Services::curlrequest();
        $apiUrl = 'http://localhost:8000/api/products';

        try {
            $response = $client->get($apiUrl);
            $body = json_decode($response->getBody());
            $products = $body->data;
        } catch (\Exception $e) {
            $products = [];
        }

        $data = [
            'title' => 'Katalog Toko',
            'products' => $products
        ];

        return view('shop/index', $data);
    }
    public function add($id)
    {
        // Ambil data produk detail dari API Laravel untuk memastikan harga valid
        $client = \Config\Services::curlrequest();
        
        try {
            // Kita panggil API spesifik per ID (Laravel Resource support show/{id})
            $response = $client->get($this->api_url . '/' . $id);
            $body = json_decode($response->getBody());
            
            // Karena resource Laravel biasanya membungkus dalam 'data'
            $product = $body->data ?? $body; 

            // Ambil keranjang lama dari session
            $cart = session()->get('cart') ?? [];

            // Logika: Jika produk sudah ada, tambah Qty. Jika belum, buat baru.
            if (isset($cart[$id])) {
                $cart[$id]['qty']++;
            } else {
                $cart[$id] = [
                    'id' => $product->id,
                    'nama' => $product->nama_produk,
                    'harga' => $product->harga,
                    'gambar' => $product->gambar,
                    'qty' => 1
                ];
            }

            // Simpan kembali ke session
            session()->set('cart', $cart);

            return redirect()->to('/shop')->with('success', 'Produk berhasil ditambahkan ke keranjang!');

        } catch (\Exception $e) {
            return redirect()->to('/shop')->with('error', 'Gagal menambahkan produk. Koneksi API bermasalah.');
        }
    }

    // 2. FUNGSI LIHAT KERANJANG
    public function cart()
    {
        $cart = session()->get('cart') ?? [];
        
        // Hitung Total Bayar
        $total = 0;
        foreach($cart as $item) {
            $total += $item['harga'] * $item['qty'];
        }

        $data = [
            'title' => 'Keranjang Belanja',
            'cart' => $cart,
            'total' => $total
        ];

        return view('shop/cart', $data);
    }

    // 3. FUNGSI HAPUS DARI KERANJANG
    public function remove($id)
    {
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->set('cart', $cart);
        }
        return redirect()->to('/cart');
    }
    
    // 4. BERSIHKAN KERANJANG
    public function clear()
    {
        session()->remove('cart');
        return redirect()->to('/shop');
    }
}