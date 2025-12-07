<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        /* Style Konsisten dengan Halaman Lain */
        body { transition: background-color 0.3s, color 0.3s; }
        .nav-custom .nav-link { font-size: 0.9rem; display: flex; flex-direction: column; align-items: center; padding: 0.5rem 1rem; opacity: 0.7; }
        .nav-custom .nav-link:hover, .nav-custom .nav-link.active { opacity: 1; }
        .nav-custom .nav-link i { font-size: 1.2rem; margin-bottom: 4px; }
        .search-input { border: 1px solid rgba(255,255,255,0.2); }
        [data-bs-theme="light"] .search-input { border: 1px solid #ced4da; background-color: #fff; color: #212529; }
        [data-bs-theme="dark"] .search-input { background-color: #2b3035; color: #fff; border-color: #495057; }
        
        /* Profile Card Specific */
        .profile-header-bg {
            background: linear-gradient(135deg, #6610f2, #d63384); /* Warna Ungu Pink */
            height: 120px;
            border-radius: 8px 8px 0 0;
        }
        .avatar-container {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid;
            margin-top: -50px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: #6610f2;
        }
        [data-bs-theme="dark"] .avatar-container { border-color: #212529; }
        [data-bs-theme="light"] .avatar-container { border-color: #fff; }
    </style>
</head>
<body>

    <!-- HEADER (Update Menu Profile disini) -->
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
                            <i class="bi bi-bag"></i> Cart
                                <?php if(isset($cart_count) && $cart_count > 0): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;"><?= $cart_count ?></span>
                                <?php endif; ?>
                            </a>
                            <a class="nav-link text-body" href="/orders"><i class="bi bi-receipt"></i> Orders</a>
                            
                            <!-- MENU PROFILE (Menggantikan Products) -->
                            <a class="nav-link active text-warning fw-bold" href="/profile">
                            <i class="bi bi-person-fill"></i> Profile
                            </a>
                        <?php endif; ?>
                    </nav>

                    <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" id="themeToggle">
                        <i class="fas fa-sun" id="themeIcon"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- CONTENT PROFILE -->
    <div class="container pb-5">
        
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <div class="row g-4">
            <!-- KOLOM KIRI: DATA DIRI -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center">
                    <div class="profile-header-bg"></div>
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <div class="avatar-container shadow-sm">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <h4 class="mt-3 fw-bold"><?= esc($user->name) ?></h4>
                        <p class="text-muted mb-1"><?= esc($user->email) ?></p>
                        <span class="badge bg-primary bg-opacity-10 text-primary mb-3">Pelanggan</span>
                        
                        <hr>
                        <div class="text-start px-2">
                            <small class="text-muted fw-bold">Bergabung Sejak</small>
                            <p><i class="far fa-calendar-alt me-2"></i> <?= date('d F Y', strtotime($user->created_at)) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: ALAMAT PENGIRIMAN -->
            <div class="col-md-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 pt-4 ps-4">
                        <h5 class="fw-bold mb-0"><i class="fas fa-map-marker-alt me-2 text-danger"></i> Alamat Pengiriman</h5>
                    </div>
                    <div class="card-body p-4">
                        
                        <!-- LOGIKA: Jika Alamat Sudah Ada -->
                        <?php if(isset($user->address) && $user->address): ?>
                            <div class="alert alert-light border shadow-sm">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="fw-bold mb-1"><?= esc($user->address->nama_penerima) ?> <span class="text-muted fw-normal">(<?= esc($user->address->no_hp) ?>)</span></h6>
                                        <p class="mb-1 text-muted small"><?= esc($user->address->alamat_lengkap) ?></p>
                                        <p class="mb-0 text-muted small">
                                            <?= esc($user->address->kota) ?>, <?= esc($user->address->provinsi) ?> - <?= esc($user->address->kode_pos) ?>
                                        </p>
                                    </div>
                                    <button class="btn btn-sm btn-outline-primary" onclick="toggleForm()"><i class="fas fa-pencil-alt"></i> Edit</button>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- Jika Belum Ada Alamat -->
                            <div class="text-center py-4" id="noAddressMsg">
                                <img src="https://cdn-icons-png.flaticon.com/512/3710/3710268.png" width="80" class="mb-3 opacity-50" alt="No Address">
                                <p class="text-muted">Anda belum menambahkan alamat pengiriman.</p>
                                <button class="btn btn-primary" onclick="toggleForm()">+ Tambah Alamat</button>
                            </div>
                        <?php endif; ?>

                        <!-- FORM ALAMAT (Disembunyikan Default jika sudah ada alamat) -->
                        <div id="addressForm" style="display: <?= (isset($user->address) && $user->address) ? 'none' : 'none' ?>;" class="mt-4">
                            <h6 class="fw-bold border-bottom pb-2 mb-3">Form Alamat</h6>
                            <form action="/profile/saveAddress" method="post">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small">Nama Penerima</label>
                                        <input type="text" name="nama_penerima" class="form-control" value="<?= $user->address->nama_penerima ?? '' ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">No. Handphone</label>
                                        <input type="text" name="no_hp" class="form-control" value="<?= $user->address->no_hp ?? '' ?>" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small">Alamat Lengkap</label>
                                        <textarea name="alamat_lengkap" class="form-control" rows="2" placeholder="Nama Jalan, No. Rumah, RT/RW" required><?= $user->address->alamat_lengkap ?? '' ?></textarea>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label small">Kota/Kabupaten</label>
                                        <input type="text" name="kota" class="form-control" value="<?= $user->address->kota ?? '' ?>" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small">Provinsi</label>
                                        <input type="text" name="provinsi" class="form-control" value="<?= $user->address->provinsi ?? '' ?>" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small">Kode Pos</label>
                                        <input type="text" name="kode_pos" class="form-control" value="<?= $user->address->kode_pos ?? '' ?>" required>
                                    </div>
                                </div>
                                <div class="mt-3 text-end">
                                    <button type="button" class="btn btn-light me-2" onclick="toggleForm()">Batal</button>
                                    <button type="submit" class="btn btn-success">Simpan Alamat</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script Toggle Form Alamat
        function toggleForm() {
            var form = document.getElementById('addressForm');
            var msg = document.getElementById('noAddressMsg');
            
            if (form.style.display === 'none') {
                form.style.display = 'block';
                if(msg) msg.style.display = 'none';
            } else {
                form.style.display = 'none';
                if(msg) msg.style.display = 'block';
            }
        }

        // Script Ganti Tema (Sama seperti sebelumnya)
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