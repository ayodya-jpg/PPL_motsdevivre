<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .main-card { border: none; box-shadow: 0 0 15px rgba(0,0,0,0.05); border-radius: 10px; }
        .table th { background-color: #343a40; color: white; font-weight: 500; }
        .badge-pending { background-color: #ffc107; color: #000; }
        .badge-dibayar { background-color: #17a2b8; color: #fff; }
        .badge-dikirim { background-color: #0d6efd; color: #fff; }
        .badge-selesai { background-color: #198754; color: #fff; }
        .badge-batal { background-color: #dc3545; color: #fff; }
    </style>
</head>
<body>
    
    <!-- Navbar Admin Sederhana -->
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/admin/dashboard">Admin Panel</a>
            <a href="/auth/logout" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </nav>

    <div class="container pb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Kelola Pesanan</h2>
                <p class="text-muted mb-0">Daftar transaksi masuk dari pelanggan.</p>
            </div>
            <a href="/admin/dashboard" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left me-2"></i> Dashboard
            </a>
        </div>

        <!-- Pesan Sukses/Error -->
        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="card main-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th class="ps-4 py-3">ID Order</th>
                                <th class="py-3">Pelanggan</th>
                                <th class="py-3">Tanggal</th>
                                <th class="py-3">Total</th>
                                <th class="py-3">Status</th>
                                <th class="py-3 text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($orders)): ?>
                                <?php foreach($orders as $order): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-primary">#<?= $order->id ?></td>
                                    <td>
                                        <div class="fw-bold"><?= esc($order->user->name ?? 'Guest') ?></div>
                                        <small class="text-muted" style="font-size: 0.85rem;"><?= esc($order->user->email ?? '-') ?></small>
                                    </td>
                                    <td>
                                        <i class="far fa-calendar-alt me-1 text-muted"></i>
                                        <?= date('d M Y H:i', strtotime($order->created_at)) ?>
                                    </td>
                                    <td class="fw-bold text-success">
                                        Rp <?= number_format($order->total_harga, 0, ',', '.') ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?= strtolower($order->status) ?> text-uppercase px-3 py-2 rounded-pill">
                                            <?= $order->status ?>
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="/admin/orders/show/<?= $order->id ?>" class="btn btn-sm btn-primary shadow-sm">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </a>
                                    </td>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-box-open fa-3x mb-3"></i>
                                        <h5>Belum ada pesanan masuk.</h5>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>