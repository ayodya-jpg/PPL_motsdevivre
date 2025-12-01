<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Shop extends BaseController
{
    private $api_url = 'http://localhost:8000/api/products'; // Pastikan port API Laravel benar

    // 1. HALAMAN KATALOG (PUBLIK - TIDAK PERLU LOGIN)
    public function index()
    {
        // Ambil data produk dari API Laravel
        $client = \Config\Services::curlrequest();

        try {
            $response = $client->get($this->api_url);
            $body = json_decode($response->getBody());
            $products = $body->data;
        } catch (\Exception $e) {
            $products = []; // Jika API mati, tampilkan array kosong
        }

        // Hitung jumlah keranjang HANYA JIKA user sudah login
        // Jika tamu (belum login), cart_count otomatis 0
        $cart_count = 0;
        if (session()->get('is_logged_in')) {
            $cart = session()->get('cart') ?? [];
            $cart_count = count($cart);
        }

        $data = [
            'title' => 'Katalog Toko',
            'products' => $products,
            'cart_count' => $cart_count
        ];

        return view('shop/index', $data);
    }

    // 2. FUNGSI TAMBAH KE KERANJANG (PRIVATE - WAJIB LOGIN)
    public function add($id)
    {
        // --- CEK LOGIN ---
        // Jika user belum login, lempar ke halaman Auth
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/auth')->with('error', 'Silahkan login terlebih dahulu untuk mulai belanja.');
        }

        // --- PROSES TAMBAH KERANJANG ---
        $client = \Config\Services::curlrequest();
        
        try {
            // Panggil API Laravel untuk detail produk
            $response = $client->get($this->api_url . '/' . $id);
            $body = json_decode($response->getBody());
            
            // Ambil objek produk (handle wrapper 'data' resource Laravel)
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

    // 3. FUNGSI LIHAT KERANJANG (PRIVATE - Biasanya dilindungi Route Filter)
    public function cart()
    {
        // Opsional: Cek login lagi disini agar lebih aman
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/auth');
        }

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

    // 4. FUNGSI HAPUS DARI KERANJANG
    public function remove($id)
    {
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->set('cart', $cart);
        }
        return redirect()->to('/cart');
    }
    
    // 5. BERSIHKAN KERANJANG
    public function clear()
    {
        session()->remove('cart');
        return redirect()->to('/shop');
    }

    // 6. About Toko
    public function about()
    {
        // Tetap hitung keranjang agar header konsisten
        $cart_count = 0;
        if (session()->get('is_logged_in')) {
            $cart = session()->get('cart') ?? [];
            $cart_count = count($cart);
        }

        $data = [
            'title' => 'About Me',
            'cart_count' => $cart_count
        ];

        return view('shop/about', $data);
    }
}