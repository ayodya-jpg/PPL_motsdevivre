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

        .profile-card {
            border: none;
            border-radius: 30px;
            background: #ffffff;
            box-shadow: var(--card-shadow);
            overflow: hidden;
        }

        .profile-header-bg {
            background: linear-gradient(135deg, var(--primary-slate), #334155);
            height: 140px;
        }

        .avatar-wrapper {
            width: 110px; height: 110px;
            border-radius: 35px;
            background: white;
            padding: 5px;
            margin-top: -55px;
            display: inline-block;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .avatar-box {
            width: 100%; height: 100%;
            border-radius: 30px;
            background: var(--accent-amber);
            display: flex; align-items: center; justify-content: center;
            font-size: 2.5rem; color: var(--primary-slate);
        }

        .form-control {
            border-radius: 16px;
            padding: 12px 18px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .form-control:focus {
            box-shadow: 0 0 0 4px rgba(251, 191, 36, 0.1);
            border-color: var(--accent-amber);
        }

        .address-item {
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 20px;
            transition: all 0.3s;
        }

        .address-item:hover {
            border-color: var(--accent-amber);
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
        [data-bs-theme="dark"] .profile-card { background: #1e293b; }
        [data-bs-theme="dark"] .address-item { background: #0f172a; border-color: #334155; }
        [data-bs-theme="dark"] .double-header { background: rgba(15, 23, 42, 0.9) !important; }
        [data-bs-theme="dark"] .form-control { background: #334155; border: none; color: white; }
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
                        <a class="nav-link" href="/cart">Cart <span class="badge bg-danger rounded-pill"><?= $cart_count ?? 0 ?></span></a>
                        <a class="nav-link" href="/orders">Orders</a>
                        <a class="nav-link active" href="/profile">Profile</a>
                    </nav>
                    <button class="btn-theme-toggle" id="themeToggle"><i class="fas fa-sun" id="themeIcon"></i></button>
                    <button class="btn btn-outline-secondary border-0 d-lg-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu"><i class="fas fa-bars"></i></button>
                </div>
            </div>
        </div>
    </header>

    <div class="container pb-5">
        
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">
                <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
                <i class="fas fa-exclamation-circle me-2"></i> <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="row g-5">
            <div class="col-lg-4">
                <div class="profile-card text-center">
                    <div class="profile-header-bg"></div>
                    <div class="card-body px-4 pb-5">
                        <div class="avatar-wrapper">
                            <div class="avatar-box">
                                <i class="bi bi-person-fill"></i>
                            </div>
                        </div>
                        
                        <h4 class="fw-800 mt-3 mb-1"><?= esc($user->name) ?></h4>
                        <p class="text-muted small mb-4"><?= esc($user->email) ?></p>

                        <hr class="my-4 opacity-10">

                        <div class="text-start px-2">
                            <div class="mb-3">
                                <small class="text-muted fw-bold d-block mb-1">BERGABUNG SEJAK</small>
                                <span class="fw-bold"><i class="bi bi-calendar3 me-2 text-amber"></i> <?= date('d F Y', strtotime($user->created_at)) ?></span>
                            </div>
                            <div class="mb-0">
                                <small class="text-muted fw-bold d-block mb-1">TOTAL PESANAN</small>
                                <span class="fw-bold"><i class="bi bi-box-seam me-2 text-amber"></i> <?= $total_transaksi ?? 0 ?> Transaksi</span> 
                            </div>
                        </div>

                        <div class="mt-5">
                            <a href="/auth/logout" class="btn btn-outline-danger w-100 rounded-4 fw-bold">
                                <i class="bi bi-box-arrow-right me-2"></i> Keluar Akun
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="profile-card h-100">
                    <div class="p-4 p-md-5">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <div>
                                <h3 class="fw-800 mb-1">Alamat Pengiriman</h3>
                                <p class="text-muted small mb-0">Kelola lokasi tujuan pengiriman Anda.</p>
                            </div>
                            <?php if ($address): ?>
                                <button class="btn btn-dark rounded-pill px-4 btn-sm fw-bold" onclick="toggleForm()">
                                    <i class="bi bi-pencil-square me-2"></i> Edit Alamat
                                </button>
                            <?php endif; ?>
                        </div>

                        <?php if (isset($address) && $address): ?>
                            <div id="addressStatic" class="address-item p-4">
                                <div class="row align-items-center">
                                    <div class="col-md-1 d-none d-md-block text-center">
                                        <div class="bg-white rounded-circle p-2 text-danger shadow-sm">
                                            <i class="bi bi-geo-alt-fill fs-4"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-11 ps-md-4">
                                        <h6 class="fw-bold mb-2"><?= esc($address->nama_penerima) ?> <span class="text-muted fw-normal ms-2">• <?= esc($address->no_hp) ?></span></h6>
                                        <p class="mb-1 opacity-75"><?= esc($address->alamat_lengkap) ?></p>
                                        <p class="mb-0 fw-bold text-primary small"><?= esc($address->kota) ?>, <?= esc($address->provinsi) ?> - <?= esc($address->kode_pos) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5" id="noAddressMsg">
                                <img src="https://illustrations.popsy.co/slate/delivery-address.svg" style="width: 200px" class="mb-4">
                                <h5>Belum ada alamat nih...</h5>
                                <p class="text-muted">Isi alamat agar kami tahu ke mana harus mengirim parfum mu.</p>
                                <button class="btn btn-dark rounded-pill px-5 py-3 fw-bold mt-3" onclick="toggleForm()">
                                    + Tambah Alamat Sekarang
                                </button>
                            </div>
                        <?php endif; ?>

                        <div id="addressForm" style="display: none;" class="mt-2">
                            <form action="<?= base_url('profile/saveAddress') ?>" method="POST" id="formAddress">
                                <?= csrf_field() ?>
                                
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted">NAMA PENERIMA</label>
                                        <input 
                                            type="text" 
                                            name="nama_penerima" 
                                            class="form-control" 
                                            value="<?= isset($address->nama_penerima) ? esc($address->nama_penerima) : '' ?>" 
                                            required
                                        >
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted">NOMOR HANDPHONE</label>
                                        <input 
                                            type="tel" 
                                            name="no_hp" 
                                            id="inputNoHP"
                                            class="form-control" 
                                            value="<?= isset($address->no_hp) ? esc($address->no_hp) : '' ?>" 
                                            placeholder="08xxxxxxxxxx"
                                            required
                                        >
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-bold text-muted">ALAMAT LENGKAP</label>
                                        <textarea 
                                            name="alamat_lengkap" 
                                            class="form-control" 
                                            rows="3" 
                                            placeholder="Contoh: Jl. Sukses No. 123, RT 01 RW 02" 
                                            required
                                        ><?= isset($address->alamat_lengkap) ? esc($address->alamat_lengkap) : '' ?></textarea>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label small fw-bold text-muted">KOTA / KABUPATEN</label>
                                        <input 
                                            type="text" 
                                            name="kota" 
                                            class="form-control" 
                                            value="<?= isset($address->kota) ? esc($address->kota) : '' ?>" 
                                            required
                                        >
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold text-muted">PROVINSI</label>
                                        <input 
                                            type="text" 
                                            name="provinsi" 
                                            class="form-control" 
                                            value="<?= isset($address->provinsi) ? esc($address->provinsi) : '' ?>"
                                        >
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-bold text-muted">KODE POS</label>
                                        <input 
                                            type="text" 
                                            name="kode_pos" 
                                            class="form-control" 
                                            value="<?= isset($address->kode_pos) ? esc($address->kode_pos) : '' ?>"
                                        >
                                    </div>
                                </div>
                                
                                <div class="mt-5 d-flex gap-3">
                                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" onclick="toggleForm()">Batal</button>
                                    <button type="submit" class="btn btn-warning rounded-pill px-5 fw-bold shadow-sm">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleForm() {
            const form = document.getElementById('addressForm');
            const staticInfo = document.getElementById('addressStatic');
            const emptyInfo = document.getElementById('noAddressMsg');
            
            if (form.style.display === 'none') {
                form.style.display = 'block';
                if(staticInfo) staticInfo.style.display = 'none';
                if(emptyInfo) emptyInfo.style.display = 'none';
            } else {
                form.style.display = 'none';
                if(staticInfo) staticInfo.style.display = 'block';
                if(emptyInfo) emptyInfo.style.display = 'block';
            }
        }

        // DEBUG FORM SUBMIT
        document.getElementById('formAddress')?.addEventListener('submit', function(e) {
            const noHP = document.getElementById('inputNoHP').value;
            if (!noHP || noHP.trim() === '') {
                e.preventDefault();
                alert('Nomor HP harus diisi!');
                return false;
            }
        });

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