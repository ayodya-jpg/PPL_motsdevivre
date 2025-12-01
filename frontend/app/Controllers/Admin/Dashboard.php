<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    private $api_url_products = 'http://localhost:8000/api/products';
    private $api_url_orders = 'http://localhost:8000/api/admin/orders';

    public function index()
    {
        $client = \Config\Services::curlrequest();
        
        // 1. Ambil Data Produk
        $products = [];
        try {
            $response = $client->get($this->api_url_products);
            $body = json_decode($response->getBody());
            $products = $body->data ?? [];
        } catch (\Exception $e) {
            $products = [];
        }

        // 2. Ambil Data Pesanan (BARU)
        $orders = [];
        try {
            $response = $client->get($this->api_url_orders);
            $body = json_decode($response->getBody());
            $allOrders = $body->data ?? [];
            
            // Opsional: Kita ambil 5 pesanan terbaru saja agar dashboard tidak kepanjangan
            $orders = array_slice($allOrders, 0, 5);
        } catch (\Exception $e) {
            $orders = [];
        }

        $data = [
            'title' => 'Dashboard Admin',
            'products' => $products,
            'orders' => $orders // Kirim data order ke view
        ];

        return view('admin/dashboard', $data);
    }
}