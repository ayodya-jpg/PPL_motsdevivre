<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Auth extends BaseController
{
    // ==========================================
    // 1. KONFIGURASI GOOGLE (AMBIL DARI ENV)
    // ==========================================
    protected $googleClientID;
    protected $googleClientSecret;
    protected $googleRedirectUri;

    public function __construct()
    {
        // Menginisialisasi data dari file .env
        $this->googleClientID     = env('google.clientID');
        $this->googleClientSecret = env('google.clientSecret');
        $this->googleRedirectUri  = env('google.redirectUri');
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
                        'is_logged_in'  => true,
                        'is_subscribed' => $isSubscribed
                    ]);

                } catch (\Exception $e) {
                    session()->set([
                        'user_id'       => $userData->id,
                        'name'          => $userData->name,
                        'email'         => $userData->email,
                        'role'          => $userData->role,
                        'is_logged_in'  => true,
                        'is_subscribed' => 0, 
                        'show_membership_popup' => true,  
                    ]);
                }

                $this->loadUserAddress($userData->id);

                if ($userData->role == 'admin') {
                    return redirect()->to('/admin/dashboard');
                }
                
                return redirect()->to('/shop');

            } else {
                return redirect()->back()->with('error', $body->message ?? 'Email atau Password salah');
            } 
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal terhubung ke Server API.');
        }
    }

    // ==========================================
    // 2. FITUR GOOGLE LOGIN
    // ==========================================
    
    public function google()
    {
        $params = [
            'response_type' => 'code',
            'client_id'     => $this->googleClientID,
            'redirect_uri'  => $this->googleRedirectUri,
            'scope'         => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
            'access_type'   => 'offline',
            'prompt'        => 'consent'
        ];

        return redirect()->to('https://accounts.google.com/o/oauth2/auth?' . http_build_query($params));
    }

    public function googleCallback()
    {
        $code = $this->request->getVar('code');

        if (!$code) {
            return redirect()->to('/auth')->with('error', 'Gagal login dengan Google (No Code).');
        }

        $client = \Config\Services::curlrequest();

        try {
            $response = $client->post('https://oauth2.googleapis.com/token', [
                'form_params' => [
                    'client_id'     => $this->googleClientID,
                    'client_secret' => $this->googleClientSecret,
                    'redirect_uri'  => $this->googleRedirectUri,
                    'grant_type'    => 'authorization_code',
                    'code'          => $code
                ]
            ]);
            $tokenData = json_decode($response->getBody());
            
            if (!isset($tokenData->access_token)) {
                return redirect()->to('/auth')->with('error', 'Gagal mendapatkan token dari Google.');
            }

            $accessToken = $tokenData->access_token;

            $userInfoResponse = $client->get('https://www.googleapis.com/oauth2/v2/userinfo', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken
                ]
            ]);
            $googleUser = json_decode($userInfoResponse->getBody());

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
                $userData = $loginResult->data;
                
                session()->set([
                    'user_id'       => $userData->id,
                    'name'          => $userData->name,
                    'email'         => $userData->email,
                    'role'          => $userData->role,
                    'is_logged_in'  => true,
                    'is_subscribed' => $userData->is_subscribed ?? 0,
                    'is_google_login' => true 
                ]);

                if (isset($userData->address)) {
                    session()->set('address', (array)$userData->address);
                }

                return redirect()->to('/shop')->with('success', 'Selamat datang, ' . $userData->name);
            } else {
                return redirect()->to('/auth')->with('error', $loginResult->message ?? 'Gagal sinkronisasi backend.');
            }

        } catch (\Exception $e) {
            return redirect()->to('/auth')->with('error', 'Terjadi kesalahan saat login dengan Google.');
        }
    }

    private function loadUserAddress($userId)
    {
        try {
            $client = \Config\Services::curlrequest();
            $response = $client->post('http://nginx_server/api/get-address', [
                'json' => ['user_id' => $userId],
                'http_errors' => false,
                'timeout' => 30
            ]);

            if ($response->getStatusCode() === 200) {
                $res = json_decode($response->getBody());
                if ($res && isset($res->success) && $res->success && isset($res->data)) {
                    session()->set('address', (array) $res->data);
                }
            }
        } catch (\Exception $e) {
            session()->set('address', null);
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/clear-storage');
    }

    public function clearStorage()
    {
        return view('auth/clear_storage');
    }
}