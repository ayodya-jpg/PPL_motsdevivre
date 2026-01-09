<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Masuk ke Akun - Mots De Vivre') ?></title>
    
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

        .login-card {
            border: none;
            border-radius: 35px;
            background: #ffffff;
            box-shadow: var(--card-shadow);
            width: 100%;
            max-width: 450px;
            overflow: hidden;
        }

        .brand-logo-container {
            background: var(--primary-slate);
            color: var(--accent-amber);
            width: 55px;
            height: 55px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.4rem;
            margin: 0 auto 20px;
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.15);
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

        .btn-login {
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

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.2);
            color: var(--accent-amber);
        }

        /* Dark Mode overrides */
        [data-bs-theme="dark"] {
            --bg-light: #0f172a;
            --primary-slate: #f8fafc;
        }
        [data-bs-theme="dark"] .login-card { background: #1e293b; }
        [data-bs-theme="dark"] .form-control { background: #334155; border-color: #475569; color: white; }
        [data-bs-theme="dark"] .brand-logo-container { background: var(--accent-amber); color: #0f172a; }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="p-5">
            <div class="text-center">
                <div class="brand-logo-container">DV</div>
                <h3 class="fw-800 mb-1">Selamat Datang</h3>
                <p class="text-muted small mb-4">Masuk untuk menikmati produk pilihanmu.</p>
            </div>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger border-0 rounded-4 small mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-success border-0 rounded-4 small mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <form id="loginForm">
                <div class="mb-3">
                    <label class="form-label">Alamat Email</label>
                    <div class="input-group">
                        <input type="email" name="email" id="email" class="form-control" placeholder="nama@email.com" required>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="form-label">Kata Sandi</label>
                        <a href="#" class="text-warning small fw-bold text-decoration-none" style="font-size: 11px;">Lupa Sandi?</a>
                    </div>
                    <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                </div>

                <div id="errorMsg" class="alert alert-danger border-0 rounded-4 small mb-3" style="display:none;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <span id="errorText"></span>
                </div>

                <button type="submit" class="btn btn-login" id="btnLogin">
                    Masuk Sekarang
                </button>

                <div class="text-center mt-3">
                    <p class="text-muted small">Atau masuk dengan</p>
                    <a href="/auth/google" class="btn btn-outline-dark w-100 rounded-pill py-2" style="border-color: #ddd;">
                        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google" width="18" class="me-2">
                        Sign in with Google
                    </a>
                </div>
            </form>

            <div class="text-center mt-4">
                <p class="small text-muted mb-0">Belum punya akun? <a href="/register" class="text-warning fw-bold text-decoration-none">Daftar di sini</a></p>
            </div>

            <div class="text-center mt-3">
                <a href="/shop" class="text-muted small text-decoration-none opacity-50"><i class="bi bi-arrow-left"></i> Kembali ke Toko</a>
            </div>
        </div>
    </div>

<script>
const savedTheme = localStorage.getItem('theme') || 'light';
document.documentElement.setAttribute('data-bs-theme', savedTheme);

const loginForm = document.getElementById('loginForm');
const btnLogin = document.getElementById('btnLogin');
const errorMsg = document.getElementById('errorMsg');
const errorText = document.getElementById('errorText');

loginForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    btnLogin.disabled = true;
    btnLogin.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    errorMsg.style.display = 'none';
    
    const formData = new FormData(this);
    
    try {
        // ✅ SUBMIT KE CODEIGNITER CONTROLLER!
        const response = await fetch('/auth/loginProcess', {
            method: 'POST',
            body: formData
        });
        
        // ✅ Follow redirect
        if (response.redirected) {
            window.location.href = response.url;
            return;
        }
        
        // Handle error response
        const text = await response.text();
        if (text.includes('error')) {
            errorText.textContent = 'Email atau Password salah';
            errorMsg.style.display = 'block';
            btnLogin.disabled = false;
            btnLogin.innerHTML = 'Masuk Sekarang';
        } else {
            // Success - redirect
            window.location.href = '/shop';
        }
    } catch (error) {
        console.error('Login error:', error);
        errorText.textContent = 'Terjadi kesalahan. Coba lagi.';
        errorMsg.style.display = 'block';
        btnLogin.disabled = false;
        btnLogin.innerHTML = 'Masuk Sekarang';
    }
});
</script>

</body>
</html>
