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
        :root {
            --card-bg-light: #ffffff;
            --card-bg-dark: #212529;
        }

        body { 
            font-family: 'Inter', sans-serif;
            transition: background-color 0.3s, color 0.3s;
            background-color: var(--bs-tertiary-bg);
        }

        /* --- HEADER STYLE --- */
        .nav-custom .nav-link { font-size: 0.9rem; display: flex; flex-direction: column; align-items: center; padding: 0.5rem 1rem; opacity: 0.7; transition: 0.2s; }
        .nav-custom .nav-link:hover, .nav-custom .nav-link.active { opacity: 1; transform: translateY(-2px); }
        .nav-custom .nav-link i { font-size: 1.3rem; margin-bottom: 4px; }
        .search-input { background-color: var(--bs-body-bg); border: 1px solid var(--bs-border-color); }
        
        /* --- MODERN PRODUCT CARD --- */
        .product-card {
            background-color: var(--bs-body-bg);
            border: 1px solid var(--bs-border-color-translucent);
            border-radius: 1rem;
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            border-color: var(--bs-primary-border-subtle);
        }

        .img-container {
            position: relative;
            padding: 0.75rem; /* Memberikan padding agar gambar terlihat seperti 'inset' */
        }

        .product-img {
            height: 220px;
            width: 100%;
            object-fit: cover;
            border-radius: 0.75rem; /* Sudut gambar membulat */
            /* background-color: var(--bs-tertiary-bg); */
        }

        .img-placeholder {
            height: 220px;
            border-radius: 0.75rem;
            background-color: var(--bs-secondary-bg);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--bs-secondary-color);
        }

        .card-body-custom {
            padding: 1.25rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        /* Badge Stok Modern */
        .badge-stock {
            position: absolute;
            top: 1rem;
            right: 1rem;
            backdrop-filter: blur(4px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        /* Tombol Beli */
        .btn-action {
            border-radius: 0.75rem;
            padding: 0.6rem 1rem;
            font-weight: 600;
            transition: 0.2s;
        }
        .btn-action:hover {
            transform: scale(1.02);
        }

        /* --- PROMO MODAL STYLE --- */
        .promo-header {
            background: linear-gradient(135deg, #FFD700, #FF8C00); /* Warna Emas Promo */
            color: #000;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
            position: relative;
            overflow: hidden;
        }
        .promo-header::after {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.4) 0%, transparent 60%);
            animation: shine 3s infinite linear;
        }
        @keyframes shine {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .promo-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #fff;
            color: #d63384;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 2;
        }
    </style>
</head>
<body>

    <!-- HEADER (Sama dengan Orders/Cart) -->
    <header class="mb-5 shadow-sm bg-body-tertiary sticky-top">
        
        <!-- Baris Atas -->
        <div class="header-top py-2 border-bottom border-secondary border-opacity-10">
            <div class="container d-flex justify-content-between align-items-center">
                <!-- Logo -->
                <a class="navbar-brand fw-bold d-flex align-items-center text-decoration-none fs-4" href="/shop">
                    <span class="bg-warning text-white rounded-3 px-2 me-2 fs-5 d-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px;">VR</span> 
                    <span class="text-body fw-bold">Mots De Vivre</span>
                </a>
                
                <div class="d-flex align-items-center gap-3">
                    <nav class="nav nav-custom d-none d-md-flex">
                        <a class="nav-link active text-warning fw-bold" href="/shop"><i class="bi bi-house"></i> Home</a>
                        
                        <?php if (session()->get('is_logged_in')) : ?>
                            <a class="nav-link text-body position-relative" href="/cart">
                                <i class="bi bi-bag"></i> Cart
                                <?php if(isset($cart_count) && $cart_count > 0): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm border border-2 border-white" style="font-size: 0.6rem;"><?= $cart_count ?></span>
                                <?php endif; ?>
                            </a>
                            <a class="nav-link text-body" href="/orders"><i class="bi bi-receipt"></i> Orders</a>
                            <a class="nav-link text-body" href="/profile"><i class="bi bi-person-fill"></i> Profile</a>
                        <?php else: ?>
                            <a class="nav-link text-body" href="/about"><i class="bi bi-shop"></i> About Toko</a>
                        <?php endif; ?>
                    </nav>
                    <button class="btn btn-outline-secondary btn-sm rounded-circle p-2" style="width: 38px; height: 38px;" id="themeToggle"><i class="fas fa-sun" id="themeIcon"></i></button>
                    
                    <!-- Mobile Menu Button -->
                    <button class="btn btn-outline-secondary d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu"><i class="fas fa-bars"></i></button>
                </div>
            </div>
        </div>

        <!-- Baris Bawah (Search & Auth) -->
        <div class="header-bottom py-3">
            <div class="container d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="flex-grow-1" style="max-width: 400px;">
                    <form class="d-flex position-relative" role="search">
                        <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"><i class="bi bi-search"></i></span>
                        <input class="form-control search-input ps-5 rounded-pill" type="search" placeholder="Cari produk impian..." aria-label="Search">
                    </form>
                </div>

                <div class="d-flex align-items-center">
                    <?php if (session()->get('is_logged_in')) : ?>
                        <div class="text-body small me-3 d-none d-sm-block text-end">
                            <div class="text-muted" style="font-size: 0.75rem;">Halo,</div>
                            <div class="fw-bold"><?= esc(session()->get('name')) ?></div>
                        </div>
                        <a href="/auth/logout" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold"><i class="bi bi-box-arrow-right me-1"></i> Logout</a>
                    <?php else : ?>
                        <a href="/auth" class="btn btn-outline-primary btn-sm fw-bold me-2 px-4 rounded-pill">Login</a>
                        <a href="/register" class="btn btn-primary btn-sm fw-bold px-4 rounded-pill">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- KONTEN UTAMA -->
    <div class="container pb-5">

        <!-- Pesan Sukses/Gagal -->
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 border-start border-4 border-success" role="alert">
                <i class="bi bi-check-circle-fill me-2 text-success"></i> <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 border-start border-4 border-danger" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i> <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- GRID PRODUK -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            
            <?php if(!empty($products)): ?>
                <?php foreach ($products as $item) : ?>
                <div class="col">
                    <div class="product-card shadow-sm">
                        
                        <!-- Area Gambar -->
                        <div class="img-container">
                            <?php if(!empty($item->gambar)): ?>
                                <img src="http://localhost:8000/storage/<?= $item->gambar ?>" class="product-img shadow-sm" alt="<?= esc($item->nama_produk) ?>">
                            <?php else: ?>
                                <div class="img-placeholder shadow-sm">
                                    <i class="bi bi-image fs-1 mb-2 opacity-50"></i>
                                    <small>No Image</small>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Badge Stok -->
                            <?php if($item->stok < 5 && $item->stok > 0): ?>
                                <span class="badge bg-warning text-dark badge-stock rounded-pill"><i class="bi bi-exclamation-circle me-1"></i> Menipis</span>
                            <?php elseif($item->stok == 0): ?>
                                <span class="badge bg-secondary badge-stock rounded-pill">Habis</span>
                            <?php endif; ?>
                        </div>

                        <!-- Body Card -->
                        <div class="card-body-custom">
                            <h5 class="card-title text-truncate fw-bold mb-1" title="<?= esc($item->nama_produk) ?>">
                                <?= esc($item->nama_produk) ?>
                            </h5>
                            
                            <p class="card-text text-secondary small flex-grow-1 mb-3" style="line-height: 1.5;">
                                <?= substr(esc($item->deskripsi), 0, 50) ?>...
                            </p>

                            <div class="d-flex justify-content-between align-items-end mt-auto">
                                <div>
                                    <small class="text-muted d-block" style="font-size: 0.75rem;">Harga</small>
                                    <span class="h5 mb-0 text-primary fw-bold">
                                        Rp <?= number_format($item->harga, 0, ',', '.') ?>
                                    </span>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted d-block" style="font-size: 0.75rem;">Stok</small>
                                    <span class="fw-medium text-body-emphasis"><?= $item->stok ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Footer / Tombol -->
                        <div class="p-3 pt-0 bg-transparent border-0">
                            <?php if($item->stok > 0): ?>
                                <a href="/shop/add/<?= $item->id ?>" class="btn btn-success w-100 btn-action shadow-sm">
                                    <i class="bi bi-bag-plus-fill me-2"></i> Beli Sekarang
                                </a>
                                <!-- btnbeli -->
                            <?php else: ?>
                                <button class="btn btn-secondary w-100 btn-action opacity-75" disabled>Stok Habis</button>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
                <?php endforeach; ?>
            
            <?php else: ?>
                <div class="col-12">
                    <div class="text-center py-5 opacity-50">
                        <i class="bi bi-box-seam fa-4x mb-3 d-block"></i>
                        <h4 class="fw-light">Belum ada produk yang tersedia.</h4>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- PROMO POPUP MODAL (Hanya untuk yang belum login) -->
    <?php if (!session()->get('is_logged_in')) : ?>
    <div class="modal fade" id="promoModal" tabindex="-1" aria-labelledby="promoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem;">
                <div class="modal-header promo-header border-0 p-4">
                    <span class="promo-badge">NEW USER</span>
                    <h1 class="modal-title fs-3 fw-bold w-100 text-center" style="z-index: 1;">
                        <i class="bi bi-gift-fill me-2"></i> Selamat Datang!
                    </h1>
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" style="z-index: 2;"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <h4 class="fw-bold mb-2">Dapatkan Promo Spesial!</h4>
                    <p class="text-muted mb-4">Daftar sekarang dan klaim hadiah pengguna baru Anda.</p>
                    
                    <div class="d-flex justify-content-center gap-3 mb-4">
                        <!-- Promo 1: Gratis Ongkir -->
                        <div class="text-center p-2 rounded bg-body-tertiary shadow-sm" style="min-width: 100px;">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 mb-2 mx-auto" style="width: 50px; height: 50px; display:flex; align-items:center; justify-content:center;">
                                <i class="bi bi-truck fs-5"></i>
                            </div>
                            <small class="fw-bold d-block text-primary">Gratis<br>Ongkir</small>
                        </div>
                        
                        <!-- Promo 2: Diskon 20% -->
                        <div class="text-center p-2 rounded bg-body-tertiary shadow-sm" style="min-width: 100px;">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 mb-2 mx-auto" style="width: 50px; height: 50px; display:flex; align-items:center; justify-content:center;">
                                <i class="bi bi-percent fs-5"></i>
                            </div>
                            <small class="fw-bold d-block text-success">Diskon<br>20% Off</small>
                        </div>
                    </div>

                    <!-- Tombol Klaim (MENGARAH KE URL REGISTER DENGAN PARAMETER) -->
                    <a href="/register?promo=NEWUSER20_FREESHIP" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm mb-3">
                        Klaim Promo & Daftar <i class="bi bi-arrow-right-circle ms-1"></i>
                    </a>
                    <button type="button" class="btn btn-link text-decoration-none text-muted btn-sm" data-bs-dismiss="modal">Nanti saja, saya hanya melihat-lihat</button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SCRIPT TEMA -->
    <script>
        // --- LOGIKA TEMA ---
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
            if (theme === 'dark') {
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            } else {
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
            }
        }

        // --- LOGIKA POPUP MODAL (SHOW ONCE) ---
        document.addEventListener('DOMContentLoaded', function() {
            const promoModalEl = document.getElementById('promoModal');
            if (promoModalEl) {
                // Tampilkan hanya jika belum pernah ditampilkan di sesi ini
                const promoShown = sessionStorage.getItem('promoShown');
                if (!promoShown) {
                    const promoModal = new bootstrap.Modal(promoModalEl);
                    promoModal.show();
                    sessionStorage.setItem('promoShown', 'true');
                }
            }
        });
    </script>
</body>
</html>