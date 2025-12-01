<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Input tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Cek Kredensial (Email & Password)
        // Auth::attempt akan otomatis meng-hash password input dan mencocokkan dengan database
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            // Jika Sukses, ambil data user
            $user = Auth::user();

            // 3. Kembalikan Response JSON
            return response()->json([
                'success' => true,
                'message' => 'Login Berhasil',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role, // PENTING: Kirim role (admin/pelanggan)
                ]
            ], 200);
        }

        // 4. Jika Gagal
        return response()->json([
            'success' => false,
            'message' => 'Email atau Password salah'
        ], 401);
    }
    public function register(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // Email tidak boleh kembar
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Buat User Baru (Force role ke 'pelanggan' agar aman)
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'pelanggan' // Default user baru selalu pelanggan
        ]);

        // 3. Return Success
        return response()->json([
            'success' => true,
            'message' => 'Registrasi Berhasil! Silahkan Login.',
            'data' => $user
        ], 201);
    }
}
