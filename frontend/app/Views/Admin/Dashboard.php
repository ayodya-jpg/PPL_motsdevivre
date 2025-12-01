<!DOCTYPE html>
<html>
<head>
    <title><?= esc($title) ?></title>
    <!-- Menggunakan Bootstrap CSS untuk tampilan yang lebih rapi -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            padding: 20px; 
            background-color: #f4f6f9;
        }
        .header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 25px; 
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        h1 { margin: 0; font-size: 24px; font-weight: bold; color: #333; }
        
        /* Card & Table Styles */
        .card-box {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 40px;
            border: none;
        }
        
        .table thead th {
            background-color: #343a40;
            color: white;
            font-weight: 500;
            border: none;
            padding: 15px;
        }
        
        .table tbody td {
            padding: 12px 15px;
            vertical-align: middle;
        }

        /* Status Badges */
        .badge-pending { background-color: #ffc107; color: #000; }
        .badge-dikirim { background-color: #0d6efd; color: #fff; }
        .badge-selesai { background-color: #198754; color: #fff; }
        .badge-batal { background-color: #dc3545; color: #fff; }

        /* Thumb Image */
        .thumb-img {
            width: 40px; height: 40px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;
        }
        
        h3 { margin-top: 0; margin-bottom: 20px; color: #333; font-weight: 600; font-size: 1.25rem; }
    </style>
</head>
<body>
    
    <!-- HEADER -->
    <div class="header">
        <div>
            <h1>Dashboard Admin</h1>
            <p class="text-muted mb-0">Selamat Datang, <strong><?= esc(session()->get('name')) ?></strong></p>
        </div>
        <a href="/auth/logout" class="btn btn-danger">
            <i class="fas fa-sign-out-alt me-2"></i> Logout
        </a>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success mb-4"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger mb-4"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>


    <!-- ========================================== -->
    <!-- TABEL 1: STOK PRODUK -->
    <!-- ========================================== -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>📦 Stok Gudang Real-time</h3>
        <a href="/admin/products/create" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Tambah Produk
        </a>
    </div>
    
    <div class="card card-box">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>


    <!-- ========================================== -->
    <!-- TABEL 2: PESANAN MASUK (BARU) -->
    <!-- ========================================== -->
    <div class="d-flex justify-content-between align-items-center mb-3 mt-5">
        <h3>🛒 Pesanan Terbaru (Incoming Orders)</h3>
        <a href="/admin/orders" class="text-decoration-none fw-bold">
            Lihat Semua Pesanan <i class="fas fa-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="card card-box">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background-color: #0d6efd !important;">
                    <tr>
                        <th class="ps-4">ID Order</th>
                        <th>Pelanggan</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($orders)): ?>
                        <?php foreach($orders as $order): ?>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>