<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User; // [PENTING] Tambahkan Model User
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    // --- FUNGSI CHECKOUT (UPDATED: Hapus Promo Setelah Dipakai) ---
    public function checkout(Request $request)
    {
        // Validasi Input
        $request->validate([
            'user_id' => 'required',
            'items' => 'required|array',
            'total_harga' => 'required|numeric',
            'payment_method' => 'required',
            'shipping_method' => 'required',
            'shipping_cost' => 'required|numeric',
            'shipping_estimation' => 'required',
            'delivery_address' => 'required'
        ]);

        DB::beginTransaction();
        try {
            // 1. Buat Order Baru
            $order = Order::create([
                'user_id' => $request->user_id,
                'total_harga' => $request->total_harga,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'shipping_method' => $request->shipping_method,
                'shipping_cost' => $request->shipping_cost,
                'shipping_estimation' => $request->shipping_estimation,
                'delivery_address' => $request->delivery_address,
            ]);

            // 2. Proses Item & Kurangi Stok
            foreach ($request->items as $item) {
                $product = Product::find($item['id']);

                if (!$product) {
                    throw new \Exception("Produk dengan ID " . $item['id'] . " tidak ditemukan.");
                }

                // Cek Stok
                if($product->stok < $item['qty']) {
                    throw new \Exception("Stok " . $product->nama_produk . " tidak mencukupi (Sisa: " . $product->stok . ")");
                }

                // KURANGI STOK
                $product->decrement('stok', $item['qty']);

                // Simpan Detail Order
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'jumlah' => $item['qty'],
                    'harga_saat_ini' => $product->harga
                ]);
            }

            // --- [LOGIKA BARU] HAPUS PROMO JIKA DIPAKAI ---
            // Cek user di database
            $user = User::find($request->user_id);
            // Jika user punya promo 'NEWUSER20_FREESHIP' saat checkout ini terjadi
            if ($user && $user->promo_code === 'NEWUSER20_FREESHIP') {
                // Hapus promo agar tidak bisa dipakai lagi (Set jadi NULL)
                $user->update(['promo_code' => null]);
            }
            // ----------------------------------------------

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Transaksi Berhasil', 'order_id' => $order->id], 201);

        } catch (\Exception $e) {
            DB::rollback(); // Batalkan semua perubahan jika ada error
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    // --- FUNGSI RIWAYAT ORDER ---
    public function history(Request $request)
    {
        $request->validate(['user_id' => 'required']);
        $orders = Order::with('details.product')
                    ->where('user_id', $request->user_id)
                    ->orderBy('created_at', 'desc')
                    ->get();
        return response()->json(['success' => true, 'data' => $orders]);
    }

    // --- [ADMIN] LIHAT SEMUA PESANAN ---
    public function indexAdmin()
    {
        $orders = Order::with(['user', 'details.product'])
                    ->orderBy('created_at', 'desc')
                    ->get();
        return response()->json(['success' => true, 'data' => $orders]);
    }

    // --- [ADMIN] UPDATE STATUS PESANAN ---
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,dibayar,dikirim,selesai,batal']);

        $order = Order::find($id);
        if (!$order) return response()->json(['success' => false, 'message' => 'Order tidak ditemukan'], 404);

        $order->update(['status' => $request->status]);
        return response()->json(['success' => true, 'message' => 'Status pesanan berhasil diperbarui']);
    }

    // --- [ADMIN] DETAIL PESANAN ---
    public function show($id)
    {
        $order = Order::with(['user', 'details.product'])->find($id);
        if (!$order) return response()->json(['success' => false], 404);
        return response()->json(['success' => true, 'data' => $order]);
    }
    
}
