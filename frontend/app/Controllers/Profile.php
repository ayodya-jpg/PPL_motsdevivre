<?php

namespace App\Controllers;

class Profile extends BaseController
{
    private $api_url = 'http://localhost:8000/api/profile';

    public function index()
    {
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/auth');
        }

        $client = \Config\Services::curlrequest();
        $userId = session()->get('user_id');

        try {
            // Panggil API GET Profile dengan parameter user_id
            $response = $client->get($this->api_url . '?user_id=' . $userId);
            $body = json_decode($response->getBody());
            $userData = $body->data;
        } catch (\Exception $e) {
            $userData = null;
        }

        // Hitung keranjang untuk navbar
        $cart_count = session()->get('cart') ? count(session()->get('cart')) : 0;

        $data = [
            'title' => 'Profil Saya',
            'user' => $userData,
            'cart_count' => $cart_count
        ];

        return view('shop/profile', $data);
    }

    public function saveAddress()
    {
        $client = \Config\Services::curlrequest();
        
        try {
            $response = $client->post('http://localhost:8000/api/profile/address', [
                'form_params' => [
                    'user_id' => session()->get('user_id'),
                    'nama_penerima' => $this->request->getPost('nama_penerima'),
                    'no_hp' => $this->request->getPost('no_hp'),
                    'alamat_lengkap' => $this->request->getPost('alamat_lengkap'),
                    'kota' => $this->request->getPost('kota'),
                    'provinsi' => $this->request->getPost('provinsi'),
                    'kode_pos' => $this->request->getPost('kode_pos'),
                ]
            ]);
            
            return redirect()->to('/profile')->with('success', 'Alamat berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->to('/profile')->with('error', 'Gagal menyimpan alamat.');
        }
    }
}