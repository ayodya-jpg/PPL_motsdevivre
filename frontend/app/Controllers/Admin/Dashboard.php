<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $client = \Config\Services::curlrequest();
        
        // 1. Ambil Products
        $apiUrlProducts = 'http://nginx_server/api/products';
        try {
            $response = $client->get($apiUrlProducts);
            $body     = json_decode($response->getBody());
            $products = $body->data ?? [];
        } catch (\Exception $e) {
            $products = [];
        }

        // 2. Ambil Orders (10 terbaru)
        $apiUrlOrders = 'http://nginx_server/api/admin/orders';
        try {
            $response = $client->get($apiUrlOrders);
            $body     = json_decode($response->getBody());
            $orders   = is_array($body) ? array_slice($body, 0, 10) : [];
        } catch (\Exception $e) {
            $orders = [];
        }

        // 3. Ambil Pending Memberships (dari backend Laravel)
        $apiUrlPending = 'http://nginx_server/api/admin/membership/pending';
        try {
            $response = $client->get($apiUrlPending);
            $body     = json_decode($response->getBody());
            $pendingMemberships = $body->data ?? [];
        } catch (\Exception $e) {
            $pendingMemberships = [];
        }

        // 4. Ambil Active Memberships (dari backend Laravel)
        $apiUrlActive = 'http://nginx_server/api/admin/membership/active';
        try {
            $response = $client->get($apiUrlActive);
            $body     = json_decode($response->getBody());
            $activeMemberships = $body->data ?? [];
        } catch (\Exception $e) {
            $activeMemberships = [];
        }

        return view('admin/dashboard', [
            'title'              => 'Dashboard Admin',
            'products'           => $products,
            'orders'             => $orders,
            'pendingMemberships' => $pendingMemberships,
            'activeMemberships'  => $activeMemberships
        ]);
    }   
}
