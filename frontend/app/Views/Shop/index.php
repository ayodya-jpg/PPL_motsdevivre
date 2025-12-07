<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    
    <!-- 1. Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- 2. FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap Icons (BARU: Untuk ikon bi-shop) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* --- GLOBAL STYLE --- */
        body { 
            transition: background-color 0.3s, color 0.3s;
        }

        /* --- HEADER STYLE --- */
        .double-header {
            transition: background-color 0.3s;
        }
        
        /* Baris Atas (Logo & Navigasi) */
        .header-top {
            padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1); /* Border transparan agar cocok di dark/light */
        }
        
        .nav-custom .nav-link {
            font-size: 0.9rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0.5rem 1rem;
            transition: color 0.2s;
            opacity: 0.7;
        }
        .nav-custom .nav-link:hover, .nav-custom .nav-link.active {
            opacity: 1;
        }
        .nav-custom .nav-link i {
            font-size: 1.2rem;
            margin-bottom: 4px;
        }

        /* Baris Bawah (Search & Auth) */
        .header-bottom {
            padding: 15px 0;
        }

        /* Search Input Style Adaptif */
        .search-input {
            border: 1px solid rgba(255,255,255,0.2);
        }
        [data-bs-theme="light"] .search-input {
            border: 1px solid #ced4da;
            background-color: #fff;
            color: #212529;
        }
        [data-bs-theme="dark"] .search-input {
            background-color: #2b3035;
            color: #fff;
            border-color: #495057;
        }

        /* Card Produk Style */
        .product-img {
            height: 200px;
            object-fit: cover;
            object-position: center;
        }
        .img-placeholder {
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
        }
        [data-bs-theme="dark"] .img-placeholder { background-color: #2b3035; }
        [data-bs-theme="light"] .img-placeholder { background-color: #e9ecef; }

        .card { 
            transition: transform 0.2s, box-shadow 0.2s; 
            border: 1px solid rgba(255,255,255,0.1); 
        }
        [data-bs-theme="light"] .card { border: 1px solid rgba(0,0,0,0.1); }
        
        .card:hover { transform: translateY(-5px); }

        /* Theme Toggle Button Style */
        .theme-toggle-btn {
            background: none;
            border: 1px solid rgba(255,255,255,0.2);
            color: inherit;
            padding: 5px 10px;
            border-radius: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.85rem;
            transition: 0.3s;
        }
        [data-bs-theme="light"] .theme-toggle-btn {
            border: 1px solid rgba(0,0,0,0.2);
        }
        .theme-toggle-btn:hover {
            background-color: rgba(255,255,255,0.1);
        }
    </style>
</head>
<body>

    <!-- DOUBLE HEADER WRAPPER -->
    <!-- bg-body-tertiary akan otomatis menyesuaikan warna (gelap/terang) berdasarkan tema -->
    <header class="double-header mb-4 shadow-sm bg-body-tertiary">
        
        <!-- BAGIAN 1: HEADER ATAS (Logo & Menu Navigasi) -->
        <div class="header-top">
            <div class="container d-flex justify-content-between align-items-center">
                
                <!-- Logo -->
                <a class="navbar-brand fw-bold d-flex align-items-center text-decoration-none fs-4" href="/shop">
                    <span class="bg-warning text-white rounded px-2 me-2 fs-5 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">VR</span> 
                    <span class="text-body">Mots De Vivre</span>
                </a>

                <div class="d-flex align-items-center gap-3">
                    <!-- Navigasi Ikon (Tengah/Kanan) -->
                    <nav class="nav nav-custom d-none d-md-flex">
                        <a class="nav-link active text-warning fw-bold" href="/shop">
                        <i class="bi bi-house"></i> Home
                        </a>
                        
                        <?php if (session()->get('is_logged_in')) : ?>
                            <a class="nav-link position-relative text-body" href="/cart">
                            <i class="bi bi-bag"></i></i> Cart
                                <?php if(isset($cart_count) && $cart_count > 0): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                        <?= $cart_count ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                            <a class="nav-link text-body" href="/orders">
                            <i class="bi bi-receipt"></i> Orders
                            </a>
                            <a class="nav-link text-body" href="/profile">
                            <i class="bi bi-person-fill"></i> Profile
                            </a>
                        <?php else: ?>
                            <a class="nav-link text-body" href="/about">
                                <i class="bi bi-shop"></i> About Mots
                            </a>
                        <?php endif; ?>
                    </nav>

                    <!-- TOMBOL GANTI TEMA (Pojok Kanan) -->
                    <button class="theme-toggle-btn" id="themeToggle" title="Ganti Tema">
                        <i class="fas fa-sun" id="themeIcon"></i>
                        <span class="d-none d-sm-inline" id="themeText">Light</span>
                    </button>

                    <!-- Mobile Menu Button -->
                    <button class="btn btn-outline-secondary d-md-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div class="collapse border-top border-secondary" id="mobileMenu">
            <div class="container py-2">
                <a href="/shop" class="d-block py-2 text-decoration-none text-body"><i class="fas fa-home me-2"></i> Home</a>
                <a href="/cart" class="d-block py-2 text-decoration-none text-body"><i class="fas fa-shopping-cart me-2"></i> Cart</a>
            </div>
        </div>

        <!-- BAGIAN 2: HEADER BAWAH (Search & Auth) -->
        <div class="header-bottom bg-body">
            <div class="container d-flex justify-content-between align-items-center">
                
                <!-- Search Box -->
                <div class="flex-grow-1 me-4" style="max-width: 400px;">
                    <form class="d-flex" role="search">
                        <input class="form-control search-input" type="search" placeholder="Search..." aria-label="Search">
                    </form>
                </div>

                <!-- Auth Buttons -->
                <div class="d-flex align-items-center">
                    <?php if (session()->get('is_logged_in')) : ?>
                        <div class="text-body small me-3 d-none d-sm-block">
                            Halo, <strong><?= esc(session()->get('name')) ?></strong>
                        </div>
                        <a href="/auth/logout" class="btn btn-danger btn-sm fw-bold">Logout</a>
                    <?php else : ?>
                        <a href="/auth" class="btn btn-outline-secondary btn-sm fw-bold me-2 px-3">Login</a>
                        <a href="/register" class="btn btn-warning btn-sm fw-bold px-3">Sign-up</a>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </header>

    <!-- CONTENT CONTAINER -->
    <div class="container pb-5">

        <!-- Pesan Sukses/Gagal -->
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

        <!-- GRID PRODUK -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            
            <?php if(!empty($products)): ?>
                <?php foreach ($products as $item) : ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        
                        <!-- Gambar Produk -->
                        <div class="position-relative">
                            <?php if(!empty($item->gambar)): ?>
                                <img src="http://localhost:8000/storage/<?= $item->gambar ?>" class="card-img-top product-img" alt="<?= esc($item->nama_produk) ?>">
                            <?php else: ?>
                                <div class="card-img-top img-placeholder">
                                    <div class="text-center">
                                        <i class="fas fa-image fa-3x mb-2"></i><br>No Image
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Badge Stok -->
                            <?php if($item->stok < 5 && $item->stok > 0): ?>
                                <span class="position-absolute top-0 end-0 badge bg-warning text-dark m-2">Stok Menipis!</span>
                            <?php elseif($item->stok == 0): ?>
                                <span class="position-absolute top-0 end-0 badge bg-secondary m-2">Habis</span>
                            <?php endif; ?>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-truncate" title="<?= esc($item->nama_produk) ?>">
                                <?= esc($item->nama_produk) ?>
                            </h5>
                            
                            <p class="card-text opacity-75 small flex-grow-1">
                                <?= substr(esc($item->deskripsi), 0, 60) ?>...
                            </p>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="h5 mb-0 text-body fw-bold">
                                    Rp <?= number_format($item->harga, 0, ',', '.') ?>
                                </span>
                                <small class="opacity-75">Stok: <?= $item->stok ?></small>
                            </div>
                        </div>

                        <div class="card-footer bg-transparent border-top-0 pb-3 pt-0">
                            <?php if($item->stok > 0): ?>
                                <a href="/shop/add/<?= $item->id ?>" class="btn btn-success w-100 fw-bold">
                                    <i class="fas fa-cart-plus me-2"></i> Beli Sekarang
                                </a>
                            <?php else: ?>
                                <button class="btn btn-secondary w-100" disabled>Stok Habis</button>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
                <?php endforeach; ?>
            
            <?php else: ?>
                <div class="col-12">
                    <div class="text-center py-5 opacity-75">
                        <i class="fas fa-box-open fa-4x mb-3"></i>
                        <h4>Belum ada produk yang tersedia.</h4>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SCRIPT GANTI TEMA -->
    <script>
        const themeToggleBtn = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const themeText = document.getElementById('themeText');
        const htmlElement = document.documentElement;

        // Cek Local Storage (Default ke 'dark')
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
                themeText.textContent = 'Light';
                themeToggleBtn.setAttribute('title', 'Ganti ke Light Mode');
            } else {
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
                themeText.textContent = 'Dark';
                themeToggleBtn.setAttribute('title', 'Ganti ke Dark Mode');
            }
        }
    </script>
</body>
</html>