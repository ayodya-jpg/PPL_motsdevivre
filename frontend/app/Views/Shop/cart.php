<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    
    <!-- CSS Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body { transition: background-color 0.3s, color 0.3s; }
        
        /* --- STYLE HEADER (Sama dengan Index/About) --- */
        .nav-custom .nav-link { font-size: 0.9rem; display: flex; flex-direction: column; align-items: center; padding: 0.5rem 1rem; opacity: 0.7; }
        .nav-custom .nav-link:hover, .nav-custom .nav-link.active { opacity: 1; }
        .nav-custom .nav-link i { font-size: 1.2rem; margin-bottom: 4px; }
        
        .search-input { border: 1px solid rgba(255,255,255,0.2); }
        [data-bs-theme="light"] .search-input { border: 1px solid #ced4da; background-color: #fff; color: #212529; }
        [data-bs-theme="dark"] .search-input { background-color: #2b3035; color: #fff; border-color: #495057; }
        
        /* --- STYLE KHUSUS CART --- */
        .cart-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid rgba(128,128,128,0.2);
        }
        .table-custom th { background-color: rgba(13, 110, 253, 0.1); }
        
        /* Agar align middle semua isi tabel */
        .table > :not(caption) > * > * { vertical-align: middle; }
    </style>
</head>
<body>

    <!-- HEADER (Konsisten) -->
    <header class="mb-4 shadow-sm bg-body-tertiary">
        <div class="header-top py-2 border-bottom border-secondary border-opacity-25">
            <div class="container d-flex justify-content-between align-items-center">
                <a class="navbar-brand fw-bold d-flex align-items-center text-decoration-none fs-4" href="/shop">
                    <span class="bg-warning text-white rounded px-2 me-2 fs-5 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">VR</span> 
                    <span class="text-body">Mots De Vivre</span>
                </a>
                <div class="d-flex align-items-center gap-3">
                    <nav class="nav nav-custom d-none d-md-flex">
                        <a class="nav-link text-body" href="/shop"> <i class="bi bi-house"></i> Home</a>
                        <?php if (session()->get('is_logged_in')) : ?>
                            <!-- Cart Aktif -->
                            <a class="nav-link active text-warning fw-bold position-relative" href="/cart">
                                 <i class="bi bi-bag"></i></i> Cart
                                <?php 
                                    $cart_count = 0;
                                    if(session()->get('cart')) $cart_count = count(session()->get('cart'));
                                ?>
                                <?php if($cart_count > 0): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;"><?= $cart_count ?></span>
                                <?php endif; ?>
                            </a>
                            <a class="nav-link text-body" href="/orders"><i class="bi bi-receipt"></i> Orders</a>
                            <a class="nav-link text-body" href="/profile"><i class="bi bi-person-fill"></i> Profile</a>
                        <?php endif; ?>
                    </nav>

                    <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" id="themeToggle">
                        <i class="fas fa-sun" id="themeIcon"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- KONTEN KERANJANG -->
    <div class="container pb-5">
        <h2 class="mb-4 fw-bold border-bottom pb-2"><i class="bi bi-cart3 me-2"></i> Keranjang Belanja</h2>

        <?php if(empty($cart)): ?>
            <!-- Tampilan Jika Keranjang Kosong -->
            <div class="card border-0 shadow-sm text-center py-5">
                <div class="card-body">
                    <i class="bi bi-cart-x text-muted" style="font-size: 5rem;"></i>
                    <h3 class="mt-3">Keranjang Anda Kosong</h3>
                    <p class="text-muted">Sepertinya Anda belum memilih produk apapun.</p>
                    <a href="/shop" class="btn btn-primary px-4 mt-2">
                        <i class="fas fa-arrow-left me-2"></i> Mulai Belanja
                    </a>
                </div>
            </div>
        <?php else: ?>
            <!-- Tabel Keranjang -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-custom mb-0">
                            <thead class="bg-gray text-white">
                                <tr>
                                    <th class="ps-4">Produk</th>
                                    <th>Harga</th>
                                    <th class="text-center">Qty</th>
                                    <th>Subtotal</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($cart as $id => $item): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <!-- Gambar Produk -->
                                            <?php if(!empty($item['gambar'])): ?>
                                                <img src="http://localhost:8000/storage/<?= $item['gambar'] ?>" class="cart-img me-3" alt="Produk">
                                            <?php else: ?>
                                                <div class="cart-img me-3 bg-secondary d-flex align-items-center justify-content-center text-white">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div>
                                                <h6 class="mb-0 fw-bold"><?= esc($item['nama']) ?></h6>
                                                <!-- <small class="text-muted">ID: <?= $item['id'] ?></small> -->
                                            </div>
                                        </div>
                                    </td>
                                    <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary px-3 py-2"><?= $item['qty'] ?></span>
                                    </td>
                                    <td class="fw-bold text-body">
                                        Rp <?= number_format($item['harga'] * $item['qty'], 0, ',', '.') ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="/shop/remove/<?= $id ?>" class="btn btn-outline-danger btn-sm rounded-circle" onclick="return confirm('Hapus produk ini?')" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="bg-secondary">
                                <tr>
                                    <td colspan="3" class="text-end py-3"><strong>Total Pembayaran:</strong></td>
                                    <td colspan="2" class="py-3">
                                        <h4 class="mb-0 fw-bold text-warning">Rp <?= number_format($total, 0, ',', '.') ?></h4>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                
                <!-- Tombol Aksi Bawah -->
                <div class="card-footer bg-transparent py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <a href="/shop" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Lanjut Belanja
                            </a>
                            <a href="/shop/clear" class="btn btn-outline-danger ms-2" onclick="return confirm('Yakin ingin mengosongkan keranjang?')">
                                <i class="fas fa-trash me-2"></i> Kosongkan
                            </a>
                        </div>
                        
                        <a href="/checkout" class="btn btn-success btn-lg px-5 fw-bold shadow-sm">
                            Checkout <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Script Tema (Sama dengan Index) -->
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