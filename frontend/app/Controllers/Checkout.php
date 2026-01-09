<?php

namespace App\Controllers;

use App\Controllers\BaseController;

//debug
class Checkout extends BaseController
{
    // ✅ URL API Backend (Gunakan IP Wifi Anda yang sudah berhasil sebelumnya)
    // Pastikan IP ini sama dengan yang ada di Shop.php agar konsisten
    protected $api_base_url = 'http://192.168.18.71:8090/api'; 

    public function index()
    {
        // 1. Cek Login
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/auth')->with('error', 'Silakan login terlebih dahulu.');
        }

        // 2. Cek Keranjang
        $cart = session()->get('cart') ?? [];
        if (empty($cart)) {
            return redirect()->to('/shop')->with('error', 'Keranjang kosong!');
        }

        // 3. Hitung Subtotal
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['harga'] * $item['qty'];
        }

        // 4. Ambil Alamat
        $address = session()->get('address');
        if (is_array($address)) {
            $address = (object) $address;
        }

        // 5. Ambil Promo User dari API Laravel
        $client = \Config\Services::curlrequest();
        $user_promos = [];
        
        try {
            // Endpoint: /api/user-promos/{user_id}
            $response = $client->get($this->api_base_url . '/user-promos/' . $userId, [
                'http_errors' => false,
                'timeout' => 5
            ]);
            
            $body = json_decode($response->getBody());
            if (isset($body->success) && $body->success) {
                $user_promos = $body->data;
            }
        } catch (\Exception $e) {
            log_message('error', 'Gagal mengambil promo: ' . $e->getMessage());
            // Lanjut saja meski promo gagal load
        }

        // 6. Tampilkan View
        return view('Shop/checkout', [
            'title'       => 'Checkout Aman',
            'cart'        => $cart,
            'subtotal'    => $subtotal,
            'address'     => $address,
            'user_promos' => $user_promos,
            'user_name'   => session()->get('name'),
            'user_email'  => session()->get('email')
        ]);
    }

    /**
     * processCheckout: Meminta Snap Token ke Backend
     * PENTING: Method ini TIDAK boleh menghapus cart session.
     */
    public function processCheckout()
    {
        $json = $this->request->getJSON();

        if (!$json) {
            return $this->response->setJSON(['success' => false, 'error' => 'Data tidak valid'])->setStatusCode(400);
        }

        // Validasi User
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'error' => 'Sesi habis, login ulang.'])->setStatusCode(401);
        }

        // Validasi Cart
        $cart = session()->get('cart') ?? [];
        if (empty($cart)) {
            return $this->response->setJSON(['success' => false, 'error' => 'Keranjang kosong!'])->setStatusCode(400);
        }

        // Format Items untuk API Laravel
        $items = [];
        foreach ($cart as $item) {
            $items[] = [
                'product_id' => $item['id'],
                'jumlah'     => $item['qty']
            ];
        }

        // Siapkan Payload Data
        $checkoutData = [
            'user_id'     => $userId,
            'name'        => session()->get('name') ?? 'Customer',
            'email'       => session()->get('email') ?? 'customer@example.com',
            'total_harga' => $json->total,
            'items'       => $items,
            'promo_codes' => $json->promo_codes ?? []
        ];

        // Kirim ke API Laravel (/api/checkout)
        $client = \Config\Services::curlrequest();
        
        try {
            $response = $client->post($this->api_base_url . '/checkout', [
                'json'        => $checkoutData,
                'http_errors' => false,
                'timeout'     => 15 // Timeout agak lama untuk proses midtrans
            ]);

            $body = json_decode($response->getBody());

            if (isset($body->success) && $body->success) {
                // ✅ Sukses dapat Token, kirim balik ke Frontend
                // JANGAN hapus session cart di sini!
                return $this->response->setJSON($body);
            } else {
                return $this->response->setJSON([
                    'success' => false, 
                    'error'   => $body->message ?? 'Gagal memproses checkout di backend.'
                ])->setStatusCode(400);
            }

        } catch (\Exception $e) {
            log_message('error', 'Checkout Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false, 
                'error'   => 'Koneksi ke server pembayaran gagal.'
            ])->setStatusCode(500);
        }
    }

    /**
     * ✅ FUNGSI PENTING: Menghapus Cart setelah pembayaran sukses
     * Dipanggil via AJAX/Fetch dari view checkout.php
     */
    public function successPay()
    {
        // 1. Hapus Session Cart
        session()->remove('cart');

        // 2. Kirim respon JSON agar JS tahu proses selesai
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Cart berhasil dikosongkan.'
        ]);
    }
}