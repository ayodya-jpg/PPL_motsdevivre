<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Orders extends BaseController
{
    // URL API Laravel untuk Admin (Pastikan port 8000 benar)
    private $api_url = 'http://localhost:8000/api/admin/orders';

    // 1. HALAMAN DAFTAR PESANAN (LIST)
    public function index()
    {
        $client = \Config\Services::curlrequest();
        $orders = [];

        try {
            // Panggil API GET /api/admin/orders
            $response = $client->get($this->api_url);
            $body = json_decode($response->getBody());
            
            // Jika sukses, ambil datanya
            if(isset($body->success) && $body->success) {
                $orders = $body->data;
            }
        } catch (\Exception $e) {
            // Jika API mati atau error, biarkan kosong agar halaman tidak crash
            $orders = [];
        }

        $data = [
            'title' => 'Kelola Pesanan Masuk',
            'orders' => $orders
        ];

        // Kita akan buat view ini di Tahap 3
        return view('admin/orders/index', $data);
    }

    // 2. HALAMAN DETAIL PESANAN
    public function show($id)
    {
        $client = \Config\Services::curlrequest();
        $order = null;

        try {
            // Panggil API GET /api/admin/orders/{id}
            $response = $client->get($this->api_url . '/' . $id);
            $body = json_decode($response->getBody());
            
            if(isset($body->success) && $body->success) {
                $order = $body->data;
            } else {
                throw new \Exception("Data tidak ditemukan");
            }

        } catch (\Exception $e) {
            // Jika ID tidak ada, kembalikan ke list dengan pesan error
            return redirect()->to('/admin/orders')->with('error', 'Pesanan tidak ditemukan atau server bermasalah.');
        }

        $data = [
            'title' => 'Detail Pesanan #' . $id,
            'order' => $order
        ];

        // Kita akan buat view ini di Tahap 3
        return view('admin/orders/detail', $data);
    }

    // 3. PROSES UPDATE STATUS (Aksi Tombol Simpan)
    public function updateStatus($id)
    {
        // Ambil input status dari form dropdown
        $statusBaru = $this->request->getPost('status');
        
        $client = \Config\Services::curlrequest();

        try {
            // Kirim POST ke API Laravel untuk update
            $response = $client->post($this->api_url . '/' . $id . '/status', [
                'form_params' => [
                    'status' => $statusBaru
                ]
            ]);
            
            // Sukses
            return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui menjadi: ' . strtoupper($statusBaru));

        } catch (\Exception $e) {
            // Gagal
            return redirect()->back()->with('error', 'Gagal memperbarui status pesanan.');
        }
    }
}