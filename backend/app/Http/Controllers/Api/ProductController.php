<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(['success' => true, 'data' => Product::all()], 200);
    }

    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        return response()->json(['success' => true, 'data' => $product], 200);
    }

    // SIMPAN PRODUK BARU
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_produk' => 'required',
            'harga' => 'required|integer',
            'stok' => 'required|integer',
            'deskripsi' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Validasi Gambar
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Upload Gambar
        $imagePath = null;
        if ($request->hasFile('gambar')) {
            // Simpan ke folder 'public/products'
            $imagePath = $request->file('gambar')->store('products', 'public');
        }

        $product = Product::create([
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'gambar' => $imagePath, // Simpan path gambar
        ]);

        return response()->json(['success' => true, 'data' => $product], 201);
    }

    // UPDATE PRODUK
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) return response()->json(['message' => 'Produk tidak ditemukan'], 404);

        // Validasi (Gambar nullable/boleh kosong saat edit)
        $validator = Validator::make($request->all(), [
            'nama_produk' => 'required',
            'harga' => 'required|integer',
            'stok' => 'required|integer',
            'deskripsi' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $dataToUpdate = [
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
        ];

        // Jika ada upload gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($product->gambar && Storage::disk('public')->exists($product->gambar)) {
                Storage::disk('public')->delete($product->gambar);
            }
            // Upload baru
            $dataToUpdate['gambar'] = $request->file('gambar')->store('products', 'public');
        }

        $product->update($dataToUpdate);

        return response()->json(['success' => true, 'data' => $product], 200);
    }

    // HAPUS PRODUK
    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) return response()->json(['message' => 'Produk tidak ditemukan'], 404);

        // Hapus file gambar dari storage
        if ($product->gambar && Storage::disk('public')->exists($product->gambar)) {
            Storage::disk('public')->delete($product->gambar);
        }

        $product->delete();
        return response()->json(['message' => 'Produk berhasil dihapus'], 200);
    }
}
