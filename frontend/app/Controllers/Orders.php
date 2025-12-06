<?php

namespace App\Controllers;

class Orders extends BaseController
{
    // Alamat API Laravel untuk minta riwayat pesanan
    private $api_url = 'http://localhost:8000/api/orders/history';

    public function index()
    {
        // 1. CEK LOGIN
        // Kalau user belum login, jangan kasih lihat halaman ini, tendang ke login
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/auth');
        }

        // 2. SIAPKAN ALAT UNTUK MENELPON API
        $client = \Config\Services::curlrequest();
        $userId = session()->get('user_id'); // Ambil ID user yang sedang login
        
        // 3. MINTA DATA KE API LARAVEL
        $orders = []; // Siapkan wadah kosong dulu
        try {
            // Kirim request GET ke API dengan membawa ?user_id=...
            $response = $client->get($this->api_url . '?user_id=' . $userId);
            
            // Ubah jawaban API (JSON) menjadi data yang bisa dibaca PHP
            $body = json_decode($response->getBody());
            
            // Kalau API bilang "sukses", ambil datanya
            if($body->success) {
                $orders = $body->data;
            }
        } catch (\Exception $e) {
            $orders = []; // Kalau gagal konek, biarkan kosong
        }

        // 4. PILAH-PILAH PESANAN
        // Kita pisahkan jadi dua tumpukan: "Sedang Berjalan" dan "Selesai"
        $ongoing = [];
        $completed = [];

        foreach($orders as $order) {
            // Cek status pesanan
            if (in_array($order->status, ['pending', 'dibayar', 'dikirim', 'diproses'])) {
                $ongoing[] = $order; // Masuk tumpukan "Sedang Berjalan"
            } else {
                $completed[] = $order; // Masuk tumpukan "Selesai" (status 'selesai' atau 'batal')
            }
        }

        // 5. HITUNG ISI KERANJANG (Agar angka di menu navbar tetap muncul)
        $cart_count = session()->get('cart') ? count(session()->get('cart')) : 0;

        // 6. BUNGKUS SEMUA DATA UNTUK DIKIRIM KE VIEW (Tampilan)
        $data = [
            'title' => 'Riwayat Pesanan',
            'ongoing' => $ongoing,
            'completed' => $completed,
            'cart_count' => $cart_count
        ];

        // Tampilkan halaman
        return view('shop/orders', $data);
    }
}