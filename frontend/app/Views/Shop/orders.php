<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    
    <!-- CSS Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body { transition: background-color 0.3s, color 0.3s; }
        
        /* Header Style */
        .nav-custom .nav-link { font-size: 0.9rem; display: flex; flex-direction: column; align-items: center; padding: 0.5rem 1rem; opacity: 0.7; }
        .nav-custom .nav-link:hover, .nav-custom .nav-link.active { opacity: 1; }
        .nav-custom .nav-link i { font-size: 1.2rem; margin-bottom: 4px; }
        
        /* Order Card Style */
        .order-card {
            border: 1px solid rgba(128,128,128,0.2);
            margin-bottom: 20px;
            border-radius: 8px;
            overflow: hidden;
            transition: box-shadow 0.3s;
        }
        .order-card:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        
        .order-header {
            background-color: rgba(13, 110, 253, 0.05);
            padding: 15px;
            border-bottom: 1px solid rgba(128,128,128,0.2);
        }
        .order-body { padding: 20px; }
        
        .product-thumb {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid rgba(128,128,128,0.2);
        }
        
        /* Status Badge Colors */
        .badge-pending { background-color: #ffc107; color: #000; }
        .badge-dikirim { background-color: #0d6efd; color: #fff; }
        .badge-selesai { background-color: #198754; color: #fff; }
        .badge-batal { background-color: #dc3545; color: #fff; }
        
        /* Info Pengiriman */
        .shipping-info {
            background-color: rgba(13, 202, 240, 0.1); 
            border-left: 4px solid #0dcaf0;
            color: var(--bs-body-color);
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <header class="mb-4 shadow-sm bg-body-tertiary">
        <div class="header-top py-2 border-bottom border-secondary border-opacity-25">
            <div class="container d-flex justify-content-between align-items-center">
                <a class="navbar-brand fw-bold d-flex align-items-center text-decoration-none fs-4" href="/shop">
                    <span class="bg-warning text-white rounded px-2 me-2 fs-5 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">VR</span> 
                    <span class="text-body">Mots De Vivre</span>
                </a>
                <div class="d-flex align-items-center gap-3">
                    <nav class="nav nav-custom d-none d-md-flex">
                        <a class="nav-link text-body" href="/shop"><i class="bi bi-house"></i> Home</a>
                        <a class="nav-link text-body position-relative" href="/cart">
                            <i class="bi bi-bag"></i></i> Cart
                            <?php if(isset($cart_count) && $cart_count > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;"><?= $cart_count ?></span>
                            <?php endif; ?>
                        </a>
                        <!-- MENU ORDERS AKTIF -->
                        <a class="nav-link active text-warning fw-bold" href="/orders"><i class="bi bi-receipt"></i> Orders</a>
                        <a class="nav-link text-body" href="/profile"><i class="bi bi-person-fill"></i> Profile</a>
                    </nav>
                    <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" id="themeToggle"><i class="fas fa-sun" id="themeIcon"></i></button>
                </div>
            </div>
        </div>
    </header>

    <!-- KONTEN UTAMA -->
    <div class="container pb-5">
        <h2 class="mb-4 fw-bold"><i class="bi bi-clock-history me-2"></i> Riwayat Pesanan</h2>

        <!-- TABS -->
        <ul class="nav nav-pills mb-4 gap-2" id="orderTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold px-4" id="ongoing-tab" data-bs-toggle="tab" data-bs-target="#ongoing" type="button" role="tab">
                    Sedang Berjalan 
                    <?php if(count($ongoing) > 0): ?>
                        <span class="badge bg-warning text-dark ms-2"><?= count($ongoing) ?></span>
                    <?php endif; ?>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold px-4" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab">
                    Selesai 
                    <?php if(count($completed) > 0): ?>
                        <span class="badge bg-secondary ms-2"><?= count($completed) ?></span>
                    <?php endif; ?>
                </button>
            </li>
        </ul>

        <div class="tab-content" id="orderTabsContent">
            
            <!-- TAB 1: SEDANG BERJALAN -->
            <div class="tab-pane fade show active" id="ongoing" role="tabpanel">
                <?php if(empty($ongoing)): ?>
                    <div class="text-center py-5 opacity-75">
                        <i class="bi bi-box2 fa-4x mb-3 d-block"></i>
                        <h5>Tidak ada pesanan yang sedang berjalan.</h5>
                        <a href="/shop" class="btn btn-primary mt-3">Mulai Belanja</a>
                    </div>
                <?php else: ?>
                    <?php foreach($ongoing as $order): ?>
                        <div class="order-card bg-body">
                            <!-- Header Kartu -->
                            <div class="order-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <span class="fw-bold me-2"><i class="bi bi-receipt me-1"></i> Order #<?= $order->id ?></span>
                                    <small class="text-muted">
                                        <!-- PERBAIKAN JAM (UTC ke WIB) -->
                                        <i class="bi bi-calendar-event me-1"></i> 
                                        <?php 
                                            // Konversi Timezone dari UTC ke Asia/Jakarta
                                            $date = new DateTime($order->created_at, new DateTimeZone('UTC'));
                                            $date->setTimezone(new DateTimeZone('Asia/Jakarta'));
                                            echo $date->format('d M Y, H:i'); // Format: 26 Nov 2025, 19:24
                                        ?> WIB
                                    </small>
                                </div>
                                <span class="badge badge-<?= strtolower($order->status) ?> text-uppercase px-3 py-2 rounded-pill">
                                    <?= $order->status ?>
                                </span>
                            </div>
                            
                            <!-- Isi Order -->
                            <div class="order-body">
                                <!-- List Produk -->
                                <?php foreach($order->details as $detail): ?>
                                <div class="d-flex align-items-start mb-3 pb-3 border-bottom last-no-border">
                                    <?php if($detail->product && $detail->product->gambar): ?>
                                        <img src="http://localhost:8000/storage/<?= $detail->product->gambar ?>" class="product-thumb me-3 shadow-sm">
                                    <?php else: ?>
                                        <div class="product-thumb me-3 bg-secondary d-flex align-items-center justify-content-center text-white"><i class="fas fa-image"></i></div>
                                    <?php endif; ?>
                                    
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold"><?= $detail->product->nama_produk ?? '<span class="text-danger">Produk Dihapus</span>' ?></h6>
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <small class="text-muted"><?= $detail->jumlah ?> barang x Rp <?= number_format($detail->harga_saat_ini, 0, ',', '.') ?></small>
                                            <span class="fw-bold">Rp <?= number_format($detail->jumlah * $detail->harga_saat_ini, 0, ',', '.') ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                
                                <!-- INFO PENGIRIMAN (Tampilkan Langsung dari Database) -->
                                <div class="alert shipping-info py-2 px-3 mt-3 mb-0">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-truck me-3 fs-3 text-info"></i>
                                        <div class="flex-grow-1">
                                            <small class="d-block text-muted fw-bold">
                                                Metode: <?= esc($order->shipping_method ?? '-') ?>
                                            </small>
                                            <!-- INI DATA ESTIMASI YANG TERSIMPAN SAAT CHECKOUT -->
                                            <span class="fw-bold text-body">
                                                <?= esc($order->shipping_estimation ?? 'Estimasi tidak tersedia') ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer Total -->
                                <div class="d-flex justify-content-between align-items-center mt-3 pt-2">
                                    <div>
                                        <small class="text-muted d-block">Metode Pembayaran</small>
                                        <span class="fw-bold small"><i class="bi bi-credit-card me-1"></i> <?= esc($order->payment_method ?? '-') ?></span>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted">Total Pesanan</small>
                                        <h4 class="fw-bold text-primary mb-0">Rp <?= number_format($order->total_harga, 0, ',', '.') ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- TAB 2: SELESAI -->
            <div class="tab-pane fade" id="completed" role="tabpanel">
                <?php if(empty($completed)): ?>
                    <div class="text-center py-5 opacity-75">
                        <i class="bi bi-clipboard-check fa-4x mb-3 d-block"></i>
                        <h5>Belum ada riwayat pesanan selesai.</h5>
                    </div>
                <?php else: ?>
                    <?php foreach($completed as $order): ?>
                        <div class="order-card bg-body opacity-75">
                            <div class="order-header d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-bold me-2">Order #<?= $order->id ?></span>
                                    <small class="text-muted">
                                        <?php 
                                            $date = new DateTime($order->created_at, new DateTimeZone('UTC'));
                                            $date->setTimezone(new DateTimeZone('Asia/Jakarta'));
                                            echo $date->format('d M Y, H:i'); 
                                        ?> WIB
                                    </small>
                                </div>
                                <!-- LOGIKA BADGE DINAMIS -->
                            <?php if ($order->status == 'batal'): ?>
                                <span class="badge badge-batal text-uppercase px-3 py-2">DIBATALKAN</span>
                            <?php else: ?>
                                <span class="badge badge-selesai text-uppercase px-3 py-2">SELESAI</span>
                            <?php endif; ?>
                            </div>
                            <div class="order-body">
                                <?php foreach($order->details as $detail): ?>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="flex-grow-1">
                                        <span class="fw-bold small"><?= $detail->product->nama_produk ?? 'Produk Dihapus' ?></span>
                                        <span class="text-muted small ms-2">x<?= $detail->jumlah ?></span>
                                    </div>
                                    <span class="small fw-bold">Rp <?= number_format($detail->jumlah * $detail->harga_saat_ini, 0, ',', '.') ?></span>
                                </div>
                                <?php endforeach; ?>
                                <hr class="my-2">
                                <div class="text-end">
                                    <span class="text-muted small me-2">Total:</span>
                                    <span class="fw-bold">Rp <?= number_format($order->total_harga, 0, ',', '.') ?></span>
                                </div>
                                <div class="text-end mt-3">
                                    <button class="btn btn-sm btn-outline-primary">Beli Lagi</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script Tema -->
    <script>
        const themeToggleBtn = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const htmlElement = document.documentElement;
        const currentTheme = localStorage.getItem('theme') || 'dark';
        setTheme(currentTheme);
        themeToggleBtn.addEventListener('click', () => {
            const newTheme = htmlElement.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            setTheme(newTheme);
            localStorage.setItem('theme', newTheme);
        });
        function setTheme(theme) {
            htmlElement.setAttribute('data-bs-theme', theme);
            if (theme === 'dark') { themeIcon.classList.remove('fa-moon'); themeIcon.classList.add('fa-sun'); } 
            else { themeIcon.classList.remove('fa-sun'); themeIcon.classList.add('fa-moon'); }
        }
        
    </script>
</body>
</html>