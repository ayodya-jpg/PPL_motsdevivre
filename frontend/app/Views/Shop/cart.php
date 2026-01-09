<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary-slate: #0f172a;
            --accent-amber: #fbbf24;
            --bg-light: #f8fafc;
            --card-shadow: 0 10px 30px -12px rgba(0, 0, 0, 0.08);
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            color: var(--primary-slate);
            transition: all 0.3s ease;
        }

        /* --- MODERN NAVIGATION (Sama dengan Index) --- */
        .double-header {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .brand-logo {
            background: var(--primary-slate);
            color: var(--accent-amber) !important;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
        }

        .nav-custom .nav-link {
            font-weight: 600;
            color: #64748b !important;
            padding: 0.5rem 1.2rem;
            border-radius: 12px;
            transition: all 0.2s;
        }

        .nav-custom .nav-link:hover, .nav-custom .nav-link.active {
            color: var(--primary-slate) !important;
            background: rgba(0,0,0,0.03);
        }

        /* --- CART COMPONENTS --- */
        .cart-item-card {
            border: none;
            border-radius: 24px;
            background: #ffffff;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease;
        }

        .cart-item-card:hover {
            transform: scale(1.01);
        }

        .product-thumb {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 18px;
        }

        .summary-card {
            border: none;
            border-radius: 28px;
            background: var(--primary-slate);
            color: white;
            padding: 30px;
            position: sticky;
            top: 150px;
        }

        .btn-checkout {
            background: var(--accent-amber);
            color: var(--primary-slate);
            border: none;
            border-radius: 16px;
            padding: 16px;
            font-weight: 800;
            width: 100%;
            transition: all 0.3s;
        }

        .btn-checkout:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(251, 191, 36, 0.3);
            background: #f59e0b;
        }

        .theme-toggle-btn {
            background: #f1f5f9;
            border: none;
            padding: 10px 18px;
            border-radius: 14px;
        }

        [data-bs-theme="dark"] {
            --bg-light: #0f172a;
            --primary-slate: #f8fafc;
        }
        [data-bs-theme="dark"] .cart-item-card { background: #1e293b; }
        [data-bs-theme="dark"] .double-header { background: rgba(15, 23, 42, 0.9) !important; }
    </style>
</head>
<body>

    <header class="double-header sticky-top mb-5">
        <div class="header-top py-3">
            <div class="container d-flex justify-content-between align-items-center">
                <a class="navbar-brand fw-bolder d-flex align-items-center text-decoration-none fs-4" href="/shop">
                    <div class="brand-logo me-2">DV</div> 
                    <span class="text-body tracking-tighter">Mots De Vivre</span>
                </a>

                <div class="d-flex align-items-center gap-2">
                    <nav class="nav nav-custom d-none d-lg-flex me-3">
                        <a class="nav-link" href="/shop">Home</a>
                        <a class="nav-link active" href="/cart">Cart</a>
                        <a class="nav-link" href="/orders">Orders</a>
                        <a class="nav-link" href="/profile">Profile</a>
                    </nav>

                    <button class="theme-toggle-btn" id="themeToggle">
                        <i class="fas fa-sun" id="themeIcon"></i>
                    </button>
                    
                    <button class="btn btn-outline-secondary border-0 d-lg-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div class="collapse border-top border-secondary bg-body shadow-sm d-lg-none sticky-top" id="mobileMenu" style="top: 80px; z-index: 1040;">
        <div class="container py-3">
            <a href="/shop" class="d-block py-2 text-decoration-none text-body fw-bold">Home</a>
            <a href="/cart" class="d-block py-2 text-decoration-none text-warning fw-bold">Cart</a>
            <a href="/orders" class="d-block py-2 text-decoration-none text-body">Orders</a>
            <a href="/profile" class="d-block py-2 text-decoration-none text-body">Profile</a>
        </div>
    </div>

    <div class="container pb-5">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="/shop" class="btn btn-light rounded-circle p-2"><i class="bi bi-arrow-left fs-5"></i></a>
            <h3 class="fw-800 m-0">Keranjang Belanja</h3>
        </div>

        <?php if (empty($cart)): ?>
            <div class="text-center py-5">
                <img src="https://illustrations.popsy.co/slate/shopping-cart.svg" style="width:250px" class="mb-4" alt="Empty Cart">
                <h4 class="fw-bold">Wah, keranjangmu kosong!</h4>
                <p class="text-muted mb-4">Yuk, isi dengan parfum premium kami.</p>
                <a href="/shop" class="btn btn-dark rounded-pill px-5 py-3 fw-bold">Mulai Belanja</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <div class="col-lg-8">
                    <?php foreach ($cart as $id => $item): ?>
                        <div class="cart-item-card p-4 mb-3">
                            <div class="d-flex align-items-center gap-4 flex-wrap flex-md-nowrap">
                                <div class="bg-light rounded-4 d-flex align-items-center justify-content-center" style="min-width: 100px; height: 100px;">
                                    <i class="fas fa-egg text-muted opacity-50 fs-2"></i>
                                </div>
                                
                                <div class="flex-grow-1">
                                    <h5 class="fw-bold mb-1"><?= esc($item['nama']) ?></h5>
                                    <p class="text-muted small mb-0">Harga: Rp <?= number_format($item['harga'], 0, ',', '.') ?></p>
                                </div>

                                <div class="d-flex align-items-center gap-3 bg-light rounded-pill px-3 py-2">
                                    <span class="text-muted small">Qty:</span>
                                    <span class="fw-black"><?= $item['qty'] ?></span>
                                </div>

                                <div class="text-md-end" style="min-width: 150px;">
                                    <div class="text-muted small mb-1">Subtotal</div>
                                    <div class="fw-bold fs-5">Rp <?= number_format($item['harga'] * $item['qty'], 0, ',', '.') ?></div>
                                </div>

                                <a href="/shop/remove/<?= $id ?>" class="btn btn-outline-danger border-0 rounded-circle p-2" onclick="return confirm('Hapus produk ini?')">
                                    <i class="bi bi-trash3-fill"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="mt-4">
                        <a href="/shop/clear" class="btn btn-link text-danger text-decoration-none fw-bold" onclick="return confirm('Kosongkan keranjang?')">
                            <i class="bi bi-x-circle me-1"></i> Kosongkan Keranjang
                        </a>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="summary-card shadow-lg">
                        <h5 class="fw-bold mb-4">Ringkasan Pesanan</h5>
                        
                        <div class="d-flex justify-content-between mb-3 opacity-75">
                            <span>Total Item</span>
                            <span><?= count($cart) ?> Produk</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-3 opacity-75">
                            <span>Biaya Pengiriman</span>
                            <span class="text-success fw-bold">Gratis</span>
                        </div>
                        
                        <hr class="my-4 opacity-25">
                        
                        <div class="d-flex justify-content-between align-items-end mb-4">
                            <span class="fs-6 opacity-75">Total Bayar</span>
                            <span class="fs-3 fw-black text-amber" style="color: var(--accent-amber);">Rp <?= number_format($total, 0, ',', '.') ?></span>
                        </div>

                        <a href="#" class="btn btn-checkout text-uppercase tracking-wider" id="checkoutBtn">
                        Lanjut ke Pembayaran <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                        
                        <div class="mt-4 text-center">
                            <p class="small opacity-50 mb-0"><i class="bi bi-shield-check me-1"></i> Pembayaran Aman & Terenkripsi</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
    // Dark Mode Logic
    const themeToggleBtn = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    
    themeToggleBtn.addEventListener('click', () => {
        const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        const newTheme = isDark ? 'light' : 'dark';
        document.documentElement.setAttribute('data-bs-theme', newTheme);
        themeIcon.className = isDark ? 'fas fa-moon' : 'fas fa-sun';
        localStorage.setItem('theme', newTheme);
    });

    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-bs-theme', savedTheme);
    themeIcon.className = savedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';

    // ✅ DIRECT CHECKOUT - SESSION SUDAH ADA!
    document.getElementById('checkoutBtn').addEventListener('click', function(e) {
        e.preventDefault();
        window.location.href = '/checkout';
    });
</script>
</body>
</html>
