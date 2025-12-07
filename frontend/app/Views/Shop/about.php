<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons (Masih dipakai untuk ikon lain) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap Icons (BARU: Untuk ikon bi-shop) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body { transition: background-color 0.3s, color 0.3s; }
        
        /* Navigasi Custom */
        .nav-custom .nav-link { font-size: 0.9rem; display: flex; flex-direction: column; align-items: center; padding: 0.5rem 1rem; opacity: 0.7; }
        .nav-custom .nav-link:hover, .nav-custom .nav-link.active { opacity: 1; }
        .nav-custom .nav-link i { font-size: 1.2rem; margin-bottom: 4px; }
        
        /* Search Input */
        .search-input { border: 1px solid rgba(255,255,255,0.2); }
        [data-bs-theme="light"] .search-input { border: 1px solid #ced4da; background-color: #fff; color: #212529; }
        [data-bs-theme="dark"] .search-input { background-color: #2b3035; color: #fff; border-color: #495057; }
        
        /* Profile/Shop Card Style */
        .profile-header {
            /* Menggunakan warna hijau/biru toko */
            background: linear-gradient(135deg, #198754, #0d6efd);
            height: 150px;
            border-radius: 8px 8px 0 0;
        }
        .profile-img-container {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            border: 5px solid;
            margin-top: -70px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        [data-bs-theme="dark"] .profile-img-container { border-color: #212529; }
        [data-bs-theme="light"] .profile-img-container { border-color: #fff; }
        
        .social-link {
            width: 40px; height: 40px;
            display: inline-flex; align-items: center; justify-content: center;
            border-radius: 50%;
            text-decoration: none;
            margin: 0 5px;
            font-size: 1.2rem;
            transition: 0.3s;
        }
        .social-link:hover { transform: translateY(-3px); }
    </style>
</head>
<body>

    <!-- HEADER (Sama seperti Index) -->
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
                        <?php if (session()->get('is_logged_in')) : ?>
                            <a class="nav-link position-relative text-body" href="/cart">
                            <i class="bi bi-bag"></i></i> Cart
                                <?php if(isset($cart_count) && $cart_count > 0): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;"><?= $cart_count ?></span>
                                <?php endif; ?>
                            </a>
                            <a class="nav-link text-body" href="#"><i class="bi bi-receipt"></i> Orders</a>
                            <a class="nav-link text-body" href="#"><i class="fas fa-th-large"></i> Products</a>
                        <?php endif; ?>
                        
                        <!-- Link About Me Aktif (IKON DIPERBARUI) -->
                        <a class="nav-link active text-warning fw-bold" href="/about">
                            <i class="bi bi-shop"></i> About Mots
                        </a>
                    </nav>

                    <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" id="themeToggle">
                        <i class="fas fa-sun" id="themeIcon"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- KONTEN UTAMA: ABOUT TOKO -->
    <div class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                
                <div class="card border-0 shadow-lg text-center h-100">
                    <!-- Background Header -->
                    <div class="profile-header"></div>
                    
                    <div class="card-body">
                        <!-- Ikon Toko (Diganti Image) -->
                        <div class="d-flex justify-content-center">
                            <div class="profile-img-container shadow">
                                <!-- URL Logo Toko (Diubah ke gambar lokal) -->
                                <img src="<?= base_url('images/logo_toko.png') ?>" alt="Logo Toko" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </div>
                        
                        <h2 class="mt-3 fw-bold">Tentang Mots De Vivre</h2>
                        <p class="text-muted mb-3">Parfume</p>
                        
                        <!-- Badges Keunggulan Toko -->
                        <div class="d-flex justify-content-center mb-4 flex-wrap gap-2">
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 py-2 px-3">
                                <i class="fas fa-check-circle me-1"></i> Produk Original
                            </span>
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 py-2 px-3">
                                <i class="fas fa-shield-alt me-1"></i> Garansi Resmi
                            </span>
                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 py-2 px-3">
                                <i class="fas fa-truck me-1"></i> Pengiriman Cepat
                            </span>
                        </div>

                        <div class="card-text px-4 mb-4 text-start">
                            <p>
                                Welcome to <strong>Mots De Vivre</strong>. We are dedicated to help you enjoy for the shopping. 
                            </p>
                            <p>
                            How do you feel when words of life appear in your life? Of course, they can be a source of enthusiasm and motivation to get through the day. 
                            Mots De Vivre—which means "words of life" in French—is the meaning of life itself.

                            </p>
                        </div>

                        <hr class="my-4 opacity-25">

                        <!-- Info Kontak -->
                        <div class="row mb-4">
                             <div class="col-6">
                                <div class="p-3 rounded bg-body-tertiary">
                                    <i class="fas fa-map-marker-alt text-danger fs-4 mb-2"></i>
                                    <h6 class="fw-bold mb-1">Lokasi</h6>
                                    <small class="text-muted">Surabaya, Indonesia</small>
                                </div>
                             </div>
                             <div class="col-6">
                                <div class="p-3 rounded bg-body-tertiary">
                                    <i class="fas fa-envelope text-primary fs-4 mb-2"></i>
                                    <h6 class="fw-bold mb-1">Email</h6>
                                    <small class="text-muted">motsdevivre@gmail.com</small>
                                </div>
                             </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                            <a href="/shop" class="btn btn-primary px-4 fw-bold shadow-sm">
                                <i class="fas fa-shopping-bag me-2"></i> Mulai Belanja
                            </a>
                            <a href="#" class="btn btn-outline-secondary px-4 fw-bold">
                                <i class="fab fa-whatsapp me-2"></i> Chat Admin
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- SCRIPT TEMA -->
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
            if (theme === 'dark') {
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            } else {
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
            }
        }
    </script>
</body>
</html>