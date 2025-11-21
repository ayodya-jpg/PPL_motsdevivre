<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function checkout(Request $request)
    {
        // Menggunakan Database Transaction agar aman
        DB::beginTransaction();
        try {
            // 1. Buat Order Baru
            $order = Order::create([
                'user_id' => $request->user_id,
                'total_harga' => $request->total_harga,
                'status' => 'pending'
            ]);

            // 2. Loop Barang yang dibeli
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);

                // Cek Stok
                if($product->stok < $item['jumlah']) {
                    throw new \Exception("Stok " . $product->nama_produk . " habis.");
                }

                // 3. KURANGI STOK (Integrasi Realtime)
                $product->decrement('stok', $item['jumlah']);

                // Simpan Detail
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'jumlah' => $item['jumlah'],
                    'harga_saat_ini' => $product->harga
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Transaksi Berhasil', 'order_id' => $order->id]);

        } catch (\Exception $e) {
            DB::rollback(); // Batalkan semua jika error
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    // Untuk Admin melihat semua order
    public function index() {
        return response()->json(Order::with('details')->get());
    }
}
