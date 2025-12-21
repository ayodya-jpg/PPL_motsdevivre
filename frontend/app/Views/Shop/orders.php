<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    
    <!-- CSS Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Font (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { 
            font-family: 'Inter', sans-serif;
            transition: background-color 0.3s, color 0.3s;
            background-color: var(--bs-tertiary-bg);
        }
        
        /* --- HEADER STYLE --- */
        .nav-custom .nav-link { font-size: 0.9rem; display: flex; flex-direction: column; align-items: center; padding: 0.5rem 1rem; opacity: 0.7; transition: 0.2s; }
        .nav-custom .nav-link:hover, .nav-custom .nav-link.active { opacity: 1; transform: translateY(-2px); }
        .nav-custom .nav-link i { font-size: 1.3rem; margin-bottom: 4px; }

        /* --- MODERN ORDER CARD --- */
        .order-card {
            background-color: var(--bs-body-bg);
            border: 1px solid var(--bs-border-color-translucent);
            border-radius: 1rem;
            overflow: hidden;
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }
        
        .order-card:hover {
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }

        .order-header {
            padding: 1.25rem;
            border-bottom: 1px solid var(--bs-border-color-translucent);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            background-color: rgba(var(--bs-tertiary-bg-rgb), 0.5);
        }

        .order-body { padding: 1.25rem; }

        .product-item {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px dashed var(--bs-border-color-translucent);
        }
        .product-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .product-thumb {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 0.75rem;
            border: 1px solid var(--bs-border-color);
        }

        /* --- MODERN BADGES --- */
        .badge-soft {
            padding: 0.5em 1em;
            border-radius: 50rem;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-pending { background-color: rgba(255, 193, 7, 0.15); color: #ffc107; border: 1px solid rgba(255, 193, 7, 0.2); }
        .status-dikirim { background-color: rgba(13, 110, 253, 0.15); color: #0d6efd; border: 1px solid rgba(13, 110, 253, 0.2); }
        .status-selesai { background-color: rgba(25, 135, 84, 0.15); color: #198754; border: 1px solid rgba(25, 135, 84, 0.2); }
        .status-batal   { background-color: rgba(220, 53, 69, 0.15); color: #dc3545; border: 1px solid rgba(220, 53, 69, 0.2); }

        [data-bs-theme="light"] .status-pending { color: #997404; background-color: #fff3cd; }
        [data-bs-theme="light"] .status-dikirim { color: #084298; background-color: #cfe2ff; }
        [data-bs-theme="light"] .status-selesai { color: #0f5132; background-color: #d1e7dd; }
        [data-bs-theme="light"] .status-batal   { color: #842029; background-color: #f8d7da; }

        /* --- SHIPPING INFO BOX --- */
        .shipping-box {
            background-color: rgba(13, 202, 240, 0.08);
            border-radius: 0.75rem;
            padding: 1rem;
            margin-top: 1rem;
            border: 1px solid rgba(13, 202, 240, 0.2);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* --- NAV TABS --- */
        .nav-pills .nav-link {
            border-radius: 50rem;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            color: var(--bs-body-color);
            transition: 0.3s;
            border: 1px solid transparent;
        }
        .nav-pills .nav-link:hover { background-color: var(--bs-secondary-bg); }
        .nav-pills .nav-link.active {
            background-color: var(--bs-primary);
            color: white;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <header class="mb-5 shadow-sm bg-body-tertiary sticky-top">
        <div class="header-top py-2 border-bottom border-secondary border-opacity-10">
            <div class="container d-flex justify-content-between align-items-center">
                <a class="navbar-brand fw-bold d-flex align-items-center text-decoration-none fs-4" href="/shop">
                    <span class="bg-warning text-white rounded-3 px-2 me-2 fs-5 d-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px;">VR</span> 
                    <span class="text-body fw-bold">Mots De Vivre</span>
                </a>
                
                <div class="d-flex align-items-center gap-3">
                    <nav class="nav nav-custom d-none d-md-flex">
                        <a class="nav-link text-body" href="/shop"><i class="bi bi-house"></i> Home</a>
                        <a class="nav-link text-body position-relative" href="/cart">
                            <i class="bi bi-bag"></i> Cart
                            <?php if(isset($cart_count) && $cart_count > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm border border-2 border-white" style="font-size: 0.6rem;"><?= $cart_count ?></span>
                            <?php endif; ?>
                        </a>
                        <a class="nav-link active text-warning fw-bold" href="/orders"><i class="bi bi-receipt"></i> Orders</a>
                        <a class="nav-link text-body" href="/profile"><i class="bi bi-person-fill"></i> Profile</a>
                    </nav>
                    <button class="btn btn-outline-secondary btn-sm rounded-circle p-2" style="width: 38px; height: 38px;" id="themeToggle"><i class="fas fa-sun" id="themeIcon"></i></button>
                    
                    <button class="btn btn-outline-secondary d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu"><i class="fas fa-bars"></i></button>
                </div>
            </div>
        </div>
    </header>

    <!-- KONTEN UTAMA -->
    <div class="container pb-5">
        
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h2 class="fw-bold m-0 text-body-emphasis"><i class="bi bi-clock-history me-2 text-warning"></i> Riwayat Pesanan</h2>
        </div>

        <!-- TABS NAVIGASI -->
        <ul class="nav nav-pills mb-4 gap-2" id="orderTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="ongoing-tab" data-bs-toggle="tab" data-bs-target="#ongoing" type="button" role="tab">
                    Sedang Berjalan 
                    <?php if(count($ongoing) > 0): ?>
                        <span class="badge bg-white text-primary ms-2 shadow-sm border"><?= count($ongoing) ?></span>
                    <?php endif; ?>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab">
                    Selesai / Batal
                    <?php if(count($completed) > 0): ?>
                        <span class="badge bg-secondary bg-opacity-25 text-body ms-2"><?= count($completed) ?></span>
                    <?php endif; ?>
                </button>
            </li>
        </ul>

        <div class="tab-content" id="orderTabsContent">
            
            <!-- TAB 1: on going-->
            <div class="tab-pane fade show active" id="ongoing" role="tabpanel">
                <?php if(empty($ongoing)): ?>
                    <div class="text-center py-5 opacity-50">
                        <i class="bi bi-box2 fa-4x mb-3 d-block text-secondary"></i>
                        <h5 class="fw-light">Tidak ada pesanan yang sedang berjalan.</h5>
                        <a href="/shop" class="btn btn-primary mt-3 rounded-pill px-4 fw-bold">Mulai Belanja</a>
                    </div>
                <?php else: ?>
                    <?php foreach($ongoing as $order): ?>
                        <div class="order-card shadow-sm">
                            <div class="order-header">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded-circle text-primary">
                                        <i class="bi bi-receipt fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">Order #<?= $order->id ?></div>
                                        <small class="text-secondary d-flex align-items-center gap-1">
                                            <i class="bi bi-calendar3"></i>
                                            <?php 
                                                try {
                                                    $date = new DateTime($order->created_at, new DateTimeZone('UTC'));
                                                    $date->setTimezone(new DateTimeZone('Asia/Jakarta'));
                                                    echo $date->format('d M Y, H:i');
                                                } catch(Exception $e) { echo date('d M Y, H:i', strtotime($order->created_at)); }
                                            ?> WIB
                                        </small>
                                    </div>
                                </div>
                                <span class="badge-soft status-<?= strtolower($order->status) ?>">
                                    <?= strtoupper($order->status) ?>
                                </span>
                            </div>
                            
                            <div class="order-body">
                                <!-- HITUNG SUBTOTAL PRODUK -->
                                <?php 
                                    $subtotalProduk = 0;
                                    foreach($order->details as $detail): 
                                        $subtotalProduk += $detail->jumlah * $detail->harga_saat_ini;
                                ?>
                                <div class="product-item">
                                    <?php if($detail->product && $detail->product->gambar): ?>
                                        <img src="http://localhost:8000/storage/<?= $detail->product->gambar ?>" class="product-thumb">
                                    <?php else: ?>
                                        <div class="product-thumb bg-secondary d-flex align-items-center justify-content-center text-white"><i class="bi bi-image"></i></div>
                                    <?php endif; ?>
                                    
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <h6 class="fw-bold text-body-emphasis mb-0"><?= $detail->product->nama_produk ?? 'Produk Dihapus' ?></h6>
                                            <span class="fw-bold text-body">Rp <?= number_format($detail->jumlah * $detail->harga_saat_ini, 0, ',', '.') ?></span>
                                        </div>
                                        <div class="text-secondary small">
                                            <?= $detail->jumlah ?> barang x Rp <?= number_format($detail->harga_saat_ini, 0, ',', '.') ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                
                                <!-- INFO ESTIMASI -->
                                <div class="shipping-box">
                                    <div class="text-info fs-3"><i class="bi bi-truck"></i></div>
                                    <div class="flex-grow-1">
                                        <small class="text-secondary text-uppercase fw-bold" style="font-size: 0.7rem;">Pengiriman</small>
                                        <div class="fw-bold text-body-emphasis"><?= esc($order->shipping_method ?? '-') ?></div>
                                        <div class="text-info fw-semibold small">
                                            <?= esc($order->shipping_estimation ?? 'Estimasi tidak tersedia') ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- RINCIAN BIAYA & DISKON -->
                                <div class="bg-body-tertiary p-3 rounded-3 mt-3">
                                    <div class="d-flex justify-content-between mb-1 small text-secondary">
                                        <span>Subtotal Produk</span>
                                        <span>Rp <?= number_format($subtotalProduk, 0, ',', '.') ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1 small text-secondary">
                                        <span>Biaya Pengiriman</span>
                                        <span>Rp <?= number_format($order->shipping_cost, 0, ',', '.') ?></span>
                                    </div>

                                    <!-- LOGIKA HITUNG DISKON -->
                                    <?php 
                                        $totalNormal = $subtotalProduk + $order->shipping_cost;
                                        $diskon = $totalNormal - $order->total_harga;
                                    ?>

                                    <?php if($diskon > 0): ?>
                                        <div class="d-flex justify-content-between mb-1 small text-success fw-bold">
                                            <span><i class="bi bi-ticket-perforated-fill me-1"></i> Total Diskon (Promo)</span>
                                            <span>-Rp <?= number_format($diskon, 0, ',', '.') ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <hr class="border-secondary border-opacity-25 my-2">
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="small">
                                            <span class="text-secondary d-block">Metode Bayar</span>
                                            <span class="fw-semibold text-body"><?= esc($order->payment_method ?? '-') ?></span>
                                        </div>
                                        <div class="text-end">
                                            <span class="text-secondary d-block small">Total Bayar</span>
                                            <span class="fs-5 fw-bold text-success">Rp <?= number_format($order->total_harga, 0, ',', '.') ?></span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- TAB 2: SELESAI / BATAL -->
            <div class="tab-pane fade" id="completed" role="tabpanel">
                <?php if(empty($completed)): ?>
                    <div class="text-center py-5 opacity-50">
                        <i class="bi bi-clipboard-check fa-4x mb-3 d-block text-secondary"></i>
                        <h5 class="fw-light">Belum ada riwayat pesanan selesai.</h5>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach($completed as $order): ?>
                        <div class="col-md-6 col-lg-12 col-xl-6">
                            <div class="order-card h-100 opacity-75" style="filter: grayscale(0.1);">
                                <div class="order-header">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-secondary bg-opacity-10 p-2 rounded-circle text-body">
                                            <i class="bi bi-archive fs-5"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">Order #<?= $order->id ?></div>
                                            <small class="text-secondary">
                                                <?= date('d M Y', strtotime($order->created_at)) ?>
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <?php if ($order->status == 'batal'): ?>
                                        <span class="badge-soft status-batal">DIBATALKAN</span>
                                    <?php else: ?>
                                        <span class="badge-soft status-selesai">SELESAI</span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="order-body">
                                    <?php 
                                        $subtotalProduk = 0;
                                        foreach($order->details as $detail): 
                                            $subtotalProduk += $detail->jumlah * $detail->harga_saat_ini;
                                    ?>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <span class="fw-semibold text-body-emphasis small"><?= $detail->product->nama_produk ?? 'Produk Dihapus' ?></span>
                                            <span class="text-secondary small ms-1">x<?= $detail->jumlah ?></span>
                                        </div>
                                        <span class="small fw-bold text-body">Rp <?= number_format($detail->jumlah * $detail->harga_saat_ini, 0, ',', '.') ?></span>
                                    </div>
                                    <?php endforeach; ?>
                                    
                                    <hr class="my-3 border-secondary border-opacity-10">
                                    
                                    <!-- RINCIAN SINGKAT -->
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold text-body-secondary">Total Akhir</span>
                                        <span class="fw-bold text-body-emphasis fs-5">Rp <?= number_format($order->total_harga, 0, ',', '.') ?></span>
                                    </div>
                                    
                                    <div class="text-end mt-3">
                                        <button class="btn btn-sm btn-outline-primary rounded-pill px-3">Beli Lagi</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
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