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

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="Mid-client-K-0can14kqPvOh0f"></script>

    <style>
        :root { --primary-slate: #0f172a; --accent-amber: #fbbf24; --bg-light: #f8fafc; --card-shadow: 0 10px 30px -12px rgba(0, 0, 0, 0.08); }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--bg-light); color: var(--primary-slate); transition: all 0.3s ease; }
        .double-header { background: rgba(255, 255, 255, 0.8) !important; backdrop-filter: blur(10px); border-bottom: 1px solid rgba(0,0,0,0.05); }
        .brand-logo { background: var(--primary-slate); color: var(--accent-amber) !important; width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 800; }
        .nav-custom .nav-link { font-weight: 600; color: #64748b !important; padding: 0.5rem 1.2rem; border-radius: 12px; }
        .nav-custom .nav-link.active { color: var(--primary-slate) !important; background: rgba(0,0,0,0.03); }
        .nav-pills .nav-link { border-radius: 14px; color: #64748b; font-weight: 700; padding: 12px 24px; transition: all 0.3s; }
        .nav-pills .nav-link.active { background-color: var(--primary-slate) !important; color: white !important; box-shadow: 0 10px 20px rgba(15, 23, 42, 0.2); }
        .order-card { border: none; border-radius: 28px; background: #ffffff; box-shadow: var(--card-shadow); margin-bottom: 24px; overflow: hidden; transition: transform 0.3s; }
        .order-card:hover { transform: translateY(-5px); }
        .order-header { padding: 20px 25px; background: #fcfcfd; border-bottom: 1px solid #f1f5f9; }
        .product-thumb { width: 64px; height: 64px; object-fit: cover; border-radius: 14px; background: #f1f5f9; }
        .badge-status { padding: 8px 16px; border-radius: 12px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }
        .shipping-info-box { background: #f8fafc; border-radius: 18px; padding: 15px 20px; border: 1px solid #f1f5f9; }
        .theme-toggle-btn { background: #f1f5f9; border: none; padding: 10px 18px; border-radius: 14px; }
        [data-bs-theme="dark"] { --bg-light: #0f172a; --primary-slate: #f8fafc; }
        [data-bs-theme="dark"] .order-card { background: #1e293b; }
        [data-bs-theme="dark"] .order-header { background: #1e293b; border-color: #334155; }
        [data-bs-theme="dark"] .shipping-info-box { background: #0f172a; border-color: #334155; }
        [data-bs-theme="dark"] .double-header { background: rgba(15, 23, 42, 0.9) !important; }
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
                        <a class="nav-link active" href="/orders">Orders</a>
                        <a class="nav-link" href="/profile">Profile</a>
                    </nav>
                    <button class="theme-toggle-btn" id="themeToggle"><i class="fas fa-sun" id="themeIcon"></i></button>
                </div>
            </div>
        </div>
    </header>

    <div class="container pb-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-5">
            <div>
                <h2 class="fw-800 m-0">Riwayat Pesanan</h2>
                <p class="text-muted small mb-0">Pantau status pengiriman pesanan Anda.</p>
            </div>
            
            <ul class="nav nav-pills bg-white p-2 rounded-4 shadow-sm" id="orderTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#ongoing">📦 Berjalan</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#completed">✅ Selesai</button>
                </li>
            </ul>
        </div>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="ongoing">
                <div class="text-center py-5"><div class="spinner-border text-warning" role="status"></div><p class="mt-2 text-muted small">Memuat pesanan...</p></div>
            </div>
            <div class="tab-pane fade" id="completed">
                <div class="text-center py-5 text-muted">Belum ada riwayat selesai</div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // --- 1. SETUP TEMA ---
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

        // --- 2. LOGIKA UTAMA FETCH ORDER ---
        const API_BASE_URL = 'http://localhost:8090/api'; // Pastikan PORT sesuai Docker Backend
        const userId = <?= session()->get('user_id') ?? 0 ?>;

        async function loadOrders() {
        if (!userId) {
            document.getElementById('ongoing').innerHTML = `<div class="text-center py-5 text-muted">Silakan login terlebih dahulu.</div>`;
            return;
        }

        try {
            // 1. Ambil Data Awal
            let response = await fetch(`${API_BASE_URL}/orders/history?user_id=${userId}`);
            if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);
            let result = await response.json();

            if (result.success && result.data.length > 0) {
                
                // Cek apakah ada order yang statusnya masih 'unpaid'
                const hasUnpaidOrders = result.data.some(o => o.status === 'unpaid' && o.transaction_id);

                if (hasUnpaidOrders) {
                    // Tampilkan indikator loading kecil agar user tahu sedang cek status
                    // (Opsional, tapi bagus untuk UX)
                    console.log("Mengecek status pembayaran ke Midtrans...");
                    
                    // 2. Lakukan Sinkronisasi (Update Database Backend)
                    await syncUnpaidOrders(result.data);

                    // 3. ⚠️ PENTING: Ambil Data Ulang (Re-Fetch) setelah Database diupdate
                    // Agar status 'pending' (Dikemas) yang baru muncul di layar
                    response = await fetch(`${API_BASE_URL}/orders/history?user_id=${userId}`);
                    result = await response.json();
                }

                // 4. Render Data Terbaru
                renderOrders(result.data);
            } else {
                renderEmptyState();
            }
        } catch (error) {
            console.error('Error:', error);
            document.getElementById('ongoing').innerHTML = `
                <div class="text-center py-5 text-danger">
                    <i class="bi bi-wifi-off fs-1 mb-2"></i><br>
                    Gagal memuat data.<br><small>Pastikan Backend aktif & CORS diizinkan.</small>
                </div>`;
        }
    }

        // Fungsi Sync: Trigger backend untuk cek Midtrans jika status masih unpaid
        async function syncUnpaidOrders(orders) {
            const unpaidOrders = orders.filter(o => o.status === 'unpaid' && o.transaction_id);
            
            // Loop parallel requests untuk efisiensi
            const syncPromises = unpaidOrders.map(order => 
                fetch(`${API_BASE_URL}/orders/${order.id}/payment-status`).catch(e => console.log(e))
            );
            
            if (syncPromises.length > 0) {
                await Promise.all(syncPromises);
                // Kita tidak perlu menunggu hasil fetch, cukup trigger saja agar backend update DB
            }
        }

        function renderOrders(orders) {
            const ongoingTab = document.getElementById('ongoing');
            const completedTab = document.getElementById('completed');
            let ongoingHTML = '';
            let completedHTML = '';

            orders.forEach(order => {
                const status = order.status.toLowerCase();
                const cardHTML = createOrderCard(order);

                // --- LOGIKA PEMBAGIAN TAB ---
                // BERJALAN: Unpaid (Belum Bayar), Pending (Dikemas), Dikirim
                // Note: settlement/capture dianggap sama dengan pending (dikemas)
                if (['unpaid', 'pending', 'dikirim', 'settlement', 'capture'].includes(status)) {
                    ongoingHTML += cardHTML;
                } 
                // SELESAI: Selesai, Batal, Expire, Deny
                else {
                    completedHTML += cardHTML;
                }
            });

            ongoingTab.innerHTML = ongoingHTML || getEmptyStateHTML('Belum ada pesanan aktif');
            completedTab.innerHTML = completedHTML || getEmptyStateHTML('Belum ada riwayat selesai');
        }

        function createOrderCard(order) {
            const date = new Date(order.created_at);
            const formattedDate = date.toLocaleDateString('id-ID', {
                day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'
            });

            // --- MAPPING STATUS & WARNA ---
            let badgeClass = 'bg-secondary';
            let statusLabel = order.status.toUpperCase();
            let showPayButton = false;

            switch(order.status.toLowerCase()) {
                case 'unpaid': 
                    badgeClass = 'bg-danger text-white'; 
                    statusLabel = 'MENUNGGU PEMBAYARAN';
                    showPayButton = true;
                    break;
                case 'pending': 
                case 'settlement':
                case 'capture':
                    badgeClass = 'bg-warning text-dark'; 
                    statusLabel = 'SEDANG DIKEMAS'; // Status setelah lunas
                    break;
                case 'dikirim': 
                    badgeClass = 'bg-primary text-white'; 
                    statusLabel = 'SEDANG DIKIRIM';
                    break;
                case 'selesai': 
                    badgeClass = 'bg-success text-white'; 
                    statusLabel = 'SELESAI';
                    break;
                case 'batal': 
                case 'expire':
                case 'deny':
                    badgeClass = 'bg-secondary text-white'; 
                    statusLabel = 'DIBATALKAN';
                    break;
            }

            // Render Produk
            let productsHTML = '';
            if(order.details) {
                order.details.forEach(detail => {
                    const img = detail.product && detail.product.gambar 
                        ? `http://localhost:8090/storage/${detail.product.gambar}` 
                        : 'https://via.placeholder.com/64';
                    
                    productsHTML += `
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <img src="${img}" class="product-thumb" style="width:50px; height:50px; object-fit:cover; border-radius:8px;">
                            <div class="flex-grow-1">
                                <p class="fw-bold mb-0 small">${detail.product ? detail.product.nama_produk : 'Produk'}</p>
                                <small class="text-muted">${detail.jumlah} x Rp ${new Intl.NumberFormat('id-ID').format(detail.harga_saat_ini)}</small>
                            </div>
                        </div>`;
                });
            }

            // Render Tombol Bayar
            const payBtn = (showPayButton && order.snap_token) 
                ? `<button onclick="continuePayment('${order.snap_token}')" class="btn btn-danger btn-sm fw-bold w-100 mt-2 shadow-sm">BAYAR SEKARANG</button>` 
                : '';

            return `
                <div class="order-card mb-3 border rounded-4 overflow-hidden bg-white shadow-sm">
                    <div class="order-header px-4 py-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-bold mb-0">Order #${order.transaction_id || order.id}</h6>
                            <small class="text-muted">${formattedDate} WIB</small>
                        </div>
                        <span class="badge ${badgeClass} rounded-pill px-3 py-2 fw-bold" style="font-size:0.75rem; letter-spacing:0.5px;">${statusLabel}</span>
                    </div>
                    <div class="p-4">
                        ${productsHTML}
                        <hr class="my-3 opacity-10">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small text-muted fw-bold">TOTAL BELANJA</span>
                            <h5 class="fw-black text-primary mb-0">Rp ${new Intl.NumberFormat('id-ID').format(order.total_harga)}</h5>
                        </div>
                        ${payBtn}
                    </div>
                </div>
            `;
        }

        function getEmptyStateHTML(msg) {
            return `<div class="text-center py-5 opacity-50"><i class="bi bi-inbox fs-1 d-block mb-3"></i><h5>${msg}</h5></div>`;
        }

        function renderEmptyState() {
            document.getElementById('ongoing').innerHTML = getEmptyStateHTML('Belum ada pesanan aktif');
            document.getElementById('completed').innerHTML = getEmptyStateHTML('Belum ada riwayat selesai');
        }

        function continuePayment(token) {
            console.log("Membuka Snap Token:", token);
            if (typeof window.snap === 'undefined') {
                alert('Sistem pembayaran gagal dimuat. Coba refresh halaman.');
                return;
            }
            window.snap.pay(token, {
                onSuccess: function(result){ location.reload(); },
                onPending: function(result){ location.reload(); },
                onError: function(result){ location.reload(); },
                onClose: function(){ /* User menutup popup, tidak perlu aksi */ }
            });
        }

        // Load data saat halaman dibuka
        loadOrders();
    </script>
</body>
</html>