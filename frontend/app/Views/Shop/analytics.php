<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Analytics Dashboard') ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary-slate: #0f172a;
            --accent-amber: #fbbf24;
            --bg-light: #f8fafc;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-light);
            color: var(--primary-slate);
        }

        .stats-card {
            border: none;
            border-radius: 24px;
            background: white;
            box-shadow: 0 10px 30px -12px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            transition: all 0.3s;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-slate);
        }

        .stat-label {
            color: #64748b;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .chart-container {
            position: relative;
            height: 350px;
        }

        .brand-logo {
            background: var(--primary-slate);
            color: var(--accent-amber) !important;
            width: 40px; height: 40px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800;
        }

        .nav-custom .nav-link {
            font-weight: 600; 
            color: #64748b !important;
            padding: 0.5rem 1.2rem; 
            border-radius: 12px;
        }

        .nav-custom .nav-link.active {
            color: var(--primary-slate) !important;
            background: rgba(0,0,0,0.03);
        }

        .double-header {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .icon-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .product-rank {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
        }
    </style>
</head>
<body>

    <header class="double-header sticky-top mb-5">
        <div class="header-top py-3">
            <div class="container d-flex justify-content-between align-items-center">
                <a class="navbar-brand fw-bolder d-flex align-items-center text-decoration-none fs-4" href="/shop">
                    <div class="brand-logo me-2">TJ</div> 
                    <span class="text-body">Telur Josjis</span>
                </a>

                <nav class="nav nav-custom d-none d-lg-flex">
                    <a class="nav-link" href="/shop">Home</a>
                    <a class="nav-link" href="/cart">Cart <span class="badge bg-danger rounded-pill"><?= $cart_count ?? 0 ?></span></a>
                    <a class="nav-link" href="/orders">Orders</a>
                    <a class="nav-link active" href="/analytics">Analytics</a>
                    <a class="nav-link" href="/profile">Profile</a>
                </nav>
            </div>
        </div>
    </header>

    <div class="container pb-5">
        
        <!-- Loading State -->
        <div id="loadingState" class="text-center py-5">
            <div class="spinner-border text-warning" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Memuat data analytics...</p>
        </div>

        <!-- Error State (jika bukan member) -->
        <div id="errorState" style="display: none;" class="text-center py-5">
            <img src="https://illustrations.popsy.co/amber/forbidden.svg" style="width: 300px;" class="mb-4">
            <h3 class="fw-bold">Analytics Khusus Member</h3>
            <p class="text-muted">Upgrade ke Membership Pelaku Usaha untuk akses fitur ini.</p>
            <a href="/subscriptions" class="btn btn-warning btn-lg rounded-pill px-5 fw-bold mt-3">
                <i class="bi bi-rocket-takeoff me-2"></i> Upgrade Sekarang
            </a>
        </div>

        <!-- Main Content -->
        <div id="analyticsContent" style="display: none;">
            
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="fw-800 mb-1">📊 Analytics Dashboard</h2>
                    <p class="text-muted mb-0">Pantau performa bisnis telur Anda secara real-time</p>
                </div>
                <span class="badge bg-warning text-dark px-4 py-2 rounded-pill fw-bold">
                    <i class="bi bi-crown-fill me-1"></i> Member Pelaku Usaha
                </span>
            </div>

            <!-- Stats Cards -->
            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <div class="stats-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-label mb-2">Total Orders</div>
                                <div class="stat-value" id="totalOrders">0</div>
                            </div>
                            <div class="icon-wrapper bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-cart-check-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stats-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-label mb-2">Total Revenue</div>
                                <div class="stat-value text-success" id="totalRevenue">Rp 0</div>
                            </div>
                            <div class="icon-wrapper bg-success bg-opacity-10 text-success">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart -->
            <div class="row g-4 mb-5">
                <div class="col-12">
                    <div class="stats-card">
                        <h5 class="fw-bold mb-4">📈 Penjualan 6 Bulan Terakhir</h5>
                        <div class="chart-container">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Products -->
            <div class="row">
                <div class="col-12">
                    <div class="stats-card">
                        <h5 class="fw-bold mb-4">🏆 Top 5 Produk Terlaris</h5>
                        <div id="topProductsList"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const userId = <?= session()->get('user_id') ?>;
    // Sesuaikan Port Backend (8090)
    const API_BASE_URL = 'http://localhost:8090/api'; 

    async function loadAnalytics() {
        try {
            const response = await fetch(`${API_BASE_URL}/membership/analytics/${userId}`);
            
            // Handle jika user belum login atau session habis
            if (response.status === 401) {
                window.location.href = '/auth/login';
                return;
            }

            const result = await response.json();

            document.getElementById('loadingState').style.display = 'none';

            // ✅ CEK STATUS 403 - USER TIDAK PUNYA MEMBERSHIP
            if (response.status === 403 || !result.success) {
                document.getElementById('errorState').style.display = 'block';
                return;
            }

            // ✅ USER PUNYA MEMBERSHIP - SHOW ANALYTICS
            document.getElementById('analyticsContent').style.display = 'block';

            const data = result.data;

            // 1. UPDATE STATS CARD
            document.getElementById('totalOrders').textContent = data.total_orders;
            document.getElementById('totalRevenue').textContent = 'Rp ' + Number(data.total_revenue).toLocaleString('id-ID');

            // 2. RENDER CHART (Mapping Data Backend ke Format ChartJS)
            // Backend mengirim: monthly_orders = [{month: '2024-01', revenue: 50000}, ...]
            const chartLabels = data.monthly_orders.map(item => {
                // Format bulan biar cantik (misal: "2024-01" jadi "Jan 2024")
                const date = new Date(item.month + '-01'); 
                return date.toLocaleDateString('id-ID', { month: 'short', year: 'numeric' });
            });
            const chartData = data.monthly_orders.map(item => item.revenue);

            renderSalesChart({ labels: chartLabels, data: chartData });

            // 3. RENDER TOP PRODUCTS
            // Backend mengirim key: 'nama_produk' & 'revenue'
            // Kita mapping biar sesuai fungsi renderTopProducts
            const mappedProducts = data.top_products.map(p => ({
                name: p.nama_produk,
                total_sold: p.total_sold,
                total_revenue: p.revenue
            }));

            renderTopProducts(mappedProducts);

        } catch (error) {
            console.error('Error:', error);
            document.getElementById('loadingState').style.display = 'none';
            // Tampilkan error state jika gagal fetch (misal server mati)
            document.getElementById('errorState').innerHTML = `
                <div class="text-danger">
                    <i class="bi bi-exclamation-triangle fs-1"></i>
                    <h4 class="mt-2">Gagal Terhubung ke Server</h4>
                    <p>Pastikan backend (Port 8090) aktif.</p>
                </div>
            `;
            document.getElementById('errorState').style.display = 'block';
        }
    }

    function renderSalesChart(chartData) {
        const labels = chartData.labels;
        const revenues = chartData.data;

        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: revenues,
                    borderColor: '#fbbf24', // Warna Amber
                    backgroundColor: 'rgba(251, 191, 36, 0.1)',
                    tension: 0.4, // Garis melengkung
                    fill: true,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#fbbf24',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // Sembunyikan legend default
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9',
                            borderDash: [5, 5]
                        },
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) return 'Rp ' + (value/1000000) + 'jt';
                                if (value >= 1000) return 'Rp ' + (value/1000) + 'rb';
                                return value;
                            },
                            font: { family: "'Plus Jakarta Sans', sans-serif" }
                        },
                        border: { display: false }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: "'Plus Jakarta Sans', sans-serif" } },
                        border: { display: false }
                    }
                }
            }
        });
    }

    function renderTopProducts(products) {
        const container = document.getElementById('topProductsList');
        
        if (!products || products.length === 0) {
            container.innerHTML = '<p class="text-muted text-center py-4">Belum ada data produk terjual.</p>';
            return;
        }

        let html = '<div class="list-group list-group-flush">';
        products.forEach((product, index) => {
            html += `
                <div class="list-group-item border-0 px-0 py-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="product-rank shadow-sm">${index + 1}</div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold text-dark">${product.name}</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted"><i class="bi bi-box-seam me-1"></i> ${product.total_sold} unit terjual</small>
                                <small class="fw-bold text-success">Rp ${Number(product.total_revenue).toLocaleString('id-ID')}</small>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        
        container.innerHTML = html;
    }

    // Load analytics on page load
    loadAnalytics();
</script>

</body>
</html>
