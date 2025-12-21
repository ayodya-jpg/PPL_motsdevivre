<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', sans-serif; padding-bottom: 40px; }
        .card { border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border-radius: 8px; margin-bottom: 20px; }
        .product-thumb { width: 70px; height: 70px; object-fit: cover; border-radius: 6px; border: 1px solid #eee; }
        .status-card { background: linear-gradient(135deg, #343a40, #495057); color: white; }
    </style>
</head>
<body>
    
    <!-- Navbar Admin -->
    <nav class="navbar navbar-dark bg-dark mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/admin/dashboard">Admin Panel</a>
            <a href="/auth/logout" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </nav>

    <div class="container">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-0"><i class="fas fa-file-invoice me-2 text-primary"></i> Order #<?= $order->id ?></h3>
                <span class="text-muted small">Tanggal: <?= date('d M Y, H:i', strtotime($order->created_at)) ?></span>
            </div>
            <a href="/admin/orders" class="btn btn-secondary shadow-sm"><i class="fas fa-arrow-left me-2"></i> Kembali</a>
        </div>

        <div class="row g-4">
            <!-- KOLOM KIRI -->
            <div class="col-lg-8">
                <!-- Rincian Barang -->
                <div class="card">
                    <div class="card-header bg-white fw-bold py-3 border-bottom">Rincian Barang</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Produk</th>
                                        <th>Harga</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end pe-4">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $subtotalProduk = 0;
                                        foreach($order->details as $detail): 
                                            $subtotalItem = $detail->jumlah * $detail->harga_saat_ini;
                                            $subtotalProduk += $subtotalItem;
                                    ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <?php if($detail->product && $detail->product->gambar): ?>
                                                    <img src="http://localhost:8000/storage/<?= $detail->product->gambar ?>" class="product-thumb me-3">
                                                <?php else: ?>
                                                    <div class="product-thumb bg-secondary d-flex align-items-center justify-content-center text-white me-3"><i class="fas fa-image"></i></div>
                                                <?php endif; ?>
                                                <div>
                                                    <div class="fw-bold text-dark"><?= $detail->product->nama_produk ?? 'Produk Dihapus' ?></div>
                                                    <small class="text-muted">ID: <?= $detail->product_id ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Rp <?= number_format($detail->harga_saat_ini, 0, ',', '.') ?></td>
                                        <td class="text-center">x<?= $detail->jumlah ?></td>
                                        <td class="text-end pe-4 fw-bold">Rp <?= number_format($subtotalItem, 0, ',', '.') ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                
                                <!-- FOOTER TABEL RINCIAN BIAYA -->
                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="3" class="text-end border-0 pt-3 text-secondary">Subtotal Produk</td>
                                        <td class="text-end pe-4 border-0 pt-3">Rp <?= number_format($subtotalProduk, 0, ',', '.') ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end border-0 text-secondary">Biaya Pengiriman (<?= esc($order->shipping_method ?? 'Ongkir') ?>)</td>
                                        <td class="text-end pe-4 border-0">Rp <?= number_format($order->shipping_cost, 0, ',', '.') ?></td>
                                    </tr>
                                    
                                    <!-- LOGIKA HITUNG DISKON (Reverse Calculation) -->
                                    <?php 
                                        $totalSeharusnya = $subtotalProduk + $order->shipping_cost;
                                        $diskon = $totalSeharusnya - $order->total_harga;
                                    ?>
                                    
                                    <?php if($diskon > 0): ?>
                                    <tr>
                                        <td colspan="3" class="text-end border-0 text-success fw-bold">Potongan Promo</td>
                                        <td class="text-end pe-4 border-0 text-success fw-bold">-Rp <?= number_format($diskon, 0, ',', '.') ?></td>
                                    </tr>
                                    <?php endif; ?>

                                    <tr class="table-group-divider border-top border-2">
                                        <td colspan="3" class="text-end py-3"><strong>Total Akhir</strong></td>
                                        <td class="text-end pe-4 py-3 text-primary fw-bold fs-5">
                                            Rp <?= number_format($order->total_harga, 0, ',', '.') ?>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Info Pengiriman -->
                <div class="card">
                    <div class="card-header bg-white fw-bold py-3 border-bottom">Informasi Pengiriman</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="small text-muted fw-bold text-uppercase mb-1">Metode Pengiriman</label>
                                <div class="fw-bold text-dark fs-5"><?= esc($order->shipping_method ?? '-') ?></div>
                                <div class="badge bg-info text-dark mt-1 shadow-sm">
                                    <i class="fas fa-clock me-1"></i> <?= esc($order->shipping_estimation ?? '-') ?>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small text-muted fw-bold text-uppercase mb-1">Alamat Penerima</label>
                                <div class="fw-bold text-dark"><?= esc($order->user->name ?? 'Guest') ?></div>
                                <div class="text-secondary mt-2 p-3 bg-light rounded border" style="line-height: 1.6;">
                                    <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                    <?= nl2br(esc($order->delivery_address ?? '-')) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN (Status & Info) -->
            <div class="col-lg-4">
                <!-- Status Card -->
                <div class="card status-card shadow mb-4">
                    <div class="card-body text-center py-4">
                        <h6 class="text-uppercase opacity-75 mb-2 small">Status Saat Ini</h6>
                        <h2 class="fw-bold text-uppercase mb-0"><?= $order->status ?></h2>
                    </div>
                </div>

                <!-- Form Update Status -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white fw-bold py-3 border-bottom">Update Status</div>
                    <div class="card-body p-4">
                        <form action="/admin/orders/update-status/<?= $order->id ?>" method="post">
                            <div class="mb-3">
                                <select name="status" class="form-select form-select-lg border-primary">
                                    <option value="pending" <?= ($order->status == 'pending') ? 'selected' : '' ?>>🟠 Pending</option>
                                    <option value="dikirim" <?= ($order->status == 'dikirim') ? 'selected' : '' ?>>🔵 Dikirim</option>
                                    <option value="selesai" <?= ($order->status == 'selesai') ? 'selected' : '' ?>>🟢 Selesai</option>
                                    <option value="batal" <?= ($order->status == 'batal') ? 'selected' : '' ?>>🔴 Batalkan</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>

                <!-- Info Pelanggan -->
                <div class="card">
                    <div class="card-header bg-white fw-bold py-3 border-bottom">Data Pelanggan</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="small text-muted d-block">Nama</label>
                            <strong><?= esc($order->user->name ?? 'Guest') ?></strong>
                        </div>
                        <div class="mb-3">
                            <label class="small text-muted d-block">Email</label>
                            <span><?= esc($order->user->email ?? '-') ?></span>
                        </div>
                        <div class="mb-0">
                            <label class="small text-muted d-block">Metode Bayar</label>
                            <span class="badge bg-secondary"><?= esc($order->payment_method ?? '-') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>