<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\UserPromo;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Checkout: Memproses pembelian produk.
     * Status Awal: 'unpaid' (Menunggu Pembayaran)
     */
    public function checkout(Request $request)
    {
        DB::beginTransaction();
        try {
            // Setup Midtrans
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            // Sanitize total
            $totalRaw = preg_replace('/[^0-9]/', '', $request->total_harga);
            $total = (int) $totalRaw;

            // Generate unique transaction ID
            $transactionId = 'TJS-' . time() . '-' . rand(1000, 9999);

            // 1. Buat Order Baru
            // UPDATE: Status awal 'unpaid' agar masuk ke logika "Menunggu Pembayaran"
            $order = Order::create([
                'user_id' => $request->user_id,
                'total_harga' => $total,
                'status' => 'unpaid',
                'transaction_id' => $transactionId,
                'snap_token' => null
            ]);

            // 2. Loop Barang yang dibeli
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);

                if (!$product || $product->stok < $item['jumlah']) {
                    throw new \Exception("Stok " . ($product ? $product->nama_produk : 'Produk') . " habis.");
                }

                // 3. KURANGI STOK
                $product->decrement('stok', $item['jumlah']);

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'jumlah' => $item['jumlah'],
                    'harga_saat_ini' => $product->harga
                ]);
            }

            // 4. Generate Midtrans Snap Token
            $params = [
                'transaction_details' => [
                    'order_id' => $transactionId,
                    'gross_amount' => $total,
                ],
                'customer_details' => [
                    'first_name' => $request->name ?? 'Customer',
                    'email' => $request->email ?? 'customer@example.com',
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // 5. Update order dengan snap_token
            $order->snap_token = $snapToken;
            $order->save();

            // 6. Proses Voucher
            if ($request->has('promo_codes') && !empty($request->promo_codes)) {
                UserPromo::where('user_id', $request->user_id)
                    ->whereIn('promo_code', $request->promo_codes)
                    ->update([
                        'is_used' => true,
                        'updated_at' => now()
                    ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order berhasil dibuat',
                'order_id' => $order->id,
                'snap_token' => $snapToken,
                'transaction_id' => $transactionId
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get Order History
     * UPDATE: Menampilkan SEMUA status (termasuk unpaid) agar user bisa bayar ulang.
     */
    public function history(Request $request)
    {
        $userId = $request->input('user_id');

        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'User ID required'], 400);
        }

        try {
            $orders = Order::with(['details.product', 'user'])
                ->where('user_id', $userId)
                // Kita HAPUS filter '!= unpaid' agar order yang belum dibayar tetap muncul
                ->orderByDesc('created_at')
                ->get();

            return response()->json(['success' => true, 'data' => $orders]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Check Payment Status (Callback/Sync Midtrans)
     * UPDATE: Mapping status agar sesuai alur bisnis.
     * Settlement -> Pending (Sedang Dikemas)
     */
    public function checkPaymentStatus($orderId)
    {
        try {
            $order = Order::find($orderId);

            if (!$order || !$order->transaction_id) {
                return response()->json(['success' => false, 'message' => 'Order not found'], 404);
            }

            // Setup Midtrans
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

            // Query status ke Midtrans API
            $status = \Midtrans\Transaction::status($order->transaction_id);

            // LOGIKA UPDATE STATUS:

            // 1. Jika LUNAS (Settlement/Capture) -> Ubah ke 'pending'
            // Definisi bisnis Anda: 'Pending' = 'Sedang Dikemas' (Masuk Tab Berjalan)
            if ($status->transaction_status == 'settlement' || $status->transaction_status == 'capture') {
                $order->status = 'pending';
                $order->payment_type = $status->payment_type;
                $order->paid_at = now();
            }
            // 2. Jika Masih Menunggu Pembayaran di Midtrans -> Ubah ke 'unpaid'
            elseif ($status->transaction_status == 'pending') {
                $order->status = 'unpaid';
            }
            // 3. Jika Gagal/Kadaluarsa -> Ubah ke 'batal'
            elseif (in_array($status->transaction_status, ['deny', 'expire', 'cancel'])) {
                $order->status = 'batal';
            }

            $order->save();

            return response()->json([
                'success' => true,
                'order' => $order->load(['details.product']),
                'midtrans_status' => $status->transaction_status
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // --- FUNGSI ADMIN & LAINNYA (TIDAK DIHAPUS) ---

    public function index()
    {
        return response()->json(Order::with('details.product')->get());
    }

    public function indexAdmin()
    {
        try {
            $orders = Order::with(['user', 'details.product'])->orderBy('created_at', 'desc')->get();
            return response()->json($orders, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $order = Order::with(['user', 'details.product'])->find($id);
            if (!$order) return response()->json(['error' => 'Not found'], 404);
            return response()->json($order, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $order = Order::find($id);
            if (!$order) return response()->json(['success' => false, 'message' => 'Order not found'], 404);

            $validStatuses = ['pending', 'dikirim', 'selesai', 'batal', 'unpaid'];
            if (!in_array($request->status, $validStatuses)) {
                return response()->json(['success' => false, 'message' => 'Invalid status'], 400);
            }

            $order->status = $request->status;
            $order->save();

            return response()->json(['success' => true, 'message' => 'Status updated', 'data' => $order]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
