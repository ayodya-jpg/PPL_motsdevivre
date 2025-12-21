<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User; // Pastikan Model User diimport
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ... method login ...
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Input tidak valid'], 422);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            return response()->json([
                'success' => true,
                'message' => 'Login Berhasil',
                'data' => $user
            ], 200);
        }

        return response()->json(['success' => false, 'message' => 'Email atau Password salah'], 401);
    }

    // --- UPDATE METHOD REGISTER ---
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Simpan User beserta Kode Promo (jika ada)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pelanggan',
            // Pastikan kolom 'promo_code' sudah ditambahkan ke $fillable di model User.php
            'promo_code' => $request->promo_code ?? null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi Berhasil',
            'data' => $user
        ], 201);
    }
}
