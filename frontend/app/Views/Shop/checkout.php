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
        .search-input { border: 1px solid rgba(255,255,255,0.2); }
        [data-bs-theme="light"] .search-input { border: 1px solid #ced4da; background-color: #fff; color: #212529; }
        [data-bs-theme="dark"] .search-input { background-color: #2b3035; color: #fff; border-color: #495057; }
        .checkout-card { border: 1px solid rgba(255,255,255,0.1); }
        [data-bs-theme="light"] .checkout-card { border: 1px solid rgba(0,0,0,0.1); }
        .cart-thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; }

        
        /* Style Estimasi */
        .estimation-box {
            display: none;
            margin-top: 10px;
            border-radius: 4px;

        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <header class="mb-4 shadow-sm bg-body-tertiary">
        <div class="header-top py-2 border-bottom border-secondary border-opacity-25">
            <div class="container d-flex justify-content-between align-items-center">
                <a class="navbar-brand fw-bold d-flex align-items-center text-decoration-none fs-4" href="/shop">
                    <span class="bg-success text-white rounded px-2 me-2 fs-5 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="bi bi-shield-lock"></i></span> 
                    <span class="text-body">Checkout Aman</span>
                </a>
                <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" id="themeToggle">
                    <i class="fas fa-sun" id="themeIcon"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- KONTEN CHECKOUT -->
    <div class="container pb-5">
        <div class="row g-4">
            
            <!-- KOLOM KIRI: FORMULIR -->
            <div class="col-lg-7">
                
                <!-- Pesan Error -->
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <form action="/checkout/process" method="post" id="checkoutForm">
                    
                    <!-- 1. PENGIRIMAN -->
                    <div class="card checkout-card mb-4 shadow-sm">
                        <div class="card-header bg-transparent fw-bold py-3">
                            <i class="bi bi-geo-alt me-2 text-danger"></i> Alamat & Pengiriman
                        </div>
                        <div class="card-body">
                            
                            <!-- Pilih Alamat -->
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Alamat Pengiriman</label>
                                <?php if($address): ?>
                                    <select name="address" class="form-select p-3">
                                        <option value="<?= $address->alamat_lengkap ?>" selected>
                                            <?= esc($address->nama_penerima) ?> | <?= esc($address->kota) ?> (<?= esc($address->alamat_lengkap) ?>)
                                        </option>
                                    </select>
                                <?php else: ?>
                                    <div class="alert alert-warning d-flex align-items-center">
                                        <i class="bi bi-exclamation-circle me-2"></i>
                                        <div>Anda belum memiliki alamat tersimpan.</div>
                                        <a href="/profile" class="btn btn-sm btn-primary ms-auto">Tambah Alamat</a>
                                    </div>
                                    <input type="hidden" name="address" required> 
                                <?php endif; ?>
                            </div>

                            <!-- Pilih Kurir (DENGAN DATA ESTIMASI) -->
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Metode Pengiriman</label>
                                <select name="shipping_method" id="shippingMethod" class="form-select p-3" required>
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

                                <!-- INPUT HIDDEN (PENTING: Ini yang dikirim ke database) -->
                                <input type="hidden" name="shipping_estimation" id="shippingEstimationInput">

                                <!-- Tampilan UI Estimasi -->
                                <div id="estimationDisplay" class="estimation-box alert alert-info d-flex align-items-center py-2 mt-3">
                                    <i class="bi bi-clock-history me-2 fs-5"></i> 
                                    <div>
                                        <small class="d-block text-muted" style="line-height: 1;">Estimasi Tiba</small>
                                        <strong id="estimasiText" class="fs-6">-</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. PEMBAYARAN -->
                    <div class="card checkout-card mb-4 shadow-sm">
                        <div class="card-header bg-transparent fw-bold py-3">
                            <i class="bi bi-credit-card me-2 text-primary"></i> Metode Pembayaran
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <select name="payment_method" class="form-select p-3" required>
                                    <option value="">-- Pilih Pembayaran --</option>
                                    <option value="Transfer BCA">Transfer Bank BCA (Cek Otomatis)</option>
                                    <option value="Transfer Mandiri">Transfer Bank Mandiri</option>
                                    <option value="GoPay">GoPay / QRIS</option>
                                    <option value="COD">COD (Bayar di Tempat)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <a href="/cart" class="btn btn-outline-secondary mb-3"><i class="bi bi-arrow-left"></i> Kembali ke Keranjang</a>
                </form>
            </div>

            <!-- KOLOM KANAN: RINGKASAN PESANAN -->
            <div class="col-lg-5">
                <div class="card checkout-card shadow-sm position-sticky" style="top: 20px;">
                    <div class="card-header bg-transparent fw-bold py-3">
                        Ringkasan Pesanan
                    </div>
                    <div class="card-body p-0">
                        <!-- List Produk -->
                        <ul class="list-group list-group-flush">
                            <?php foreach($cart as $item): ?>
                            <li class="list-group-item bg-transparent d-flex align-items-center gap-3 py-3">
                                <?php if(!empty($item['gambar'])): ?>
                                    <img src="http://localhost:8000/storage/<?= $item['gambar'] ?>" class="cart-thumb">
                                <?php else: ?>
                                    <div class="cart-thumb bg-secondary d-flex align-items-center justify-content-center text-white"><i class="bi bi-image"></i></div>
                                <?php endif; ?>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 small fw-bold"><?= esc($item['nama']) ?></h6>
                                    <small class="text-muted">x<?= $item['qty'] ?></small>
                                </div>
                                <span class="small fw-bold">Rp <?= number_format($item['harga'] * $item['qty'], 0, ',', '.') ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>

                        <!-- Kalkulasi Harga -->
                        <div class="p-3 bg-body-tertiary">
                            <div class="d-flex justify-content-between mb-2 small">
                                <span>Subtotal Produk</span>
                                <span class="fw-bold">Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 small">
                                <span>Biaya Pengiriman</span>
                                <span class="fw-bold text-success" id="shippingDisplay">Rp 0</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold">Total Bayar</span>
                                <span class="fs-4 fw-bold text-primary" id="totalDisplay">Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent p-3">
                        <button type="submit" form="checkoutForm" class="btn btn-success w-100 py-3 fw-bold fs-5 shadow">
                            Bayar Sekarang <i class="bi bi-check-circle-fill ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JAVASCRIPT LOGIC -->
    <script>
        const subtotal = <?= $subtotal ?>;
        const shippingSelect = document.getElementById('shippingMethod');
        const shippingDisplay = document.getElementById('shippingDisplay');
        const totalDisplay = document.getElementById('totalDisplay');
        
        // Variabel untuk Estimasi
        const shippingEstimationInput = document.getElementById('shippingEstimationInput'); // Input Hidden
        const estimasiText = document.getElementById('estimasiText');
        const estimationBox = document.getElementById('estimationDisplay');

        shippingSelect.addEventListener('change', function() {
            // 1. Ambil Data dari Option
            const selectedOption = this.options[this.selectedIndex];
            const shippingCost = parseInt(selectedOption.getAttribute('data-cost'));
            const estimasiRaw = selectedOption.getAttribute('data-estimasi'); // "3-4 Hari"

            // 2. Update Hidden Input (Agar terkirim ke database)
            shippingEstimationInput.value = estimasiRaw;

            // 3. Update Tampilan Estimasi
            if (isNaN(shippingCost) || shippingCost === 0) {
                estimationBox.style.display = 'none';
                shippingDisplay.textContent = 'Rp 0';
                totalDisplay.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
            } else {
                estimasiText.textContent = estimasiRaw;
                estimationBox.style.display = 'flex'; // Tampilkan kotak info
                
                // Update Harga
                shippingDisplay.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(shippingCost);
                const newTotal = subtotal + shippingCost;
                totalDisplay.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(newTotal);
            }
        });

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