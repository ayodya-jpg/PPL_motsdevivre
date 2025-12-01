<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Auth extends BaseController
{
    // Halaman Login
    public function index()
    {
        // Jika sudah login, lempar langsung ke dashboard yang sesuai
        if (session()->get('is_logged_in')) {
            if (session()->get('role') == 'admin') {
                return redirect()->to('/admin/dashboard');
            }
            return redirect()->to('/shop');
        }
        return view('auth/login');
    }

    // Proses Login (Mengirim ke API Laravel)
    public function loginProcess()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Setup HTTP Client
        $client = \Config\Services::curlrequest();
        $apiUrl = 'http://localhost:8000/api/login'; // URL Laravel Anda

        try {
            // 1. Kirim Request POST ke Laravel
            $response = $client->post($apiUrl, [
                'form_params' => [
                    'email' => $email,
                    'password' => $password
                ],
                'http_errors' => false // Agar kita bisa menangkap status 401/422 manual
            ]);

            // 2. Baca Hasil Response
            $body = json_decode($response->getBody());
            $statusCode = $response->getStatusCode();

            // 3. Cek Jika Login Berhasil (Status 200)
            if ($statusCode == 200 && $body->success) {
                
                $userData = $body->data;

                // 4. Simpan Data User ke SESSION CodeIgniter
                session()->set([
                    'user_id' => $userData->id,
                    'name'    => $userData->name,
                    'email'   => $userData->email,
                    'role'    => $userData->role, // 'admin' atau 'pelanggan'
                    'is_logged_in' => true
                ]);

                // 5. Redirect Sesuai Role
                if ($userData->role == 'admin') {
                    return redirect()->to('/admin/dashboard');
                } else {
                    return redirect()->to('/shop');
                }

            } else {
                // Login Gagal
                return redirect()->back()->with('error', $body->message ?? 'Login Gagal');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal terhubung ke Server API.');
        }
    }

    // Menampilkan Halaman Register
    public function register()
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to('/shop');
        }
        return view('auth/register');
    }

    // Proses Register ke API Laravel
    public function registerProcess()
    {
        $client = \Config\Services::curlrequest();
        $apiUrl = 'http://localhost:8000/api/register';

        try {
            $response = $client->post($apiUrl, [
                'form_params' => [
                    'name' => $this->request->getPost('name'),
                    'email' => $this->request->getPost('email'),
                    'password' => $this->request->getPost('password')
                ],
                'http_errors' => false // Supaya kita bisa tangkap error 422
            ]);

            $body = json_decode($response->getBody());
            $statusCode = $response->getStatusCode();

            if ($statusCode == 201 && $body->success) {
                // SUKSES: Redirect ke Login dengan pesan
                return redirect()->to('/auth')->with('success', 'Registrasi berhasil! Silahkan Login.');
            } else {
                // GAGAL: Kembalikan ke form register dengan error
                // Jika ada detail error validasi (seperti email sudah dipakai)
                $errorsList = $body->errors ?? [];
                
                return redirect()->back()
                    ->withInput()
                    ->with('error', $body->message ?? 'Registrasi Gagal')
                    ->with('errors_list', $errorsList);
            }

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal terhubung ke API.');
        }
    }

    // Proses Logout
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/shop');
    }
}