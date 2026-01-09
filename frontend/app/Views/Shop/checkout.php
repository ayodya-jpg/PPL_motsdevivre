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

        .double-header {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .brand-logo {
            background: #10b981;
            color: white !important;
            width: 36px; height: 36px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }

        .checkout-card {
            border: none;
            border-radius: 24px;
            background: #ffffff;
            box-shadow: var(--card-shadow);
            margin-bottom: 24px;
        }

        .card-header-custom {
            padding: 20px 24px;
            border-bottom: 1px solid #f1f5f9;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .form-control, .form-select {
            border-radius: 14px;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .summary-card {
            background: var(--primary-slate);
            color: white;
            border-radius: 30px;
            padding: 32px;
            position: sticky;
            top: 100px;
        }

        .btn-pay {
            background: var(--accent-amber);
            color: var(--primary-slate);
            border: none;
            border-radius: 16px;
            padding: 16px;
            font-weight: 800;
            width: 100%;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .btn-pay:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(251, 191, 36, 0.4);
            background: #f59e0b;
        }

        .estimation-box {
            background: rgba(16, 185, 129, 0.1);
            border: 1px dashed #10b981;
            border-radius: 14px;
            display: none;
        }

        .promo-card-item {
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid transparent !important;
        }
        .promo-card-item:hover { border-color: var(--accent-amber) !important; }
        .voucher-circle { width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }

        [data-bs-theme="dark"] {
            --bg-light: #0f172a;
            --primary-slate: #f8fafc;
        }
        [data-bs-theme="dark"] .checkout-card { background: #1e293b; }
        [data-bs-theme="dark"] .summary-card { background: #1e293b; border: 1px solid #334155; }
    </style>
</head>
<body>

    <header class="double-header sticky-top mb-5">
        <div class="header-top py-3">
            <div class="container d-flex justify-content-between align-items-center">
                <a class="navbar-brand fw-bolder d-flex align-items-center text-decoration-none fs-4" href="/shop">
                    <div class="brand-logo me-2"><i class="bi bi-shield-lock-fill"></i></div> 
                    <span class="text-body tracking-tighter">Checkout Aman</span>
                </a>
                <div class="d-flex align-items-center gap-3">
                    <span class="d-none d-md-inline text-muted small"><i class="bi bi-lock me-1"></i> Enkripsi SSL 256-bit</span>
                    <button class="theme-toggle-btn btn btn-light rounded-pill border-0 shadow-sm" id="themeToggle">
                        <i class="fas fa-sun" id="themeIcon"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div class="container pb-5">
        <div class="row g-5">
            
            <div class="col-lg-7">
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <form id="checkoutForm">
                    <div class="checkout-card">
                        <div class="card-header-custom"><i class="bi bi-geo-alt-fill me-2 text-danger"></i> Alamat Pengiriman</div>
                        <div class="card-body p-4">
                            <?php if($address): ?>
                                <div class="p-3 border rounded-4 bg-light mb-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="fw-bold mb-1"><?= esc($address->nama_penerima) ?></h6>
                                            <p class="small text-muted mb-0"><?= esc($address->alamat_lengkap) ?></p>
                                            <p class="small text-muted font-monospace"><?= esc($address->kota) ?></p>
                                        </div>
                                        <span class="badge bg-dark rounded-pill">Utama</span>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4 bg-light rounded-4">
                                    <p class="text-muted small">Belum ada alamat yang tersimpan.</p>
                                    <a href="/profile" class="btn btn-dark btn-sm rounded-pill px-4">Tambah Alamat</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="checkout-card">
                        <div class="card-header-custom"><i class="bi bi-truck me-2 text-primary"></i> Pilihan Pengiriman</div>
                        <div class="card-body p-4">
                            <label class="form-label small fw-bold text-muted mb-3">PILIH METODE KURIR</label>
                            <select id="shippingMethod" class="form-select mb-3">
                                <option value="0" data-cost="0" data-estimasi="-">-- Pilih Kurir --</option>
                                <option value="JNE Reguler|20000" data-cost="20000" data-estimasi="3-4 Hari">JNE Reguler - Rp 20.000</option>
                                <option value="J&T Express|25000" data-cost="25000" data-estimasi="2-3 Hari">J&T Express - Rp 25.000</option>
                                <option value="SiCepat Halu|18000" data-cost="18000" data-estimasi="3-5 Hari">SiCepat Halu - Rp 18.000</option>
                                <option value="Instant GoSend|50000" data-cost="50000" data-estimasi="Hari Ini">Instant GoSend - Rp 50.000</option>
                            </select>

                            <div id="estimationDisplay" class="estimation-box p-3 align-items-center gap-3">
                                <div class="bg-white rounded-circle p-2 text-success shadow-sm">
                                    <i class="bi bi-lightning-charge-fill"></i>
                                </div>
                                <div>
                                    <small class="d-block text-muted uppercase font-bold" style="font-size: 10px;">ESTIMASI TIBA</small>
                                    <strong id="estimasiText" class="text-success font-black">-</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="/cart" class="btn btn-link text-decoration-none text-muted fw-bold"><i class="bi bi-arrow-left"></i> Kembali ke Keranjang</a>
                </form>
            </div>

            <div class="col-lg-5">
                <div class="summary-card shadow-lg">
                    <h5 class="fw-bold mb-4">Ringkasan Pesanan</h5>
                    
                    <div class="order-items mb-4" style="max-height: 250px; overflow-y: auto;">
                        <?php foreach($cart as $item): ?>
                            <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom border-white border-opacity-10">
                                <div class="bg-white bg-opacity-10 rounded-3 p-2">
                                    <i class="fas fa-egg text-amber" style="color:var(--accent-amber)"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="small fw-bold mb-0 text-white"><?= esc($item['nama']) ?></h6>
                                    <small class="opacity-50">x<?= $item['qty'] ?></small>
                                </div>
                                <span class="small fw-bold">Rp <?= number_format($item['harga'] * $item['qty'], 0, ',', '.') ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mb-4">
                        <label class="small fw-bold text-white text-opacity-50 d-block mb-2">VOUCHER SAYA</label>
                        <button type="button" class="btn btn-outline-light w-100 rounded-4 py-3 text-start d-flex justify-content-between align-items-center border-white border-opacity-20" data-bs-toggle="modal" data-bs-target="#promoModal">
                            <span id="selectedPromoText"><i class="bi bi-ticket-perforated me-2"></i> Gunakan Promo</span>
                            <i class="bi bi-chevron-right small"></i>
                        </button>
                    </div>

                    <div class="pricing-details">
                        <div class="d-flex justify-content-between mb-2 small opacity-75">
                            <span>Subtotal Produk</span>
                            <span>Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
                        </div>
                        
                        <div id="promoProductRow" class="justify-content-between mb-2 small text-warning d-none">
                            <span>Diskon Produk</span>
                            <span id="discProductDisplay">-Rp 0</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2 small opacity-75">
                            <span>Ongkos Kirim</span>
                            <span id="shippingDisplay">Rp 0</span>
                        </div>

                        <div id="promoShippingRow" class="justify-content-between mb-2 small text-warning d-none">
                            <span>Diskon Ongkir</span>
                            <span id="discShippingDisplay">-Rp 0</span>
                        </div>

                        <hr class="opacity-20 my-4">
                        <div class="d-flex justify-content-between align-items-end mb-4">
                            <span class="opacity-75">Total Tagihan</span>
                            <span class="fs-2 fw-black" style="color: var(--accent-amber);" id="totalDisplay">Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
                        </div>
                    </div>

                    <button type="button" id="payButton" class="btn btn-pay text-uppercase tracking-widest">
                        Bayar Sekarang <i class="bi bi-arrow-right-circle-fill ms-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="promoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-5 overflow-hidden">
                <div class="modal-header bg-dark text-white p-4">
                    <h5 class="modal-title fw-bold">Pilih Voucher</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="alert alert-info small rounded-4 border-0 mb-4">
                        Maksimal 1 Promo Produk + 1 Promo Ongkir.
                    </div>
                    <div id="promoList">
                    <?php if(!empty($user_promos)): ?>
                        <div><small class="fw-bold text-primary"><i class="bi bi-gift-fill me-1"></i> VOUCHER KAMU</small>
                        <?php foreach($user_promos as $p): ?>
                            <div class="card border-0 rounded-4 shadow-sm mb-3 promo-card-item">
                                <div class="card-body d-flex align-items-center gap-3">
                                    <div class="voucher-circle bg-<?= $p->promo_type == 'product' ? 'warning' : 'primary' ?> text-white">
                                        <i class="bi bi-<?= $p->promo_type == 'product' ? 'tag-fill' : 'truck' ?>"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-0"><?= $p->promo_code ?></h6>
                                        <small class="text-muted">Potongan <?= $p->discount_percent ?>%</small>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input promo-checkbox" 
                                               type="checkbox" 
                                               data-code="<?= $p->promo_code ?>"
                                               data-type="<?= $p->promo_type ?>" 
                                               data-value="<?= $p->discount_percent ?>" 
                                               data-valuetype="percentage"
                                               data-source="local"
                                               data-min="0">
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4 text-muted small">Memuat promo...</div>
                    <?php endif; ?>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-dark w-100 py-3 rounded-4 fw-bold" data-bs-dismiss="modal">Terapkan Promo</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // --- 1. INISIALISASI VARIABEL ---
    const subtotal = <?= $subtotal ?>; 
    const userId = <?= session()->get('user_id') ?? 0 ?>;
    
    // ID Element
    const shippingSelect = document.getElementById('shippingMethod');
    const shippingDisplay = document.getElementById('shippingDisplay');
    const totalDisplay = document.getElementById('totalDisplay');
    const promoListContainer = document.getElementById('promoList');

    // State Pilihan Promo
    let selectedPromos = {
        product: null,   // Slot Promo Produk
        shipping: null   // Slot Promo Ongkir
    };

    // --- 2. LOAD PROMO DARI API LARAVEL ---
    async function loadAllPromos() {
        if (!userId) {
            promoListContainer.innerHTML = `<div class="text-center py-4 text-muted small">Silakan login untuk melihat promo.</div>`;
            return;
        }

        try {
            // Fetch API Promo Premium
            const response = await fetch(`http://localhost:8000/api/promo-codes/available?user_id=${userId}`);
            const result = await response.json();
            
            let htmlPromos = '';

            // Render Promo Premium (API)
            if (result.success && result.data.length > 0) {
                htmlPromos += `<div class="mb-3"><small class="fw-bold text-warning"><i class="bi bi-crown-fill me-1"></i> PREMIUM</small>`;
                result.data.forEach(promo => {
                    htmlPromos += renderPromoCard(promo.code, 'product', promo.value, promo.type, 'api', promo.min_purchase);
                });
                htmlPromos += `</div>`;
            }

            // Render Promo Lokal (yang sudah ada dari PHP)
            // Kita ambil HTML yg sudah dirender PHP di dalam div #promoList
            // Lalu kita gabungkan dengan hasil API
            const existingLocalHtml = promoListContainer.innerHTML.trim();
            let finalHtml = htmlPromos;

            // Jika PHP sudah merender promo lokal (dan bukan text loading), kita pertahankan
            if (existingLocalHtml && !existingLocalHtml.includes('Memuat promo')) {
                finalHtml += existingLocalHtml; 
            } else if (existingLocalHtml.includes('Memuat promo') && htmlPromos === '') {
                finalHtml = `<div class="text-center py-4 text-muted small">Belum ada promo tersedia.</div>`;
            }

            // Update DOM jika ada perubahan
            if (htmlPromos !== '') {
                promoListContainer.innerHTML = finalHtml;
            } else if (existingLocalHtml.includes('Memuat promo')) {
                 // Kalau API kosong dan PHP kosong -> Tampilkan kosong
                 promoListContainer.innerHTML = `<div class="text-center py-4 text-muted small">Belum ada promo tersedia.</div>`;
            }

        } catch (error) {
            console.error("Gagal load API:", error);
            // Biarkan fallback PHP bekerja
            const existingLocalHtml = promoListContainer.innerHTML.trim();
            if (existingLocalHtml.includes('Memuat promo')) {
                 promoListContainer.innerHTML = `<div class="text-center py-4 text-muted small">Gagal memuat promo tambahan.</div>`;
            }
        }
    }

    // Helper: Buat HTML Card Promo (Khusus API)
    function renderPromoCard(code, type, value, valueType, source, minOrder) {
        const color = 'warning'; 
        const icon = 'tag-fill';
        const valDisplay = valueType === 'percentage' ? value + '%' : 'Rp ' + formatRupiah(value);

        return `
            <div class="card border-0 rounded-4 shadow-sm mb-2 promo-card-item">
                <div class="card-body d-flex align-items-center gap-3 py-2">
                    <div class="voucher-circle bg-${color} text-white"><i class="bi bi-${icon}"></i></div>
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-0 small">${code}</h6>
                        <small class="text-muted" style="font-size: 11px;">Hemat ${valDisplay}</small>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input promo-checkbox" type="checkbox" 
                            data-code="${code}" 
                            data-type="${type}" 
                            data-value="${value}" 
                            data-valuetype="${valueType}"
                            data-source="${source}"
                            data-min="${minOrder}">
                    </div>
                </div>
            </div>`;
    }

    // --- 3. EVENT DELEGATION (MAIN LOGIC) ---
    // Pasang listener di container utama, supaya elemen dinamis tetap kena
    promoListContainer.addEventListener('change', function(e) {
        
        // Cek apakah targetnya adalah checkbox promo
        if (e.target && e.target.classList.contains('promo-checkbox')) {
            const checkbox = e.target;
            const type = checkbox.getAttribute('data-type'); // 'product' atau 'shipping'
            
            if (checkbox.checked) {
                // 1. Matikan checkbox lain yang tipenya sama (agar max 1 per tipe)
                const allCheckboxes = document.querySelectorAll(`.promo-checkbox[data-type="${type}"]`);
                allCheckboxes.forEach(other => {
                    if (other !== checkbox) {
                        other.checked = false;
                    }
                });

                // 2. Simpan ke state
                selectedPromos[type] = {
                    code: checkbox.getAttribute('data-code'),
                    value: parseFloat(checkbox.getAttribute('data-value')),
                    valueType: checkbox.getAttribute('data-valuetype') || 'percentage',
                    source: checkbox.getAttribute('data-source')
                };
            } else {
                // 3. Jika di-uncheck, hapus dari state
                selectedPromos[type] = null;
            }

            // 4. Hitung ulang & Update UI
            calculateTotal();
            updateSelectedPromoUI();
        }
    });

    // --- 4. PERHITUNGAN TOTAL ---
    function calculateTotal() {
        // Ambil ongkir dari dropdown
        const selectedOption = shippingSelect.options[shippingSelect.selectedIndex];
        const shipCost = parseInt(selectedOption.getAttribute('data-cost')) || 0;
        
        let discountProduct = 0;
        let discountShipping = 0;

        // Hitung Diskon Produk
        if (selectedPromos.product) {
            const p = selectedPromos.product;
            if (p.valueType === 'percentage') {
                discountProduct = subtotal * (p.value / 100);
            } else {
                discountProduct = p.value; // Nominal fixed
            }
        }

        // Hitung Diskon Ongkir
        if (selectedPromos.shipping) {
            const s = selectedPromos.shipping;
            if (s.valueType === 'percentage') {
                discountShipping = shipCost * (s.value / 100);
            } else {
                discountShipping = s.value;
            }
            // Validasi: Diskon ongkir tak boleh lebih dari ongkirnya
            if (discountShipping > shipCost) discountShipping = shipCost;
        }

        // Update Tampilan Baris Diskon
        updateRowUI('promoProductRow', 'discProductDisplay', discountProduct);
        updateRowUI('promoShippingRow', 'discShippingDisplay', discountShipping);

        // Update Tampilan Ongkir & Total
        document.getElementById('shippingDisplay').textContent = `Rp ${formatRupiah(shipCost)}`;
        
        let grandTotal = (subtotal - discountProduct) + (shipCost - discountShipping);
        if (grandTotal < 0) grandTotal = 0;

        document.getElementById('totalDisplay').textContent = `Rp ${formatRupiah(grandTotal)}`;
    }

    // --- 5. FUNGSI UPDATE UI ---
    function updateRowUI(rowId, spanId, amount) {
        const row = document.getElementById(rowId);
        if (amount > 0) {
            row.classList.remove('d-none');
            row.classList.add('d-flex');
            document.getElementById(spanId).textContent = `-Rp ${formatRupiah(amount)}`;
        } else {
            row.classList.add('d-none');
            row.classList.remove('d-flex');
        }
    }

    function updateSelectedPromoUI() {
        const codes = [];
        if (selectedPromos.product) codes.push(selectedPromos.product.code);
        if (selectedPromos.shipping) codes.push(selectedPromos.shipping.code);

        const btnText = document.getElementById('selectedPromoText');
        if (codes.length > 0) {
            btnText.innerHTML = `<i class="bi bi-ticket-fill text-warning me-2"></i> ${codes.join(' + ')}`;
            btnText.parentElement.classList.add('border-warning', 'bg-warning', 'bg-opacity-10');
            btnText.classList.add('fw-bold', 'text-dark');
        } else {
            btnText.innerHTML = `<i class="bi bi-ticket-perforated me-2"></i> Gunakan Promo`;
            btnText.parentElement.classList.remove('border-warning', 'bg-warning', 'bg-opacity-10');
            btnText.classList.remove('fw-bold', 'text-dark');
        }
    }

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(Math.round(angka));
    }

    // --- 6. EVENT LISTENER LAIN ---
    
    // Ganti Kurir -> Hitung Ulang Total
    shippingSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const estimasi = selectedOption.getAttribute('data-estimasi');
        
        if (estimasi && estimasi !== '-') {
            document.getElementById('estimasiText').textContent = estimasi;
            document.getElementById('estimationDisplay').style.display = 'flex';
        } else {
            document.getElementById('estimationDisplay').style.display = 'none';
        }
        calculateTotal();
    });

    // Tombol Bayar
    document.getElementById('payButton').addEventListener('click', async function() {
        const shipCost = parseInt(shippingSelect.options[shippingSelect.selectedIndex].getAttribute('data-cost')) || 0;
        
        if (shipCost === 0) { 
            alert('Mohon pilih metode pengiriman terlebih dahulu!'); 
            return; 
        }

        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>MEMPROSES...';

        const totalRaw = document.getElementById('totalDisplay').textContent.replace(/[^\d]/g, '');

        // Kumpulkan Kode Promo untuk dikirim ke Backend
        const codesToSend = [];
        if (selectedPromos.product) codesToSend.push(selectedPromos.product.code);
        if (selectedPromos.shipping) codesToSend.push(selectedPromos.shipping.code);

        const paymentData = {
            total: parseInt(totalRaw),
            promo_codes: codesToSend 
        };

        try {
            const response = await fetch('/api/payment/checkout', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(paymentData)
            });
            
            const data = await response.json();
            
            if (data.success && data.snap_token) {
                window.snap.pay(data.snap_token, {
                    onSuccess: () => window.location.href = '/orders',
                    onPending: () => window.location.href = '/orders',
                    onError: () => { alert('Pembayaran Gagal!'); this.disabled = false; this.innerHTML = 'BAYAR SEKARANG'; },
                    onClose: () => { this.disabled = false; this.innerHTML = 'BAYAR SEKARANG'; }
                });
            } else {
                throw new Error(data.error || 'Gagal membuat transaksi');
            }
        } catch (err) {
            console.error(err);
            alert('Error: ' + err.message);
            this.disabled = false;
            this.innerHTML = 'BAYAR SEKARANG';
        }
    });

    // Theme Toggle
    document.getElementById('themeToggle').addEventListener('click', () => {
        const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        document.documentElement.setAttribute('data-bs-theme', isDark ? 'light' : 'dark');
    });

    // Load Promo Awal
    loadAllPromos();

    </script>

</body>
</html>