<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Profile extends BaseController
{
    protected $helpers = ['form'];
    
     public function index()
    {
        $joinedAt = session()->get('created_at') ?? date('Y-m-d H:i:s');

        $user = (object) [
            'name'       => session()->get('name')  ?? 'User',
            'email'      => session()->get('email') ?? '',
            'created_at' => $joinedAt,
        ];

        // ✅ Ambil alamat dari session ATAU fetch dari backend
        $address = session()->get('address');
        
        // Kalau belum ada di session, fetch dari backend
        if (!$address && session()->get('user_id')) {
            $address = $this->fetchAddressFromBackend(session()->get('user_id'));
        }
        
        if (is_array($address)) {
            $address = (object) $address;
        }


        // ✅ TAMBAHAN BARU - Fetch total transaksi
        $totalTransaksi = 0;
        $userId = session()->get('user_id');
        
        if ($userId) {
            $totalTransaksi = $this->fetchTotalOrders($userId);
        }

        return view('Shop/profile', [
            'title'          => 'Profil Saya',
            'user'           => $user,
            'address'        => $address,
            'total_transaksi' => $totalTransaksi, 
        ]);
    }

        private function fetchAddressFromBackend($userId)
    {
        try {
            $client = \Config\Services::curlrequest();
            
            $response = $client->post('http://nginx_server/api/get-address', [
                'json' => ['user_id' => $userId],
                'http_errors' => false,
                'timeout' => 60,
                'connect_timeout' => 10
            ]);

            if ($response->getStatusCode() === 200) {
                $res = json_decode($response->getBody());
                if ($res && isset($res->success) && $res->success) {
                    return (array) $res->data;
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Fetch address error: ' . $e->getMessage());
        }
        
        return null;
    }

    // ✅ TAMBAHAN BARU - Method untuk fetch total orders
    private function fetchTotalOrders($userId)
    {
        try {
            $client = \Config\Services::curlrequest();
            
            $response = $client->get("http://nginx_server/api/orders/count/{$userId}", [
                'http_errors' => false,
                'timeout' => 10,
                'connect_timeout' => 5
            ]);

            if ($response->getStatusCode() === 200) {
                $res = json_decode($response->getBody());
                if ($res && isset($res->success) && $res->success && isset($res->total)) {
                    return (int) $res->total;
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Fetch total orders error: ' . $e->getMessage());
        }
        
        return 0;
    }

    public function saveAddress()
    {
        log_message('info', '========== SAVE ADDRESS CALLED ==========');
        
        $userId = session()->get('user_id');
        log_message('info', 'User ID: ' . ($userId ?? 'NULL'));
        
        if (!$userId) {
            return redirect()->to('/auth')->with('error', 'Silakan login terlebih dahulu');
        }

        $nama = $this->request->getPost('nama_penerima');
        $noHp = $this->request->getPost('no_hp');
        $alamat = $this->request->getPost('alamat_lengkap');
        $kota = $this->request->getPost('kota');
        $provinsi = $this->request->getPost('provinsi');
        $kodePos = $this->request->getPost('kode_pos');

        log_message('info', "Data - Nama: {$nama}, No HP: {$noHp}, Kota: {$kota}");

        if (empty($nama) || empty($noHp) || empty($alamat) || empty($kota)) {
            return redirect()->back()->with('error', 'Harap isi semua field wajib (Nama, No HP, Alamat, Kota)!');
        }

        $payload = [
            'user_id'        => (int)$userId,
            'nama_penerima'  => $nama,
            'no_hp'          => $noHp,
            'alamat_lengkap' => $alamat,
            'kota'           => $kota,
            'provinsi'       => $provinsi ?? '',
            'kode_pos'       => $kodePos ?? '',
        ];

        log_message('info', 'Payload: ' . json_encode($payload));

        try {
            $client = \Config\Services::curlrequest();
            
            $response = $client->post('http://nginx_server/api/update-address', [
                'json' => $payload,
                'http_errors' => false,
                'timeout' => 60,  // ✅ Naikkan timeout
                'connect_timeout' => 10,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody();
            
            log_message('info', "Backend [{$statusCode}]: {$body}");

            if ($statusCode === 200) {
                $res = json_decode($body);
                
                if ($res && isset($res->success) && $res->success) {
                    // ✅ Simpan ke session untuk cache
                    session()->set('address', [
                        'nama_penerima'  => $nama,
                        'no_hp'          => $noHp,
                        'alamat_lengkap' => $alamat,
                        'kota'           => $kota,
                        'provinsi'       => $provinsi ?? '',
                        'kode_pos'       => $kodePos ?? '',
                    ]);
                    
                    log_message('info', 'SUCCESS - Address saved to DB & session');
                    return redirect()->to('/profile')->with('success', 'Alamat berhasil disimpan!');
                }
            }

            log_message('error', 'Backend error: ' . $statusCode . ' - ' . $body);
            return redirect()->back()->with('error', 'Gagal menyimpan: ' . ($res->message ?? 'Server error'));
            
        } catch (\Exception $e) {
            log_message('error', 'Exception: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan koneksi ke server');
        }
    }
}
