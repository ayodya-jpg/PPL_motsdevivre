<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class MembershipProxy extends BaseController
{
    // Approve membership
    public function approve($id)
    {
        $client = \Config\Services::curlrequest();
        $apiUrl = "http://nginx_server/api/admin/membership/approve/{$id}";

        try {
            $response = $client->post($apiUrl);
            $result = json_decode($response->getBody());

            if ($result->success) {
                return redirect()->to('/admin/dashboard')->with('success', $result->message);
            } else {
                return redirect()->back()->with('error', $result->message);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal approve membership!');
        }
    }

    // Reject membership
    public function reject($id)
    {
        $client = \Config\Services::curlrequest();
        $apiUrl = "http://nginx_server/api/admin/membership/reject/{$id}";

        try {
            $response = $client->post($apiUrl);
            $result = json_decode($response->getBody());

            if ($result->success) {
                return redirect()->to('/admin/dashboard')->with('success', $result->message);
            } else {
                return redirect()->back()->with('error', $result->message);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal reject membership!');
        }
    }

    // Revoke membership
    public function revoke($id)
    {
        $client = \Config\Services::curlrequest();
        $apiUrl = "http://nginx_server/api/admin/membership/revoke/{$id}";

        try {
            $response = $client->post($apiUrl);
            $result = json_decode($response->getBody());

            if ($result->success) {
                return redirect()->back()->with('success', $result->message);
            } else {
                return redirect()->back()->with('error', $result->message);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal revoke membership!');
        }
    }

    // Halaman active members
    public function active()
    {
        $client = \Config\Services::curlrequest();
        $apiUrl = 'http://nginx_server/api/admin/membership/active';

        try {
            $response = $client->get($apiUrl);
            $result = json_decode($response->getBody());
            $activeUsers = $result->data ?? [];
        } catch (\Exception $e) {
            $activeUsers = [];
        }

        return view('admin/membership/active', [
            'activeUsers' => $activeUsers
        ]);
    }
}
