<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    
    <!-- CSS Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Font (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
<<<<<<< Updated upstream
        body { transition: background-color 0.3s, color 0.3s; }
        .search-input { border: 1px solid rgba(255,255,255,0.2); }
        [data-bs-theme="light"] .search-input { border: 1px solid #ced4da; background-color: #fff; color: #212529; }
        [data-bs-theme="dark"] .search-input { background-color: #2b3035; color: #fff; border-color: #495057; }
        .checkout-card { border: 1px solid rgba(255,255,255,0.1); }
        [data-bs-theme="light"] .checkout-card { border: 1px solid rgba(0,0,0,0.1); }
        .cart-thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; }

=======
        body { 
            font-family: 'Inter', sans-serif;
            transition: background-color 0.3s, color 0.3s;
            background-color: var(--bs-tertiary-bg);
        }
>>>>>>> Stashed changes
        
        /* --- HEADER STYLE --- */
        .nav-custom .nav-link { font-size: 0.9rem; display: flex; flex-direction: column; align-items: center; padding: 0.5rem 1rem; opacity: 0.7; transition: 0.2s; }
        .nav-custom .nav-link:hover, .nav-custom .nav-link.active { opacity: 1; transform: translateY(-2px); }
        .nav-custom .nav-link i { font-size: 1.3rem; margin-bottom: 4px; }

        /* --- CHECKOUT CARDS --- */
        .checkout-card {
            background-color: var(--bs-body-bg);
            border: 1px solid var(--bs-border-color-translucent);
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }
        .checkout-header {
            padding: 1.25rem;
            border-bottom: 1px solid var(--bs-border-color-translucent);
            background-color: rgba(var(--bs-tertiary-bg-rgb), 0.5);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .checkout-body { padding: 1.5rem; }

        /* --- FORM ELEMENTS --- */
        .form-select, .form-control {
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            border-color: var(--bs-border-color);
            background-color: var(--bs-body-bg);
        }
        .form-select:focus, .form-control:focus {
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15);
            border-color: #0d6efd;
        }

        /* --- SUMMARY SECTION --- */
        .cart-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
            border-bottom: 1px dashed var(--bs-border-color-translucent);
        }
        .cart-item:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        
        .cart-thumb {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 0.5rem;
            border: 1px solid var(--bs-border-color);
        }

        /* --- ESTIMATION & PROMO BOX --- */
        .estimation-box {
<<<<<<< Updated upstream
            display: none;
            margin-top: 10px;
            border-radius: 4px;

=======
            background-color: rgba(13, 202, 240, 0.1);
            border: 1px solid rgba(13, 202, 240, 0.2);
            color: var(--bs-info-text-emphasis);
            padding: 1rem;
            border-radius: 0.75rem;
            margin-top: 1rem;
            display: none;
            align-items: center;
            gap: 0.75rem;
        }

        .promo-success-box {
            background-color: rgba(25, 135, 84, 0.1);
            border: 1px solid rgba(25, 135, 84, 0.2);
            color: var(--bs-success-text-emphasis);
            padding: 1rem;
            border-radius: 0.75rem;
            margin-top: 1rem;
            display: none;
        }
        
        /* Sticky Summary for Desktop */
        @media (min-width: 992px) {
            .sticky-summary {
                position: sticky;
                top: 100px;
            }
>>>>>>> Stashed changes
        }
    </style>
</head>
<body>

    <!-- HEADER (KONSISTEN) -->
    <header class="mb-5 shadow-sm bg-body-tertiary sticky-top">
        <div class="header-top py-2 border-bottom border-secondary border-opacity-10">
            <div class="container d-flex justify-content-between align-items-center">
                <a class="navbar-brand fw-bold d-flex align-items-center text-decoration-none fs-4" href="/shop">
                    <span class="bg-success text-white rounded-3 px-2 me-2 fs-5 d-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px;"><i class="bi bi-shield-lock-fill"></i></span> 
                    <span class="text-body fw-bold">Checkout Aman</span>
                </a>
                
                <div class="d-flex align-items-center gap-3">
                    <nav class="nav nav-custom d-none d-md-flex">
                        <a class="nav-link text-body" href="/shop"><i class="bi bi-house"></i> Home</a>
                        <a class="nav-link text-body" href="/cart"><i class="bi bi-bag"></i> Cart</a>
                    </nav>
                    <button class="btn btn-outline-secondary btn-sm rounded-circle p-2" style="width: 38px; height: 38px;" id="themeToggle"><i class="fas fa-sun" id="themeIcon"></i></button>
                </div>
            </div>
        </div>
    </header>

    <!-- KONTEN UTAMA -->
    <div class="container pb-5">
        
        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 border-start border-4 border-danger mb-4" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="/checkout/process" method="post" id="checkoutForm">
            <div class="row g-4">
                
                <!-- KOLOM KIRI: FORMULIR -->
                <div class="col-lg-7">
                    
                    <!-- 1. ALAMAT PENGIRIMAN -->
                    <div class="checkout-card">
                        <div class="checkout-header">
                            <div class="bg-danger bg-opacity-10 p-2 rounded-circle text-danger"><i class="bi bi-geo-alt-fill"></i></div>
                            <div>Alamat Pengiriman</div>
                        </div>
                        <div class="checkout-body">
                            <?php if($address): ?>
                                <div class="p-3 bg-body-tertiary rounded-3 border mb-3">
                                    <h6 class="fw-bold mb-1"><?= esc($address->nama_penerima) ?> <span class="fw-normal text-muted">(<?= esc($address->no_hp) ?>)</span></h6>
                                    <p class="mb-0 text-secondary small" style="line-height: 1.5;">
                                        <?= esc($address->alamat_lengkap) ?><br>
                                        <?= esc($address->kota) ?>, <?= esc($address->provinsi) ?> - <?= esc($address->kode_pos) ?>
                                    </p>
                                </div>
                                <input type="hidden" name="address" value="<?= esc($address->alamat_lengkap) ?>">
                                <a href="/profile" class="btn btn-outline-primary btn-sm rounded-pill px-3">Ganti Alamat</a>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <img src="https://cdn-icons-png.flaticon.com/512/3710/3710268.png" width="60" class="mb-3 opacity-50" alt="No Address">
                                    <p class="text-muted small">Anda belum memiliki alamat tersimpan.</p>
                                    <a href="/profile" class="btn btn-primary rounded-pill px-4 fw-bold">Tambah Alamat Baru</a>
                                </div>
                                <input type="hidden" name="address" required>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- 2. METODE PENGIRIMAN -->
                    <div class="checkout-card">
                        <div class="checkout-header">
                            <div class="bg-info bg-opacity-10 p-2 rounded-circle text-info"><i class="bi bi-truck-front-fill"></i></div>
                            <div>Metode Pengiriman</div>
                        </div>
                        <div class="checkout-body">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary text-uppercase">Pilih Kurir</label>
                                <select name="shipping_method" id="shippingMethod" class="form-select form-select-lg" required>
                                    <option value="0" data-cost="0" data-estimasi="-">-- Pilih Pengiriman --</option>
                                    
                                    <option value="JNE Reguler|20000" data-cost="20000" data-estimasi="3-4 Hari">
                                        JNE Reguler (3-4 Hari) - Rp 20.000
                                    </option>
                                    <option value="J&T Express|25000" data-cost="25000" data-estimasi="2-3 Hari">
                                        J&T Express (2-3 Hari) - Rp 25.000
                                    </option>
                                    <option value="SiCepat Halu|18000" data-cost="18000" data-estimasi="3-5 Hari">
                                        SiCepat Halu (3-5 Hari) - Rp 18.000
                                    </option>
                                    <option value="Instant GoSend|50000" data-cost="50000" data-estimasi="Hari Ini">
                                        Instant GoSend (Hari Ini) - Rp 50.000
                                    </option>
                                </select>

                                <input type="hidden" name="shipping_estimation" id="shippingEstimationInput">

                                <div id="estimationDisplay" class="estimation-box">
                                    <i class="bi bi-clock-history fs-4"></i>
                                    <div>
                                        <small class="d-block text-uppercase fw-bold" style="font-size: 0.65rem; opacity: 0.8;">Estimasi Tiba</small>
                                        <strong id="estimasiText" class="fs-6">-</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. KODE PROMO (DROPDOWN) -->
                    <div class="checkout-card">
                        <div class="checkout-header">
                            <div class="bg-success bg-opacity-10 p-2 rounded-circle text-success"><i class="bi bi-ticket-perforated-fill"></i></div>
                            <div>Voucher & Promo</div>
                        </div>
                        <div class="checkout-body">
                            <label class="form-label small fw-bold text-secondary text-uppercase">Gunakan Promo</label>
                            
                            <!-- DROPDOWN PILIH PROMO -->
                            <select class="form-select form-select-lg" id="promoSelect" name="promo_code">
                                <option value="">-- Tidak Pakai Promo --</option>
                                
                                <!-- Cek Variable dari Controller (Database) -->
                                <?php if(!empty($user_promo)): ?>
                                    <option value="<?= esc($user_promo) ?>">
                                        🎁 <?= esc($user_promo) ?> (20% Off + Hemat Ongkir)
                                    </option>
                                <?php endif; ?>
                            </select>

                            <!-- Pesan Sukses -->
                            <div id="promoSuccessBox" class="promo-success-box">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-check-circle-fill fs-5"></i>
                                    <div>
                                        <div class="fw-bold">Promo Diterapkan!</div>
                                        <small class="opacity-75">Diskon 20% Produk + 8% Ongkir</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. PEMBAYARAN -->
                    <div class="checkout-card">
                        <div class="checkout-header">
                            <div class="bg-warning bg-opacity-10 p-2 rounded-circle text-warning"><i class="bi bi-credit-card-fill"></i></div>
                            <div>Metode Pembayaran</div>
                        </div>
                        <div class="checkout-body">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary text-uppercase">Pilih Pembayaran</label>
                                <select name="payment_method" class="form-select form-select-lg" required>
                                    <option value="">-- Pilih Pembayaran --</option>
                                    <option value="Transfer BCA">Transfer Bank BCA (Cek Otomatis)</option>
                                    <option value="Transfer Mandiri">Transfer Bank Mandiri</option>
                                    <option value="GoPay">GoPay / QRIS</option>
                                    <option value="COD">COD (Bayar di Tempat)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <a href="/cart" class="btn btn-link text-decoration-none text-secondary ps-0"><i class="bi bi-arrow-left me-1"></i> Kembali ke Keranjang</a>
                </div>

                <!-- KOLOM KANAN: RINGKASAN -->
                <div class="col-lg-5">
                    <div class="checkout-card sticky-summary">
                        <div class="checkout-header">
                            <div class="bg-primary bg-opacity-10 p-2 rounded-circle text-primary"><i class="bi bi-receipt"></i></div>
                            <div>Ringkasan Pesanan</div>
                        </div>
                        
                        <div class="checkout-body pt-2">
                            <!-- List Item -->
                            <div class="mb-4" style="max-height: 300px; overflow-y: auto;">
                                <?php foreach($cart as $item): ?>
                                <div class="cart-item">
                                    <?php if(!empty($item['gambar'])): ?>
                                        <img src="http://localhost:8000/storage/<?= $item['gambar'] ?>" class="cart-thumb">
                                    <?php else: ?>
                                        <div class="cart-thumb bg-secondary d-flex align-items-center justify-content-center text-white"><i class="bi bi-image"></i></div>
                                    <?php endif; ?>
                                    
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 small fw-bold text-body-emphasis"><?= esc($item['nama']) ?></h6>
                                        <div class="d-flex justify-content-between mt-1">
                                            <small class="text-muted"><?= $item['qty'] ?> x Rp <?= number_format($item['harga'], 0, ',', '.') ?></small>
                                            <small class="fw-bold">Rp <?= number_format($item['harga'] * $item['qty'], 0, ',', '.') ?></small>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Rincian Harga Lengkap -->
                            <div class="bg-body-tertiary p-3 rounded-3 border">
                                <div class="d-flex justify-content-between mb-2 small text-secondary">
                                    <span>Subtotal Produk</span>
                                    <span class="fw-bold text-body">Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2 small text-secondary">
                                    <span>Biaya Pengiriman</span>
                                    <span class="fw-bold text-body" id="shippingDisplay">Rp 0</span>
                                </div>
                                
                                <!-- Baris Diskon -->
                                <div id="discountDetails" style="display: none;" class="border-top border-bottom border-secondary border-opacity-10 py-2 my-2">
                                    <div class="d-flex justify-content-between mb-1 small text-success">
                                        <span><i class="bi bi-tag-fill me-1"></i> Diskon Produk (20%)</span>
                                        <span class="fw-bold" id="productDiscountDisplay">-Rp 0</span>
                                    </div>
                                    <div class="d-flex justify-content-between small text-success">
                                        <span><i class="bi bi-truck me-1"></i> Diskon Ongkir (8%)</span>
                                        <span class="fw-bold" id="shippingDiscountDisplay">-Rp 0</span>
                                    </div>
                                </div>

                                <hr class="border-secondary border-opacity-25 my-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Total Bayar</span>
                                    <span class="fs-4 fw-bold text-primary" id="totalDisplay">Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
                                </div>
                            </div>

                            <!-- Input Hidden untuk Data Promo yang akan dikirim -->
                            <!-- Name harus 'promo_code' agar ditangkap controller -->
                            <!-- Value diisi via JS saat dropdown berubah -->
                            <input type="hidden" name="final_promo_code" id="finalPromoCode"> 

                            <button type="submit" form="checkoutForm" class="btn btn-primary w-100 py-3 mt-4 fw-bold rounded-pill shadow-sm">
                                Bayar Sekarang <i class="bi bi-arrow-right-circle-fill ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- LOGIKA HITUNG HARGA -->
    <script>
        const subtotal = <?= $subtotal ?>;
        
        // Input Elements
        const shippingSelect = document.getElementById('shippingMethod');
        const promoSelect = document.getElementById('promoSelect');
        const shippingEstimationInput = document.getElementById('shippingEstimationInput');
        const finalPromoCodeInput = document.getElementById('finalPromoCode');
        
        // Display Elements
        const shippingDisplay = document.getElementById('shippingDisplay');
        const totalDisplay = document.getElementById('totalDisplay');
        const productDiscountDisplay = document.getElementById('productDiscountDisplay');
        const shippingDiscountDisplay = document.getElementById('shippingDiscountDisplay');
        const discountDetails = document.getElementById('discountDetails');
        const promoSuccessBox = document.getElementById('promoSuccessBox');
        const estimationBox = document.getElementById('estimationDisplay');
        const estimasiText = document.getElementById('estimasiText');

        // Fungsi Kalkulasi Utama
        function calculateTotal() {
            // 1. Ambil Ongkir
            const selectedShip = shippingSelect.options[shippingSelect.selectedIndex];
            let shippingCost = parseInt(selectedShip.getAttribute('data-cost'));
            const estimasiRaw = selectedShip.getAttribute('data-estimasi');
            
            if (isNaN(shippingCost)) shippingCost = 0;
            
            // Update UI Estimasi
            shippingEstimationInput.value = estimasiRaw;
            if (shippingCost === 0) {
                estimationBox.style.display = 'none';
                shippingDisplay.textContent = 'Rp 0';
            } else {
                estimasiText.textContent = estimasiRaw;
                estimationBox.style.display = 'flex';
                shippingDisplay.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(shippingCost);
            }

            // 2. Cek Promo
            const selectedPromo = promoSelect.value;
            let totalProductDiscount = 0;
            let totalShippingDiscount = 0;

            if (selectedPromo === 'NEWUSER20_FREESHIP') {
                // Tampilkan Info
                discountDetails.style.display = 'block';
                promoSuccessBox.style.display = 'block';
                
                // Isi hidden input untuk dikirim ke controller
                finalPromoCodeInput.value = selectedPromo;

                // Hitung Diskon Produk 20%
                totalProductDiscount = subtotal * 0.20;
                
                // Hitung Diskon Ongkir 8% (Jika ada ongkir)
                if (shippingCost > 0) {
                    totalShippingDiscount = shippingCost * 0.08;
                }

                // Update UI Diskon
                productDiscountDisplay.textContent = '-Rp ' + new Intl.NumberFormat('id-ID').format(totalProductDiscount);
                shippingDiscountDisplay.textContent = '-Rp ' + new Intl.NumberFormat('id-ID').format(totalShippingDiscount);
            } else {
                // Reset Promo
                discountDetails.style.display = 'none';
                promoSuccessBox.style.display = 'none';
                finalPromoCodeInput.value = '';
            }

            const totalDiscount = totalProductDiscount + totalShippingDiscount;

            // 3. Hitung Total Akhir
            // Rumus: (Subtotal + Ongkir) - Total Diskon
            const newTotal = (subtotal + shippingCost) - totalDiscount;
            
            // Render Total
            totalDisplay.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(newTotal);
        }

        // Event Listeners
        shippingSelect.addEventListener('change', calculateTotal);
        promoSelect.addEventListener('change', calculateTotal);

        // Jalankan saat load
        calculateTotal();

        // Script Tema
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
            if (theme === 'dark') { themeIcon.classList.remove('fa-moon'); themeIcon.classList.add('fa-sun'); } 
            else { themeIcon.classList.remove('fa-sun'); themeIcon.classList.add('fa-moon'); }
        }
    </script>
</body>
</html>
