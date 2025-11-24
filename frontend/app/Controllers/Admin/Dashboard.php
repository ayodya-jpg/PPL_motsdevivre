<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        // 1. Ambil data produk dari API Laravel
        $client = \Config\Services::curlrequest();
        $apiUrl = 'http://localhost:8000/api/products';

        try {
            $response = $client->get($apiUrl);
            $body = json_decode($response->getBody());
            $products = $body->data;
        } catch (\Exception $e) {
            $products = []; // Kosong jika API error
        }

        $data = [
            'title' => 'Dashboard Admin',
            'products' => $products
        ];

        return view('admin/dashboard', $data);
    }
}