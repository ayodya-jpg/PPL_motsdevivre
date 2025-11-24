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
}
