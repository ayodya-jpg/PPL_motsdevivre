<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Daftar Akun Baru') ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary-slate: #0f172a;
            --accent-amber: #fbbf24;
            --bg-light: #f8fafc;
            --card-shadow: 0 20px 50px -12px rgba(15, 23, 42, 0.1);
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            color: var(--primary-slate);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .register-card {
            border: none;
            border-radius: 35px;
            background: #ffffff;
            box-shadow: var(--card-shadow);
            width: 100%;
            max-width: 480px;
            overflow: hidden;
        }

        /* BANNER PROMO NEW USER */
        .promo-banner {
            background: linear-gradient(135deg, var(--accent-amber), #f59e0b);
            padding: 15px;
            text-align: center;
            color: var(--primary-slate);
            font-weight: 800;
            font-size: 0.85rem;
            display: none; /* Muncul via JS jika ada parameter promo */
            border-bottom: 4px solid rgba(0,0,0,0.05);
        }

        .brand-logo-container {
            background: var(--primary-slate);
            color: var(--accent-amber);
            width: 50px;
            height: 50px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.2rem;
            margin: 0 auto 20px;
        }

        .form-label {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #64748b;
            text-transform: uppercase;
        }

        .form-control {
            border-radius: 16px;
            padding: 14px 18px;
            border: 1.5px solid #e2e8f0;
            background: #f8fafc;
            font-size: 0.95rem;
        }

        .form-control:focus {
            box-shadow: 0 0 0 4px rgba(251, 191, 36, 0.1);
            border-color: var(--accent-amber);
            background: white;
        }

        .btn-register {
            background: var(--primary-slate);
            color: white;
            border: none;
            border-radius: 18px;
            padding: 16px;
            font-weight: 800;
            width: 100%;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.2);
            color: var(--accent-amber);
        }

        [data-bs-theme="dark"] {
            --bg-light: #0f172a;
            --primary-slate: #f8fafc;
        }
        [data-bs-theme="dark"] .register-card { background: #1e293b; }
        [data-bs-theme="dark"] .form-control { background: #334155; border-color: #475569; color: white; }
    </style>
</head>
<body>

    <div class="register-card">
        <div class="promo-banner" id="promoBanner">
            <i class="bi bi-stars me-2"></i> BENEFIT AKTIF: DISKON 20% & ONGKIR 10%
        </div>

        <div class="p-5">
            <div class="text-center">
                <div class="brand-logo-container">DV</div>
                <h3 class="fw-800 mb-1">Daftar Akun</h3>
                <p class="text-muted small mb-4">Dapatkan  parfum terbaik kami.</p>
            </div>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger border-0 rounded-4 small mb-4">
                    <i class="bi bi-exclamation-circle me-2"></i> <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form action="/auth/storeRegister" method="post">
                <input type="hidden" name="promo_code" id="promoInput">

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" placeholder="Masukkan nama Anda" value="<?= old('name') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat Email</label>
                    <input type="email" name="email" class="form-control" placeholder="nama@email.com" value="<?= old('email') ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Kata Sandi</label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
                </div>

                <button type="submit" class="btn btn-register">
                    Buat Akun Sekarang
                </button>
            </form>

            <div class="text-center mt-4">
                <p class="small text-muted mb-0">Sudah punya akun? <a href="/auth" class="text-warning fw-bold text-decoration-none">Masuk di sini</a></p>
            </div>

            <div class="text-center mt-3">
                <a href="/shop" class="text-muted small text-decoration-none opacity-50"><i class="bi bi-arrow-left"></i> Kembali ke Toko</a>
            </div>
        </div>
    </div>

    <script>
        // Tangkap parameter promo dari URL (misal: /register?promo=newuser)
        const urlParams = new URLSearchParams(window.location.search);
        const promoType = urlParams.get('promo');
        
        if (promoType === 'newuser') {
            document.getElementById('promoBanner').style.display = 'block';
            document.getElementById('promoInput').value = 'newuser';
        }

        // Terapkan Tema Sesuai LocalStorage
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
    </script>
</body>
</html>