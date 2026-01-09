<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; // Tambahan untuk random password
use App\Models\User;
use App\Models\UserPromo;
use App\Models\UserAddress;

class AuthController extends Controller
{
    /**
     * REGISTRASI USER + LOGIKA PROMO
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        // 1. Simpan User ke Database
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'customer'
        ]);

        // 2. Logika Promo New User
        if ($request->promo_code == 'newuser') {
            $this->giveNewUserPromos($user->id);
        }

        return response()->json([
            'success' => true,
            'message' => 'Registrasi Berhasil' . ($request->promo_code == 'newuser' ? ' + Promo Diaktifkan!' : ''),
            'data'    => $user
        ], 201);
    }

    /**
     * LOGIN USER + AMBIL ALAMAT PERMANEN 
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Input tidak valid',
                'errors'  => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Format respon standar
            return $this->respondWithToken($user, 'Login Berhasil');
        }

        return response()->json([
            'success' => false,
            'message' => 'Email atau Password salah'
        ], 401);
    }

    /**
     * ✅ LOGIN VIA GOOGLE (BARU)
     */
    public function googleLogin(Request $request)
    {
        // 1. Validasi Input dari Frontend CI4
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email',
            'name'      => 'required',
            'google_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Data Google tidak lengkap'], 422);
        }

        // 2. Cek apakah user sudah ada berdasarkan email
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // SKENARIO A: User Sudah Ada -> Update google_id jika belum punya
            if (!$user->google_id) {
                $user->update(['google_id' => $request->google_id]);
            }
        } else {
            // SKENARIO B: User Belum Ada -> Register Baru Otomatis
            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make(Str::random(16)), // Password acak karena login via Google
                'google_id' => $request->google_id,
                'role'      => 'customer'
            ]);

            // Berikan Promo New User secara otomatis untuk pengguna Google baru
            $this->giveNewUserPromos($user->id);
        }

        // 3. Kembalikan respons sukses (sama formatnya dengan login biasa)
        return $this->respondWithToken($user, 'Login Google Berhasil');
    }

    /**
     * HELPER: Memberikan Promo New User
     * (Dipisahkan agar bisa dipanggil oleh register biasa & google login)
     */
    private function giveNewUserPromos($userId)
    {
        UserPromo::create([
            'user_id' => $userId,
            'promo_code' => 'NEWUSER_PROD20',
            'promo_type' => 'product',
            'discount_percent' => 20,
            'is_used' => false
        ]);

        UserPromo::create([
            'user_id' => $userId,
            'promo_code' => 'NEWUSER_SHIP10',
            'promo_type' => 'shipping',
            'discount_percent' => 10,
            'is_used' => false
        ]);
    }

    /**
     * HELPER: Format Response Login yang Konsisten
     */
    private function respondWithToken($user, $message)
    {

        $address = UserAddress::where('user_id', $user->id)->first();

        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => [
                'id'            => $user->id,
                'name'          => $user->name,
                'email'         => $user->email,
                'role'          => $user->role,
                'address'       => $address
            ]
        ], 200);
    }
}
