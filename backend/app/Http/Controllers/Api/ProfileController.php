<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function updateAddress(Request $request)
    {
        Log::info('========== UPDATE ADDRESS START ==========');
        Log::info('Request data:', $request->all());

        try {
            // ✅ Validasi
            $validated = $request->validate([
                'user_id' => 'required|integer',
                'nama_penerima' => 'required|string',
                'no_hp' => 'required|string',
                'alamat_lengkap' => 'required|string',
                'kota' => 'required|string',
                'provinsi' => 'nullable|string',
                'kode_pos' => 'nullable|string',
            ]);

            Log::info('Validation passed', $validated);

            // ✅ Cek user exists
            $userExists = DB::table('users')->where('id', $validated['user_id'])->exists();
            Log::info('User exists check:', ['exists' => $userExists]);

            if (!$userExists) {
                Log::warning('User not found: ' . $validated['user_id']);
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 404);
            }

            // ✅ Update or Create
            Log::info('Attempting updateOrCreate...');
            
            $address = Address::updateOrCreate(
                ['user_id' => $validated['user_id']],
                [
                    'nama_penerima'  => $validated['nama_penerima'],
                    'no_hp'          => $validated['no_hp'],
                    'alamat_lengkap' => $validated['alamat_lengkap'],
                    'kota'           => $validated['kota'],
                    'provinsi'       => $validated['provinsi'] ?? '',
                    'kode_pos'       => $validated['kode_pos'] ?? '',
                ]
            );

            Log::info('Address saved successfully', ['address_id' => $address->id]);

            return response()->json([
                'success' => true,
                'message' => 'Alamat berhasil disimpan',
                'data' => $address
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error:', $e->errors());
            
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Update Address Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan alamat: ' . $e->getMessage()
            ], 500);
        } finally {
            Log::info('========== UPDATE ADDRESS END ==========');
        }
    }

    public function getAddress(Request $request)
    {
        Log::info('Get address called', $request->all());
        
        $userId = $request->input('user_id');
        
        $address = Address::where('user_id', $userId)->first();

        if ($address) {
            return response()->json([
                'success' => true,
                'data' => $address
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Alamat belum ada'
        ], 404);
    }
}
