<?php

namespace App\Controllers;

class Checkout extends BaseController
{
    // Pastikan port ini sesuai dengan server Laravel Anda
    private $api_url_profile = 'http://localhost:8000/api/profile';
    private $api_url_checkout = 'http://localhost:8000/api/checkout';

    public function index()
    {
        // 1. Cek Login
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/auth');
        }

        // 2. Cek Keranjang Kosong
        $cart = session()->get('cart') ?? [];
        if (empty($cart)) {
            return redirect()->to('/shop');
        }

        // 3. Ambil Data User (Untuk Alamat)
        $client = \Config\Services::curlrequest();
        $userAddress = null;
        try {
            $userId = session()->get('user_id');
            $response = $client->get($this->api_url_profile . '?user_id=' . $userId);
            $body = json_decode($response->getBody());
            
            // Ambil objek alamat jika ada
            if($body->success && isset($body->data->address)) {
                $userAddress = $body->data->address;
            }
        } catch (\Exception $e) {
            $userAddress = null;
        }

        // 4. Hitung Subtotal Belanja
        $subtotal = 0;
        foreach($cart as $item) {
            $subtotal += $item['harga'] * $item['qty'];
        }

        $data = [
            'title' => 'Checkout',
            'cart' => $cart,
            'subtotal' => $subtotal,
            'address' => $userAddress
        ];

        return view('shop/checkout', $data);
    }

    public function process()
    {
        // 1. Ambil Input dari Form
        $shippingMethodRaw = $this->request->getPost('shipping_method'); // Format: "JNE|20000"
        $shippingEstimation = $this->request->getPost('shipping_estimation'); // Contoh: "3-4 Hari"
        $paymentMethod = $this->request->getPost('payment_method');
        $address = $this->request->getPost('address');

        // Validasi Alamat
        if(!$address) {
            return redirect()->back()->with('error', 'Silahkan pilih atau tambah alamat pengiriman.');
        }

        // 2. Pecah Data Shipping (Nama dan Harga)
        $parts = explode('|', $shippingMethodRaw);
        
        if(count($parts) < 2) {
             return redirect()->back()->with('error', 'Pilih metode pengiriman yang valid.');
        }
        
        $shipName = $parts[0]; // Contoh: "JNE Reguler"
        $shipCost = $parts[1]; // Contoh: "20000"

        // --- LOGIKA HITUNG TANGGAL TIBA (TETAP DIPERTAHANKAN) ---
        // Kita hitung tanggal pastinya SEKARANG agar tersimpan permanen di database.
        
        $hariTambah = 3; // Default rata-rata
        // Ambil angka pertama dari string estimasi (misal "3-4 Hari" -> ambil 3)
        if (preg_match('/(\d+)/', $shippingEstimation, $matches)) {
            $hariTambah = (int)$matches[0];
        }

        $timestamp = time();
        if (stripos($shippingEstimation, 'Hari Ini') === false) {
            // Jika bukan instant, tambah hari sesuai estimasi
            $timestamp = strtotime("+$hariTambah days");
        }

        // Format Tanggal Indonesia Manual
        $bulanIndo = [
            'Jan' => 'Jan', 'Feb' => 'Feb', 'Mar' => 'Mar', 'Apr' => 'Apr', 'May' => 'Mei', 'Jun' => 'Jun',
            'Jul' => 'Jul', 'Aug' => 'Agt', 'Sep' => 'Sep', 'Oct' => 'Okt', 'Nov' => 'Nov', 'Dec' => 'Des'
        ];
        $tglStr = date('d', $timestamp) . ' ' . $bulanIndo[date('M', $timestamp)] . ' ' . date('Y', $timestamp);
        
        // Gabungkan ke dalam string estimasi agar tersimpan rapi di DB
        // Hasil: "3-4 Hari (Estimasi Tiba: 30 Nov 2025)"
        $shippingEstimationFull = "$shippingEstimation (Estimasi Tiba: $tglStr)";


        // 3. Hitung Total Akhir (Produk + Ongkir)
        $cart = session()->get('cart');
        $subtotal = 0;
        foreach($cart as $item) {
            $subtotal += $item['harga'] * $item['qty'];
        }
        $totalBayar = $subtotal + $shipCost;

        // 4. Persiapkan Payload untuk Laravel
        $payload = [
            'user_id' => session()->get('user_id'),
            'items' => array_values($cart),
            'total_harga' => $totalBayar,
            'payment_method' => $paymentMethod,
            'shipping_method' => $shipName,
            'shipping_cost' => $shipCost,
            'shipping_estimation' => $shippingEstimationFull, // Data estimasi + tanggal
            'delivery_address' => $address,
            'status' => 'pending' // KEMBALI KE DEFAULT: PENDING
        ];

        // 5. Kirim ke API Laravel
        $client = \Config\Services::curlrequest();
        
        try {
            $response = $client->post($this->api_url_checkout, [
                'json' => $payload,
                'http_errors' => false 
            ]);
            
            $body = json_decode($response->getBody());

            // Cek Status Response
            if ($response->getStatusCode() == 201 && $body->success) {
                // SUKSES
                session()->remove('cart'); // Kosongkan Keranjang
                return redirect()->to('/shop')->with('success', 'Pesanan berhasil dibuat! Menunggu konfirmasi admin.');
            } else {
                // GAGAL DARI API
                return redirect()->back()->with('error', 'Gagal Checkout: ' . ($body->message ?? 'Error API'));
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan koneksi ke server API.');
        }
    }
}