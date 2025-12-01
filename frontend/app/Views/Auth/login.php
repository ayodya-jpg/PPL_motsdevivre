<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <title>Login Toko Online</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap 5 CSS (Wajib untuk Dark Mode Otomatis) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { 
            font-family: sans-serif; 
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            /* Menggunakan variabel Bootstrap agar warna berubah otomatis sesuai tema */
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            transition: background-color 0.3s, color 0.3s;
        }

        .login-container { 
            /* Menggunakan variabel Bootstrap untuk background card */
            background-color: var(--bs-body-bg);
            border: 1px solid var(--bs-border-color);
            padding: 30px; 
            border-radius: 8px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.1); 
            width: 350px; 
            transition: background-color 0.3s, border-color 0.3s;
        }

        /* Tombol Ganti Tema (Pojok Kanan Atas) */
        .theme-toggle-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: 1px solid var(--bs-border-color);
            background: transparent;
            color: var(--bs-body-color);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.3s;
        }
        .theme-toggle-btn:hover {
            background-color: rgba(128, 128, 128, 0.1);
        }
    </style>
</head>
<body>

    <!-- Tombol Ganti Tema (Hanya Ikon) -->
    <button class="theme-toggle-btn" id="themeToggle" title="Ganti Tema">
        <i class="fas fa-sun" id="themeIcon"></i>
    </button>

    <div class="login-container">
        <h2 class="text-center fw-bold mb-4" style="margin-top: 0;">Login</h2>
        
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success py-2 text-center" role="alert">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger py-2 text-center" role="alert">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="/auth/loginProcess" method="post">
            <div class="mb-3">
                <label class="form-label fw-bold small">Email</label>
                <!-- class form-control otomatis menyesuaikan warna input di dark/light mode -->
                <input type="email" class="form-control" name="email" required placeholder="example: budi@gmail.com">
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold small">Password</label>
                <input type="password" class="form-control" name="password" required placeholder="enter password">
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Masuk</button>
        </form>

        <div class="text-center mt-4 small text-muted">
            Don't have an account? <br>
            <a href="/register" class="text-decoration-none fw-bold">click here for register</a>
        </div>
    </div>

    <!-- Script Ganti Tema -->
    <script>
        const themeToggleBtn = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
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
            } else {
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
            }
        }
    </script>
</body>
</html>