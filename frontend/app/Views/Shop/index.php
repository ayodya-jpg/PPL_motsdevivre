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

        /* --- CAROUSEL BANNER STABIL --- */
        .carousel-banner {
            border-radius: 24px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        .carousel-item {
            /* Pastikan item carousel punya tinggi fixed agar tidak loncat */
            height: 400px;
            background-color: #000; /* Fallback color */
        }

        .carousel-img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Gambar akan di-crop proporsional */
            object-position: center;
            filter: brightness(0.85);
        }

        .carousel-caption h5 {
            font-weight: 800;
            font-size: 2rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        /* Responsif untuk Mobile */
        @media (max-width: 768px) {
            .carousel-item {
                height: 200px; /* Tinggi stabil di HP */
            }
            .carousel-caption h5 {
                font-size: 1.2rem;
            }
            .carousel-caption p {
                font-size: 0.9rem;
            }
        }

        /* --- MODERN NAVIGATION --- */
        .double-header {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .header-top { padding: 15px 0; }
        
        .brand-logo {
            background: var(--primary-slate);
            color: var(--accent-amber) !important;
            width: 40px; height: 40px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
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

        .search-input {
            border-radius: 15px !important;
            padding: 12px 20px;
            border: 1px solid #e2e8f0;
            background: #f1f5f9;
        }

        /* --- PRODUCT CARDS (AGAR SELARAS) --- */
        .card { 
            border: none;
            border-radius: 24px;
            overflow: hidden;
            background: #ffffff;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: var(--card-shadow);
            display: flex;
            flex-direction: column;
            height: 100%; /* Wajib full height */
        }
        
        .card:hover { 
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .product-img {
            height: 220px; /* Tinggi gambar fix */
            width: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .card:hover .product-img { transform: scale(1.1); }

        .card-body {
            flex-grow: 1; /* Agar body mengisi ruang kosong, mendorong footer ke bawah */
            display: flex;
            flex-direction: column;
        }

        /* Trik agar deskripsi selalu sama tinggi (max 2 baris) */
        .text-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 2.6em; /* Cadangan height jika browser jadul */
        }

        .price-text {
            color: var(--primary-slate);
            font-weight: 800;
            font-size: 1.25rem;
            margin-top: auto; /* Mendorong harga ke bawah area body */
        }

        .btn-success {
            background-color: var(--primary-slate);
            border: none;
            border-radius: 14px;
            padding: 12px;
            font-weight: 700;
            transition: all 0.3s;
        }

        .btn-success:hover {
            background-color: var(--accent-amber);
            color: var(--primary-slate);
        }

        /* --- MODAL PROMO (GUEST) --- */
        .modal-promo-content {
            border-radius: 35px;
            overflow: hidden;
            border: none;
        }
        .promo-bg {
            background: linear-gradient(135deg, var(--primary-slate) 0%, #1e293b 100%);
            color: white;
            padding: 50px 30px;
        }

        /* --- THEME --- */
        [data-bs-theme="dark"] {
            --bg-light: #0f172a;
            --primary-slate: #f8fafc;
        }
        [data-bs-theme="dark"] .card { background: #1e293b; }
        [data-bs-theme="dark"] .double-header { background: rgba(15, 17, 42, 0.9) !important; }
        [data-bs-theme="dark"] .search-input { background: #334155; border: none; color: white; }
    </style>
</head>
<body>

    <header class="double-header sticky-top">
        <div class="header-top">
            <div class="container d-flex justify-content-between align-items-center">
                <a class="navbar-brand fw-bolder d-flex align-items-center text-decoration-none fs-4" href="/shop">
                    <div class="brand-logo me-2">DV</div> 
                    <span class="text-body tracking-tighter">Mots De Vivre</span>
                </a>

                <div class="d-flex align-items-center gap-2">
                    <nav class="nav nav-custom d-none d-lg-flex me-3" id="navMenu">
                        <a class="nav-link active" href="/shop">Home</a>
                        <?php if (session()->get('is_logged_in')) : ?>
                            <a class="nav-link" href="/cart">Cart <span class="badge bg-danger rounded-pill"><?= $cart_count ?? 0 ?></span></a>
                            <a class="nav-link" href="/orders">Orders</a>
                            <a class="nav-link" href="/profile">Profile</a>
                        <?php endif; ?>
                        <a class="nav-link" href="/about">About Mots</a>
                    </nav>

                    <button class="theme-toggle-btn btn btn-light rounded-pill border-0" id="themeToggle">
                        <i class="fas fa-sun" id="themeIcon"></i>
                    </button>

                    <button class="btn btn-outline-secondary border-0 d-lg-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="header-bottom pb-3">
            <div class="container d-flex justify-content-between align-items-center">
                <div class="flex-grow-1 me-4" style="max-width: 500px;">
                    <form class="position-relative">
                        <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"><i class="fas fa-search"></i></span>
                        <input class="form-control search-input ps-5" type="search" placeholder="Cari produk...">
                    </form>
                </div>

                <div class="auth-zone">
                    <?php if (session()->get('is_logged_in')) : ?>
                        <div class="dropdown d-none d-md-block">
                            <button class="btn btn-dark rounded-pill px-4 dropdown-toggle fw-bold" data-bs-toggle="dropdown">
                                Hai, <?= esc(session()->get('name')) ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4">
                                <li><a class="dropdown-item py-2" href="/profile"><i class="bi bi-person me-2"></i> Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2 text-danger" href="/auth/logout"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                            </ul>
                        </div>
                    <?php else : ?>
                        <a href="/auth" class="btn btn-link text-decoration-none text-dark fw-bold me-2">Login</a>
                        <a href="/register" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <div class="collapse border-top border-secondary bg-body shadow-sm d-lg-none sticky-top" id="mobileMenu" style="top: 130px; z-index: 1040;">
        <div class="container py-3">
            <a href="/shop" class="d-block py-2 text-decoration-none text-body fw-bold">Home</a>
            <a href="/about" class="d-block py-2 text-decoration-none text-body">About Mots</a>
            <?php if (session()->get('is_logged_in')) : ?>
                <a href="/cart" class="d-block py-2 text-decoration-none text-body">Cart</a>
                <a href="/orders" class="d-block py-2 text-decoration-none text-body">Orders</a>
                <a href="/profile" class="d-block py-2 text-decoration-none text-body">Profile</a>
                <a href="/auth/logout" class="d-block py-2 text-decoration-none text-danger">Logout</a>
            <?php else: ?>
                <a href="/auth" class="d-block py-2 text-decoration-none text-body">Login</a>
                <a href="/register" class="d-block py-2 text-decoration-none text-warning fw-bold">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="container pb-5 mt-4">
    
    <div id="promoCarousel" class="carousel slide carousel-banner" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#promoCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#promoCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active" data-bs-interval="5000">
                    <img src="<?= base_url('images/Radiant-Bliss.jpeg') ?>" class="d-block w-100 carousel-img" alt="Radiant Bliss">
                    <div class="carousel-caption d-none d-md-block text-start top-50 translate-middle-y">
                        <h5 class="display-6 text-warning">New Arrival</h5>
                        <p class="fs-5 text-white">Temukan aroma terbaru musim ini.</p>
                        <a href="#produk-list" class="btn btn-light rounded-pill fw-bold px-4 mt-2">Lihat Produk</a>
                    </div>
                </div>
                <div class="carousel-item" data-bs-interval="5000">
                    <img src="<?= base_url('images/Secret-Desire.jpeg') ?>" class="d-block w-100 carousel-img" alt="Secret Desire">
                    <div class="carousel-caption d-none d-md-block text-start top-50 translate-middle-y">
                        <h5 class="display-6 text-warning">Best Seller</h5>
                        <p class="fs-5 text-white">Wangi memikat sepanjang hari.</p>
                        <a href="#produk-list" class="btn btn-light rounded-pill fw-bold px-4 mt-2">Lihat Produk</a>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#promoCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#promoCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4 mt-4"><i class="bi bi-check-circle me-2"></i> <?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4 pt-4" id="produk-list">
            <h3 class="fw-800 m-0">Produk Pilihan</h3>
            <div class="text-muted small">Pilih parfum sesuai pribadimu</div>
        </div>

        <div class="row g-4">
            <?php if(!empty($products)): ?>
                <?php foreach ($products as $item) : ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100">
                        <div class="position-relative overflow-hidden">
                            <?php if(!empty($item->gambar)): ?>
                                <img src="http://localhost:8090/storage/<?= $item->gambar ?>" class="product-img w-100" alt="Produk">
                            <?php else: ?>
                                <div class="img-placeholder bg-light d-flex align-items-center justify-content-center" style="height:220px;"><i class="fas fa-spray-can fa-3x text-muted opacity-25"></i></div>
                            <?php endif; ?>

                            <?php if($item->stok < 5 && $item->stok > 0): ?>
                                <span class="position-absolute top-0 start-0 badge bg-warning text-dark m-3 badge-stok shadow-sm">Limit</span>
                            <?php elseif($item->stok == 0): ?>
                                <span class="position-absolute top-0 start-0 badge bg-danger m-3 badge-stok shadow-sm">Habis</span>
                            <?php endif; ?>
                        </div>

                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-2 text-truncate"><?= esc($item->nama_produk) ?></h6>
                            
                            <p class="text-muted small mb-3 text-clamp-2"><?= esc($item->deskripsi) ?></p>
                            
                            <div class="price-text text-primary mt-auto">Rp <?= number_format($item->harga, 0, ',', '.') ?></div>
                        </div>

                        <div class="card-footer bg-transparent border-0 p-4 pt-0">
                            <?php if($item->stok > 0): ?>
                                <?php if (session()->get('is_logged_in')) : ?>
                                    <a href="/shop/add/<?= $item->id ?>" class="btn btn-success w-100"><i class="bi bi-cart-plus me-2"></i> Tambah</a>
                                <?php else : ?>
                                    <a href="/auth" class="btn btn-outline-dark w-100 rounded-pill fw-bold add-to-cart-btn" data-product-id="<?= $item->id ?>">Beli</a>
                                <?php endif; ?>
                            <?php else: ?>
                                <button class="btn btn-light w-100 text-muted" disabled>Habis</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <img src="https://illustrations.popsy.co/slate/empty-box.svg" style="width:200px" alt="Empty">
                    <h5 class="mt-4 fw-bold">Belum ada produk nih...</h5>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!session()->get('is_logged_in')): ?>
    <div class="modal fade" id="promoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-promo-content">
                <div class="promo-bg text-center">
                    <div class="bg-warning text-dark d-inline-block p-3 rounded-circle mb-4 shadow">
                        <i class="fas fa-gift fs-2"></i>
                    </div>
                    <h2 class="fw-900 mb-3">Welcome Promo!</h2>
                    <p class="mb-5 opacity-75">Daftar sekarang dan dapatkan penawaran spesial untuk pembelian parfum pertamamu.</p>
                    <a href="/register?promo=newuser" class="btn btn-warning rounded-pill px-5 py-3 fw-bold shadow-lg w-100 mb-3">Daftar Sekarang</a>
                    <button type="button" class="btn btn-link text-white text-decoration-none opacity-50 small" data-bs-dismiss="modal">Nanti saja</button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto Show Welcome Promo Modal if Guest
        document.addEventListener('DOMContentLoaded', function () {
            const promoEl = document.getElementById('promoModal');
            if (promoEl) {
                const myModal = new bootstrap.Modal(promoEl);
                myModal.show();
            }
        });

        // Theme Toggle
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

        // ✅ DETEKSI LOGIN DARI LOCALSTORAGE UNTUK SINKRONISASI UI
        const isLoggedIn = localStorage.getItem('is_logged_in');
        const userData = localStorage.getItem('user_data');
        const hasSession = <?= json_encode(session()->get('is_logged_in') ?? false) ?>;

        if (isLoggedIn === 'true' && userData && !hasSession) {
            const user = JSON.parse(userData);
            
            // Update auth buttons header
            const authZone = document.querySelector('.auth-zone');
            authZone.innerHTML = `
                <div class="dropdown d-none d-md-block">
                    <button class="btn btn-dark rounded-pill px-4 dropdown-toggle fw-bold" data-bs-toggle="dropdown">
                        Hai, ${user.name}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4">
                        <li><a class="dropdown-item py-2" href="/profile"><i class="bi bi-person me-2"></i> Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 text-danger" href="#" id="logoutBtn"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                    </ul>
                </div>
            `;
            
            // ✅ UPDATE NAVBAR MENU (HAPUS SUBSCRIPTIONS DARI JS JUGA)
            const navMenu = document.getElementById('navMenu');
            const aboutLink = navMenu.querySelector('a[href="/about"]');
            
            const menuHTML = `
                <a class="nav-link" href="/cart">Cart</a>
                <a class="nav-link" href="/orders">Orders</a>
                <a class="nav-link" href="/profile">Profile</a>
            `;
            aboutLink.insertAdjacentHTML('beforebegin', menuHTML);
            
            // ✅ UPDATE MOBILE MENU
            const mobileMenu = document.getElementById('mobileMenu');
            const aboutMobile = mobileMenu.querySelector('a[href="/about"]');
            
            const mobileMenuHTML = `
                <a href="/cart" class="d-block py-2 text-decoration-none text-body">Cart</a>
                <a href="/orders" class="d-block py-2 text-decoration-none text-body">Orders</a>
                <a href="/profile" class="d-block py-2 text-decoration-none text-body">Profile</a>
                <a href="#" class="d-block py-2 text-decoration-none text-danger" id="logoutBtnMobile">Logout</a>
            `;
            aboutMobile.insertAdjacentHTML('afterend', mobileMenuHTML);
            
            // UPDATE BUTTON "BELI" JADI "TAMBAH"
            document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
                const productId = btn.getAttribute('data-product-id');
                btn.href = `/shop/add/${productId}`;
                btn.className = 'btn btn-success w-100';
                btn.innerHTML = '<i class="bi bi-cart-plus me-2"></i> Tambah';
            });
            
            // Hide promo modal
            const promoModal = document.getElementById('promoModal');
            if (promoModal) {
                promoModal.remove();
            }
        }

        // Handle logout (desktop & mobile)
        document.addEventListener('click', function(e) {
            if (e.target && (e.target.id === 'logoutBtn' || e.target.id === 'logoutBtnMobile')) {
                e.preventDefault();
                localStorage.removeItem('user_data');
                localStorage.removeItem('user_email');
                localStorage.removeItem('is_logged_in');
                window.location.href = '/shop';
            }
        });
    </script>
</body>
</html>