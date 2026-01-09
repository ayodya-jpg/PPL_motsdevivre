<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Orders extends BaseController
{
    public function index()
    {
        $client = \Config\Services::curlrequest();
        
        // Ambil semua orders dari API Laravel
        $apiUrl = 'http://nginx_server/api/admin/orders';
        try {
            $response = $client->get($apiUrl, [
                'http_errors' => false,
                'timeout' => 30
            ]);
            $body = json_decode($response->getBody());
            $orders = is_array($body) ? $body : [];
        } catch (\Exception $e) {
            $orders = [];
            log_message('error', 'Failed to fetch orders: ' . $e->getMessage());
        }
        
        return view('admin/orders/index', [
            'title' => 'Semua Pesanan',
            'orders' => $orders
        ]);
    }
    
    public function show($id)
    {
        $client = \Config\Services::curlrequest();
        
        // Ambil detail order dari API
        $apiUrl = "http://nginx_server/api/admin/orders/{$id}";
        try {
            $response = $client->get($apiUrl, [
                'http_errors' => false,
                'timeout' => 30
            ]);
            $order = json_decode($response->getBody());
        } catch (\Exception $e) {
            $order = null;
            log_message('error', 'Failed to fetch order detail: ' . $e->getMessage());
        }
        
        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Order tidak ditemukan!');
        }
        
        return view('admin/orders/show', [
            'title' => 'Detail Order #' . $id,
            'order' => $order
        ]);
    }
}
