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
    
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="Mid-client-IoyLJLDukf-pHuqF"></script>

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

        /* --- NAVIGATION --- */
        .double-header {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .brand-logo {
            background: var(--primary-slate);
            color: var(--accent-amber) !important;
            width: 40px; height: 40px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center; font-weight: 800;
        }

        .nav-custom .nav-link {
            font-weight: 600; color: #64748b !important;
            padding: 0.5rem 1.2rem; border-radius: 12px;
        }

        .nav-custom .nav-link.active {
            color: var(--primary-slate) !important;
            background: rgba(0,0,0,0.03);
        }

        /* --- SUBSCRIPTION CARDS --- */
        .plan-card {
            border: none;
            border-radius: 32px;
            background: #ffffff;
            box-shadow: var(--card-shadow);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            overflow: hidden;
            position: relative;
        }

        .plan-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 30px 60px -12px rgba(15, 23, 42, 0.15);
        }

        .plan-card.featured {
            border: 2px solid var(--accent-amber);
        }

        .plan-header {
            padding: 40px 30px 20px;
            text-align: center;
        }

        .plan-icon {
            width: 64px; height: 64px;
            background: #fef3c7;
            color: #d97706;
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
            font-size: 1.75rem;
        }

        .price-tag {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-slate);
            margin-bottom: 0;
        }

        .price-period {
            color: #64748b;
            font-size: 1rem;
            font-weight: 500;
        }

        .feature-list {
            padding: 30px;
            list-style: none;
            margin: 0;
        }

        .feature-list li {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.95rem;
            color: #475569;
        }

        .btn-subscribe {
            background: var(--primary-slate);
            color: white;
            border: none;
            border-radius: 16px;
            padding: 16px;
            font-weight: 700;
            width: 100%;
            transition: all 0.3s;
        }

        .plan-card.featured .btn-subscribe {
            background: var(--accent-amber);
            color: var(--primary-slate);
        }

        .btn-subscribe:hover {
            opacity: 0.9;
            transform: scale(1.02);
        }

        .theme-toggle-btn {
            background: #f1f5f9; border: none;
            padding: 10px 18px; border-radius: 14px;
        }

        [data-bs-theme="dark"] {
            --bg-light: #0f172a;
            --primary-slate: #f8fafc;
        }
        [data-bs-theme="dark"] .plan-card { background: #1e293b; }
        [data-bs-theme="dark"] .double-header { background: rgba(15, 23, 42, 0.9) !important; }
        [data-bs-theme="dark"] .feature-list li { color: #94a3b8; }
        [data-bs-theme="dark"] .price-tag { color: white; }
    </style>
</head>
<body>

    <header class="double-header sticky-top mb-5">
        <div class="header-top py-3">
            <div class="container d-flex justify-content-between align-items-center">
                <a class="navbar-brand fw-bolder d-flex align-items-center text-decoration-none fs-4" href="/shop">
                    <div class="brand-logo me-2">TJ</div> 
                    <span class="text-body tracking-tighter">Telur Josjis</span>
                </a>

                <div class="d-flex align-items-center gap-2">
                    <nav class="nav nav-custom d-none d-lg-flex me-3">
                        <a class="nav-link" href="/shop">Home</a>
                        <?php if (session()->get('is_logged_in')) : ?>
                            <a class="nav-link" href="/cart">Cart</a>
                            <a class="nav-link" href="/orders">Orders</a>
                            <a class="nav-link active" href="/subscriptions">Subscriptions</a>
                            <a class="nav-link" href="/profile">Profile</a>
                        <?php endif; ?>
                    </nav>
                    <button class="theme-toggle-btn" id="themeToggle"><i class="fas fa-sun" id="themeIcon"></i></button>
                    <button class="btn btn-outline-secondary border-0 d-lg-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu"><i class="fas fa-bars"></i></button>
                </div>
            </div>
        </div>
    </header>

    <div class="collapse border-top border-secondary bg-body shadow-sm d-lg-none sticky-top" id="mobileMenu" style="top: 80px; z-index: 1040;">
        <div class="container py-3">
            <a href="/shop" class="d-block py-2 text-decoration-none text-body fw-bold">Home</a>
            <a href="/subscriptions" class="d-block py-2 text-decoration-none text-warning fw-bold">Subscriptions</a>
            <?php if (session()->get('is_logged_in')) : ?>
                <a href="/cart" class="d-block py-2 text-decoration-none text-body">Cart</a>
                <a href="/orders" class="d-block py-2 text-decoration-none text-body">Orders</a>
                <a href="/profile" class="d-block py-2 text-decoration-none text-body">Profile</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="container pb-5">
        <div class="text-center mb-5">
            <span class="badge bg-amber-subtle text-warning px-3 py-2 rounded-pill fw-bold mb-3">MEMBERSHIP PELAKU USAHA</span>
            <h1 class="fw-900 mb-2">Membership Pelaku Usaha</h1>
            <p class="text-muted mx-auto" style="max-width: 500px;">Dapatkan akses tercepat, analytics dashboard, dan fitur eksklusif lainnya untuk mengembangkan bisnis telur Anda.</p>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="plan-card h-100 featured">
                    <div class="bg-warning text-dark fw-bold text-center py-1 small" style="letter-spacing: 1px;">EKSKLUSIF UNTUK PELAKU USAHA</div>

                    <div class="plan-header">
                        <div class="plan-icon">
                            <i class="fas fa-crown"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Paket Pelaku Usaha</h4>
                        <div class="d-flex justify-content-center align-items-baseline gap-1">
                            <span class="price-tag">Rp500k</span>
                            <span class="price-period">/bln</span>
                        </div>
                    </div>

                    <ul class="feature-list">
                        <li><i class="bi bi-check-circle-fill text-success"></i> <strong>Layanan Tercepat</strong></li>
                        <li><i class="bi bi-check-circle-fill text-success"></i> Akses Analytics Dashboard</li>
                        <li><i class="bi bi-check-circle-fill text-success"></i> Prioritas Customer Support</li>
                        <li><i class="bi bi-check-circle-fill text-success"></i> Fitur Promo & Diskon Khusus</li>
                        <li><i class="bi bi-check-circle-fill text-success"></i> Update Harga Real-time</li>
                    </ul>

                    <div id="actionContainer" class="px-4 pb-5 mt-auto">
                        <button class="btn btn-subscribe shadow-lg" onclick="subscribeMembership()">
                            Daftar Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="/shop" class="btn btn-link text-muted text-decoration-none">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Katalog Produk
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // --- 1. SETUP VARIABEL ---
        const userId = <?= session()->get('user_id') ?? 'null' ?>;
        // Pastikan Port sesuai Backend Laravel
        const API_BASE_URL = 'http://localhost:8090/api'; 

        // --- 2. CEK STATUS SAAT LOAD HALAMAN ---
        async function checkStatus() {
            if (!userId) return; // Jika belum login, biarkan tombol default (nanti diredirect saat klik)

            try {
                // Tampilkan loading kecil di tombol (opsional)
                // fetch status ke backend
                const response = await fetch(`${API_BASE_URL}/membership/status/${userId}`);
                const result = await response.json();

                // Jika Member Aktif -> Ganti tombol "Daftar" jadi "Buka Analytics"
                if (result.success && result.status === 'active') {
                    const container = document.getElementById('actionContainer');
                    container.innerHTML = `
                        <div class="alert alert-success text-center py-2 mb-3 small fw-bold">
                            <i class="bi bi-patch-check-fill me-1"></i> MEMBERSHIP AKTIF
                        </div>
                        <a href="/analytics" class="btn btn-success w-100 py-3 rounded-4 fw-bold shadow-sm">
                            Buka Dashboard Analytics <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    `;
                }
            } catch (error) {
                console.error("Gagal cek status:", error);
            }
        }

        // --- 3. FUNGSI BELI (CHECKOUT) ---
        function subscribeMembership() {
            const btn = event.target;
            const originalText = btn.innerHTML;

            if (!userId) {
                alert('Silakan login terlebih dahulu');
                window.location.href = '/auth/login';
                return;
            }

            if (!confirm('Konfirmasi pendaftaran Membership Pelaku Usaha seharga Rp 500.000 /bulan?')) return;

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';

            // Kirim data user juga (opsional, untuk Midtrans)
            const payload = {
                user_id: userId,
                name: '<?= session()->get('name') ?? "User" ?>',
                email: '<?= session()->get('email') ?? "email@example.com" ?>'
            };

            fetch(`${API_BASE_URL}/membership/checkout`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                const snapToken = data.data?.snap_token;
                
                if (!data.success || !snapToken) {
                    throw new Error(data.message || 'Gagal mendapatkan token');
                }

                // Buka Snap Popup
                window.snap.pay(snapToken, {
                    onSuccess: () => { 
                        alert('Pembayaran berhasil! Membership Anda aktif.');
                        // Redirect langsung ke Analytics karena auto-active
                        window.location.href = '/analytics'; 
                    },
                    onPending: () => { 
                        alert('Pembayaran tertunda. Silakan selesaikan pembayaran.');
                        window.location.reload(); // Reload untuk cek status
                    },
                    onError: (err) => { 
                        alert('Pembayaran gagal.'); 
                        btn.disabled = false; 
                        btn.innerHTML = originalText; 
                    },
                    onClose: () => { 
                        alert('Anda menutup popup pembayaran.');
                        btn.disabled = false; 
                        btn.innerHTML = originalText; 
                        // Cek status lagi, siapa tau user sebenernya udah bayar
                        checkStatus();
                    }
                });
            })
            .catch(err => {
                alert('Gagal: ' + err.message);
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        }   

        // --- 4. DARK MODE LOGIC ---
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

        // --- 5. JALANKAN SAAT LOAD ---
        checkStatus();
    </script>
</body>
</html>