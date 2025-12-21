<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { 
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--bs-body-bg);
            transition: background-color 0.3s;
        }
        .register-card {
            width: 100%;
            max-width: 400px;
            border: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="card register-card p-4">
                <div class="card-body">
                    <h2 class="text-center fw-bold mb-2">Daftar Akun</h2>
                    <p class="text-center text-muted small mb-4">Bergabunglah bersama kami sekarang</p>

                    <!-- Pesan Error -->
                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger py-2 small">
                            <?= session()->getFlashdata('error') ?>
                            <?php if(session()->getFlashdata('errors_list')): ?>
                                <ul class="mb-0 ps-3 mt-1">
                                    <?php foreach(session()->getFlashdata('errors_list') as $err): ?>
                                        <li><?= is_array($err) ? implode(', ', $err) : $err ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <form action="/auth/registerProcess" method="post">
                        
                        <!-- LOGIKA PROMO (INPUT HIDDEN) -->
                        <input type="hidden" name="promo_code" value="<?= esc($promo_code ?? '') ?>">
                        
                        <!-- Tampilkan Info Jika Ada Promo -->
                        <?php if(!empty($promo_code)): ?>
                            <div class="alert alert-success d-flex align-items-center py-2 mb-3 small" role="alert">
                                <i class="fas fa-gift me-2 fs-5"></i>
                                <div>
                                    <strong>Promo Diaktifkan!</strong><br>
                                    Anda akan mendapatkan Gratis Ongkir & Diskon.
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Nama Lengkap</label>
                            <input type="text" class="form-control" name="name" required value="<?= old('name') ?>" placeholder="Nama Anda">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Email</label>
                            <input type="email" class="form-control" name="email" required value="<?= old('email') ?>" placeholder="email@contoh.com">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold small">Password</label>
                            <input type="password" class="form-control" name="password" required placeholder="Minimal 6 karakter">
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold">Daftar Sekarang</button>
                    </form>

                    <div class="text-center mt-4 small">
                        Sudah punya akun? <a href="/auth" class="text-decoration-none fw-bold">Login disini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>