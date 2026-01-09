<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class SubscriptionAdminProxy extends BaseController
{
    public function index()
    {
        $client = \Config\Services::curlrequest();

        try {
            $response = $client->get('http://nginx_server/api/admin/subscriptions', [
                'http_errors' => false,
                'headers'     => [
                    'Accept' => 'application/json',
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            return $this->response->setJSON($data);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'data'    => [],
                'error'   => $e->getMessage(),
            ]);
        }
    }

    // ✅ TAMBAHAN: Cancel Subscription
    public function cancelSubscription($id)
    {
        $client = \Config\Services::curlrequest();

        try {
            $response = $client->post("http://nginx_server/api/admin/subscriptions/cancel/{$id}", [
                'http_errors' => false,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            if ($data && isset($data['success']) && $data['success']) {
                return redirect()->to('/admin/dashboard')->with('success', $data['message']);
            } else {
                return redirect()->to('/admin/dashboard')->with('error', $data['message'] ?? 'Gagal membatalkan langganan');
            }
        } catch (\Exception $e) {
            return redirect()->to('/admin/dashboard')->with('error', 'Error: ' . $e->getMessage());
        }
    }

      public function deleteSubscription($id)
    {
        $client = \Config\Services::curlrequest();

        try {
            $response = $client->post("http://nginx_server/api/admin/subscriptions/delete/{$id}", [
                'http_errors' => false,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            if ($data && isset($data['success']) && $data['success']) {
                return redirect()->to('/admin/dashboard')->with('success', $data['message']);
            } else {
                return redirect()->to('/admin/dashboard')->with('error', $data['message'] ?? 'Gagal menghapus riwayat');
            }
        } catch (\Exception $e) {
            return redirect()->to('/admin/dashboard')->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
