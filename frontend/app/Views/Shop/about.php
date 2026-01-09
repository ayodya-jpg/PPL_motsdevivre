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

        /* --- MODERN NAVIGATION (Glassmorphism) --- */
        .double-header {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .brand-logo {
            background: var(--primary-slate);
            color: var(--accent-amber) !important;
            width: 40px; height: 40px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800;
        }

        .nav-custom .nav-link {
            font-weight: 600; color: #64748b !important;
            padding: 0.5rem 1.2rem; border-radius: 12px;
        }

        .nav-custom .nav-link.active {
            color: var(--primary-slate) !important;
            background: rgba(0,0,0,0.03);
        }

        /* --- ABOUT COMPONENTS --- */
        .about-card {
            border: none;
            border-radius: 35px;
            background: #ffffff;
            box-shadow: var(--card-shadow);
            overflow: hidden;
        }

        .about-header-bg {
            background: linear-gradient(135deg, var(--primary-slate) 0%, #334155 100%);
            height: 180px;
        }

        .logo-wrapper {
            width: 140px; height: 140px;
            border-radius: 40px;
            background: white;
            padding: 8px;
            margin-top: -70px;
            display: inline-block;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        .logo-img {
            width: 100%; height: 100%;
            border-radius: 32px;
            object-fit: cover;
        }

        .feature-badge {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 12px 20px;
            border-radius: 20px;
            transition: all 0.3s;
        }

        .feature-badge:hover {
            border-color: var(--accent-amber);
            transform: translateY(-3px);
            background: white;
        }

        .btn-theme-toggle {
            background: #f1f5f9; border: none;
            padding: 10px 18px; border-radius: 14px;
        }

        [data-bs-theme="dark"] {
            --bg-light: #0f172a;
            --primary-slate: #f8fafc;
        }
        [data-bs-theme="dark"] .about-card { background: #1e293b; }
        [data-bs-theme="dark"] .feature-badge { background: #0f172a; border-color: #334155; }
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
                        <?php if (session()->get('is_logged_in')) : ?>
                            <a class="nav-link" href="/cart">Cart <span class="badge bg-danger rounded-pill"><?= $cart_count ?? 0 ?></span></a>
                            <a class="nav-link" href="/orders">Orders</a>
                            <a class="nav-link" href="/profile">Profile</a>
                        <?php endif; ?>
                        <a class="nav-link active" href="/about">About Us</a>
                    </nav>
                    <button class="btn-theme-toggle" id="themeToggle"><i class="fas fa-sun" id="themeIcon"></i></button>
                    <button class="btn btn-outline-secondary border-0 d-lg-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu"><i class="fas fa-bars"></i></button>
                </div>
            </div>
        </div>
    </header>

    <div class="collapse border-top border-secondary bg-body shadow-sm d-lg-none sticky-top" id="mobileMenu" style="top: 80px; z-index: 1040;">
        <div class="container py-3">
            <a href="/shop" class="d-block py-2 text-decoration-none text-body fw-bold">Home</a>
            <a href="/about" class="d-block py-2 text-decoration-none text-warning fw-bold">About Us</a>
            <?php if (session()->get('is_logged_in')) : ?>
                <a href="/cart" class="d-block py-2 text-decoration-none text-body">Cart</a>
                <a href="/orders" class="d-block py-2 text-decoration-none text-body">Orders</a>
                <a href="/profile" class="d-block py-2 text-decoration-none text-body">Profile</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="container pb-5 mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="about-card">
                    <div class="about-header-bg"></div>
                    
                    <div class="card-body px-4 px-md-5 pb-5 text-center">
                        <div class="logo-wrapper">
                            <img src="<?= base_url('images/logo_toko.jpeg') ?>" class="logo-img" alt="Logo Mots">
                        </div>

                        <h1 class="fw-800 mt-4 mb-2">Mots De Vivre</h1>
                        <p class="text-muted mb-5 italic font-monospace">"Kesegaran dari Aroma, Kebahagiaan untuk sekitar"</p>

                        <div class="row g-3 mb-5">
                            <div class="col-md-4">
                                <div class="feature-badge">
                                    <i class="fas fa-spray-can text-warning fs-4 mb-2 d-block"></i>
                                    <span class="fw-bold small">Extrait De Parfume</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="feature-badge">
                                    <i class="fas fa-award text-primary fs-4 mb-2 d-block"></i>
                                    <span class="fw-bold small">Kualitas Premium</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="feature-badge">
                                    <i class="fas fa-truck-fast text-success fs-4 mb-2 d-block"></i>
                                    <span class="fw-bold small">Pengiriman Instan</span>
                                </div>
                            </div>
                        </div>

                        <div class="text-start px-md-4">
                            <div class="row g-5">
                                <div class="col-md-7">
                                    <h4 class="fw-800 mb-4 text-primary-slate">Kisah Kami</h4>
                                    <p class="text-muted leading-relaxed">
                                        Berawal dari Surabaya, <strong>Mots De Vivre</strong> lahir untuk menjembatani antara kamu dengan cara mengekpreksikan dirimu sendiri
                                    </p>
                                    <p class="text-muted leading-relaxed">
                                        Setiap produk kami telah melalui quality checking dari tim kami agar memastikan parfum kesukaan kamu selalu dalam on point hingga kedepan rumahmu.
                                    </p>
                                </div>
                                <div class="col-md-5">
                                    <div class="p-4 rounded-4" style="background: rgba(251, 191, 36, 0.05); border: 1px dashed var(--accent-amber);">
                                        <h6 class="fw-bold mb-3"><i class="bi bi-info-circle-fill me-2"></i> Hubungi Kami</h6>
                                        <div class="mb-3">
                                            <small class="text-muted d-block uppercase font-bold" style="font-size: 10px;">LOKASI PUSAT</small>
                                            <span class="fw-bold small">Surabaya, Jawa Timur, Indonesia</span>
                                        </div>
                                        <div class="mb-3">
                                            <small class="text-muted d-block uppercase font-bold" style="font-size: 10px;">EMAIL BISNIS</small>
                                            <span class="fw-bold small">motsdevivre@gmail.com</span>
                                        </div>
                                        <div class="mt-4">
                                            <a href="#" class="btn btn-warning w-100 rounded-pill fw-bold shadow-sm">
                                                <i class="fab fa-whatsapp me-2"></i> Chat Admin
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-5 opacity-10">

                        <div class="d-flex flex-column flex-md-row justify-content-center gap-3">
                            <a href="/shop" class="btn btn-dark rounded-pill px-5 py-3 fw-bold">
                                <i class="bi bi-bag-check me-2"></i> Mulai Belanja Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
    </script>
</body>
</html>