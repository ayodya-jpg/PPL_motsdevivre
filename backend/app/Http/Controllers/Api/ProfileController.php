<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // Ambil Data User + Alamat
    public function index(Request $request)
    {
        // Karena kita pakai API Token/Session, kita ambil user yang sedang login
        // Note: Untuk simplifikasi tutorial ini tanpa Sanctum Auth yang ribet,
        // kita akan kirim user_id dari Frontend.

        // Namun idealnya pakai Auth::user().
        // Kita asumsikan frontend kirim ID user via query param ?user_id=1
        $user = \App\Models\User::with('address')->find($request->user_id);

        if(!$user) return response()->json(['message' => 'User not found'], 404);

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    // Simpan/Update Alamat
    public function updateAddress(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'nama_penerima' => 'required',
            'no_hp' => 'required',
            'alamat_lengkap' => 'required',
            'kota' => 'required',
        ]);

        $user = \App\Models\User::find($request->user_id);

        // Update atau Buat Baru (updateOrCreate)
        $user->address()->updateOrCreate(
            ['user_id' => $user->id], // Kondisi pencarian
            $request->all() // Data yang diupdate/insert
        );

        return response()->json(['success' => true, 'message' => 'Alamat berhasil disimpan']);
    }
}
