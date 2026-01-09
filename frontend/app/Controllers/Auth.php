<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Auth extends BaseController
{
    // ==========================================
    // 1. KONFIGURASI GOOGLE
    // ==========================================
    
    // Ubah menjadi variabel kosong (tanpa hardcode value)
    protected $googleClientID;
    protected $googleClientSecret;
    
    // URI tetap hardcode sesuai kode asli Anda (kecuali mau dipindah ke env juga)
    protected $googleRedirectUri = 'http://localhost:8091/auth/google/callback'; 

    // Tambahkan Constructor untuk mengambil data dari .env
    public function __construct()
    {
        $this->googleClientID = getenv('GOOGLE_CLIENT_ID');
        $this->googleClientSecret = getenv('GOOGLE_CLIENT_SECRET');
    }
    
    // --- LOGIN SECTION ---
    public function index()
    {
        if (session()->get('is_logged_in')) {
            return session()->get('role') == 'admin' ? redirect()->to('/admin/dashboard') : redirect()->to('/shop');
        }
        return view('auth/login');
    }

    public function loginProcess()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $client = \Config\Services::curlrequest();
        // ✅ FIX: Gunakan 'nginx_server' agar konsisten dengan Docker Network
        $apiUrl = 'http://nginx_server/api/login'; 

        try {
            $response = $client->post($apiUrl, [
                'form_params' => [
                    'email' => $email,
                    'password' => $password
                ],
                'http_errors' => false,
                'timeout' => 30
            ]);

            $body = json_decode($response->getBody());
            $statusCode = $response->getStatusCode();

            if ($statusCode == 200 && $body->success) {
                $userData = $body->data;
                
                // --- SYNC SUBSCRIPTION DATA ---
                try {
                    $userResponse = $client->get('http://nginx_server/api/user/' . $userData->id);
                    $userFromBackend = json_decode($userResponse->getBody());
                    
                    $isSubscribed = (int)($userFromBackend->is_subscribed ?? 0);
                        
                    session()->set([
                        'user_id'       => $userData->id,
                        'name'          => $userData->name,
                        'email'         => $userData->email,
                        'role'          => $userData->role,
                        'created_at'    => $userData->created_at ?? date('Y-m-d H:i:s'),
                        'is_logged_in'  => true
                    ]);

                } catch (\Exception $e) {
                    // Fallback jika sync backend gagal
                    session()->set([
                        'user_id'       => $userData->id,
                        'name'          => $userData->name,
                        'email'         => $userData->email,
                        'role'          => $userData->role,
                        'created_at'    => $userData->created_at ?? date('Y-m-d H:i:s'),
                        'is_logged_in'  => true,
                        'is_subscribed' => 0, 
                        'subscription_status' => null,
                        'plan_name'     => null,
                        'show_membership_popup' => true,  
                    ]);
                }

                // ✅ LOAD ADDRESS
                $this->loadUserAddress($userData->id);

                // ✅ DEBUG LOG
                log_message('info', '=== LOGIN SUCCESS ===');
                log_message('info', 'User ID: ' . $userData->id);

                // Redirect
                if ($userData->role == 'admin') {
                    return redirect()->to('/admin/dashboard');
                }
                
                return redirect()->to('/shop');

            } else {
                return redirect()->back()->with('error', $body->message ?? 'Email atau Password salah');
            } 
        } catch (\Exception $e) {
            log_message('error', 'Login error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal terhubung ke Server API.');
        }
    }

    // ==========================================
    // 2. FITUR GOOGLE LOGIN (METHOD BARU)
    // ==========================================
    
    // A. Redirect User ke Google
    public function google()
    {
        $params = [
            'response_type' => 'code',
            'client_id' => $this->googleClientID,
            'redirect_uri' => $this->googleRedirectUri,
            'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
            'access_type' => 'offline',
            'prompt' => 'consent'
        ];

        return redirect()->to('https://accounts.google.com/o/oauth2/auth?' . http_build_query($params));
    }

    // B. Callback dari Google
    public function googleCallback()
    {
        $code = $this->request->getVar('code');

        if (!$code) {
            return redirect()->to('/auth')->with('error', 'Gagal login dengan Google (No Code).');
        }

        $client = \Config\Services::curlrequest();

        try {
            // 1. Tukar "Code" dengan "Access Token"
            $response = $client->post('https://oauth2.googleapis.com/token', [
                'form_params' => [
                    'client_id' => $this->googleClientID,
                    'client_secret' => $this->googleClientSecret,
                    'redirect_uri' => $this->googleRedirectUri,
                    'grant_type' => 'authorization_code',
                    'code' => $code
                ]
            ]);
            $tokenData = json_decode($response->getBody());
            
            if (!isset($tokenData->access_token)) {
                return redirect()->to('/auth')->with('error', 'Gagal mendapatkan token dari Google.');
            }

            $accessToken = $tokenData->access_token;

            // 2. Ambil Data User dari Google API
            $userInfoResponse = $client->get('https://www.googleapis.com/oauth2/v2/userinfo', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken
                ]
            ]);
            $googleUser = json_decode($userInfoResponse->getBody());

            // 3. Kirim ke Backend Laravel (Internal Docker Network)
            // ✅ FIX: Gunakan 'nginx_server'
            $backendUrl = 'http://nginx_server/api/auth/google-login'; 

            $loginResponse = $client->post($backendUrl, [
                'form_params' => [
                    'email' => $googleUser->email,
                    'name' => $googleUser->name,
                    'google_id' => $googleUser->id
                ],
                'http_errors' => false
            ]);

            $loginResult = json_decode($loginResponse->getBody());

            if ($loginResult && isset($loginResult->success) && $loginResult->success) {
                // 4. Sukses! Set Session
                $userData = $loginResult->data;
                
                session()->set([
                    'user_id'       => $userData->id,
                    'name'          => $userData->name,
                    'email'         => $userData->email,
                    'role'          => $userData->role,
                    'is_logged_in'  => true,
                    // Data tambahan dari response backend
                    'is_subscribed' => $userData->is_subscribed ?? 0,
                    'plan_name'     => $userData->plan_name ?? null,
                    'is_google_login' => true 
                ]);

                // Load Address jika ada
                if (isset($userData->address)) {
                    session()->set('address', (array)$userData->address);
                }

                return redirect()->to('/shop')->with('success', 'Selamat datang, ' . $userData->name);
            } else {
                // Tampilkan pesan error asli dari Laravel di layar
                $errorMsg = $loginResult->message ?? 'Unknown Error';
                $rawResponse = $loginResponse->getBody();
                return redirect()->to('/auth')->with('error', "Backend Error: $errorMsg | Raw: $rawResponse");
            }

        } catch (\Exception $e) {
            log_message('error', 'Google Login Error: ' . $e->getMessage());
            return redirect()->to('/auth')->with('error', 'Terjadi kesalahan saat login dengan Google.');
        }
    }

    // ==========================================
    // 3. FUNGSI PENDUKUNG LAINNYA
    // ==========================================

    private function loadUserAddress($userId)
    {
        try {
            $client = \Config\Services::curlrequest();
            // ✅ FIX: Gunakan 'nginx_server'
            $response = $client->post('http://nginx_server/api/get-address', [
                'json' => ['user_id' => $userId],
                'http_errors' => false,
                'timeout' => 30
            ]);

            if ($response->getStatusCode() === 200) {
                $res = json_decode($response->getBody());
                
                if ($res && isset($res->success) && $res->success && isset($res->data)) {
                    session()->set('address', (array) $res->data);
                    log_message('info', 'Address loaded for user: ' . $userId);
                } else {
                    session()->set('address', null);
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Load address error: ' . $e->getMessage());
            session()->set('address', null);
        }
    }

    public function setSession()
    {
        $userData = json_decode($this->request->getPost('user_data'), true);
        
        if (!$userData) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data user tidak valid']);
        }
        
        // ✅ FIX: Sync dengan backend
        $client = \Config\Services::curlrequest();
        try {
            $userResponse = $client->get('http://nginx_server/api/user/' . $userData['id']);
            $userFromBackend = json_decode($userResponse->getBody());
            
            $isSubscribed = (int)($userFromBackend->is_subscribed ?? 0);
            
            session()->set([
                'user_id'       => $userData['id'],
                'name'          => $userData['name'],
                'email'         => $userData['email'],
                'role'          => $userData['role'] ?? 'pelanggan',
                'created_at'    => $userData['created_at'] ?? date('Y-m-d H:i:s'),
                'is_logged_in'  => true,
                'is_subscribed' => $isSubscribed,
                'subscription_status' => $userFromBackend->subscription_status ?? null,
                'plan_name'     => $userFromBackend->plan_name ?? null,
                'show_membership_popup' => !$isSubscribed,  
            ]);
        } catch (\Exception $e) {
            session()->set([
                'user_id'       => $userData['id'],
                'name'          => $userData['name'],
                'email'         => $userData['email'],
                'role'          => $userData['role'] ?? 'pelanggan',
                'created_at'    => $userData['created_at'] ?? date('Y-m-d H:i:s'),
                'is_logged_in'  => true,
                'is_subscribed' => 0,
                'subscription_status' => null,
                'plan_name'     => null,
                'show_membership_popup' => true, 
            ]);
        }

        if (isset($userData['id'])) {
            $this->loadUserAddress($userData['id']);
        }
        
        return $this->response->setJSON(['success' => true]);
    }

    public function register()
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to('/shop');
        }
        
        $data['title'] = "Daftar Akun Baru - Mots De Vivre";
        return view('auth/register', $data);
    }

    public function storeRegister()
    {
        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $promoCode = $this->request->getPost('promo_code');
    
        $client = \Config\Services::curlrequest();
        // ✅ FIX: Gunakan 'nginx_server'
        $apiUrl = 'http://nginx_server/api/register'; 
    
        try {
            $response = $client->post($apiUrl, [
                'form_params' => [
                    'name'       => $name,
                    'email'      => $email,
                    'password'   => $password,
                    'promo_code' => $promoCode 
                ],
                'http_errors' => false,
                'timeout' => 30
            ]);
    
            $body = json_decode($response->getBody());
            
            if ($response->getStatusCode() !== 201) {
                log_message('error', 'Register failed: ' . $response->getBody());
            }
    
            if ($body->success) {
                return redirect()->to('/auth')->with('success', 'Berhasil! Silakan login.');
            } else {
                $errorMsg = isset($body->errors) ? json_encode($body->errors) : ($body->message ?? 'Registrasi gagal');
                return redirect()->back()->withInput()->with('error', $errorMsg);
            }
    
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal terhubung ke API: ' . $e->getMessage());
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/clear-storage');
    }

    public function clearStorage()
    {
        $data['title'] = 'Logging out...';
        return view('auth/clear_storage');
    }
}
//debug