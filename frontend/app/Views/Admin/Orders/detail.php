<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card { border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border-radius: 8px; }
        .product-thumb { width: 70px; height: 70px; object-fit: cover; border-radius: 6px; border: 1px solid #eee; }
        
        .status-card { 
            background: linear-gradient(135deg, #343a40, #495057); 
            color: white; 
        }
        
        /* Warna Status Badge */
        .badge-pending { background-color: #ffc107; color: #000; }
        .badge-dikirim { background-color: #0d6efd; color: #fff; }
        .badge-selesai { background-color: #198754; color: #fff; }
        .badge-batal { background-color: #dc3545; color: #fff; }
    </style>
</head>
<body>
    
    <div class="container py-5">
        
        <!-- Header & Navigasi -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">
                <i class="fas fa-file-invoice me-2 text-primary"></i> Order #<?= $order->id ?>
            </h3>
            <a href="/admin/orders" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke List
            </a>
        </div>

        <!-- Pesan Sukses Update Status -->
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            
            <!-- KOLOM KIRI: RINCIAN BARANG & PENGIRIMAN -->
            <div class="col-lg-8">
                
                <!-- 1. Daftar Produk -->
                <div class="card mb-4">
                    <div class="card-header bg-white fw-bold py-3 border-bottom">
                        <i class="fas fa-box-open me-2 text-muted"></i> Rincian Barang Pesanan
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Produk</th>
                                        <th>Harga Satuan</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end pe-4">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($order->details as $detail): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <?php if($detail->product && $detail->product->gambar): ?>
                                                    <img src="http://localhost:8000/storage/<?= $detail->product->gambar ?>" class="product-thumb me-3 shadow-sm">
                                                <?php else: ?>
                                                    <div class="product-thumb bg-secondary d-flex align-items-center justify-content-center text-white me-3"><i class="fas fa-image"></i></div>
                                                <?php endif; ?>
                                                <div>
                                                    <div class="fw-bold text-dark"><?= $detail->product->nama_produk ?? '<span class="text-danger fst-italic">Produk Dihapus</span>' ?></div>
                                                    <small class="text-muted">ID: <?= $detail->product_id ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Rp <?= number_format($detail->harga_saat_ini, 0, ',', '.') ?></td>
                                        <td class="text-center">x<?= $detail->jumlah ?></td>
                                        <td class="text-end pe-4 fw-bold text-primary">
                                            Rp <?= number_format($detail->jumlah * $detail->harga_saat_ini, 0, ',', '.') ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end py-3"><strong>Total Pembayaran (Termasuk Ongkir)</strong></td>
                                        <td class="text-end pe-4 py-3 text-success fw-bold fs-5">
                                            Rp <?= number_format($order->total_harga, 0, ',', '.') ?>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 2. Info Pengiriman -->
                <div class="card">
                    <div class="card-header bg-white fw-bold py-3 border-bottom">
                        <i class="fas fa-shipping-fast me-2 text-muted"></i> Informasi Pengiriman
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="small text-muted fw-bold text-uppercase mb-1">Metode Pengiriman</label>
                                <div class="fw-bold text-dark fs-5"><?= esc($order->shipping_method ?? '-') ?></div>
                                <div class="badge bg-info text-dark mt-2 shadow-sm">
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

            <!-- KOLOM KANAN: PANEL UPDATE STATUS & INFO USER -->
            <div class="col-lg-4">
                
                <!-- Kartu Status (Highlight) -->
                <div class="card status-card mb-4 shadow">
                    <div class="card-body text-center py-4">
                        <h6 class="text-uppercase opacity-75 mb-2 small letter-spacing-1">Status Pesanan Saat Ini</h6>
                        <h2 class="fw-bold text-uppercase mb-0 display-6">
                            <?= $order->status ?>
                        </h2>
                    </div>
                </div>

                <!-- Form Update Status -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white fw-bold py-3 border-bottom">
                        <i class="fas fa-edit me-2 text-muted"></i> Update Status
                    </div>
                    <div class="card-body p-4">
                        <form action="/admin/orders/update-status/<?= $order->id ?>" method="post">
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Pilih Status Baru:</label>
                                <select name="status" class="form-select form-select-lg border-primary" required>
                                    <option value="pending" <?= ($order->status == 'pending') ? 'selected' : '' ?>>🟠 Pending (Menunggu)</option>
                                    <option value="dikirim" <?= ($order->status == 'dikirim') ? 'selected' : '' ?>>🔵 Dikirim (Sedang Diantar)</option>
                                    <option value="selesai" <?= ($order->status == 'selesai') ? 'selected' : '' ?>>🟢 Selesai (Diterima)</option>
                                    <option value="batal" <?= ($order->status == 'batal') ? 'selected' : '' ?>>🔴 Batalkan Pesanan</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Info Pelanggan -->
                <div class="card">
                    <div class="card-header bg-white fw-bold py-3 border-bottom">
                        <i class="fas fa-user me-2 text-muted"></i> Data Pelanggan
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded-circle p-3 me-3 border shadow-sm">
                                <i class="fas fa-user fa-lg text-secondary"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark"><?= esc($order->user->name ?? 'Guest') ?></div>
                                <div class="small text-muted"><?= esc($order->user->email ?? '-') ?></div>
                                <div class="small text-muted mt-1">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    Join: <?= date('d M Y', strtotime($order->user->created_at ?? 'now')) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>