<!DOCTYPE html>
<html>
<head>
    <title><?= esc($title ?? 'Semua Pesanan') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
            background-color: #f4f6f9;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        h1 { margin: 0; font-size: 24px; font-weight: bold; color: #333; }
        .card-box {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 40px;
        }
        .table thead th {
            background-color: #0d6efd;
            color: white;
            font-weight: 500;
            border: none;
            padding: 15px;
        }
        .table tbody td {
            padding: 12px 15px;
            vertical-align: middle;
        }
        .refresh-indicator {
            background: #e7f3ff;
            border-left: 4px solid #0d6efd;
            padding: 12px 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .refresh-time {
            font-size: 13px;
            color: #666;
        }
        .auto-refresh-badge {
            background: #28a745;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .auto-refresh-badge.paused {
            background: #dc3545;
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <div class="header">
        <div>
            <h1><i class="fas fa-shopping-cart me-2"></i> Semua Pesanan</h1>
            <p class="text-muted mb-0">Manajemen pesanan masuk</p>
        </div>
        <a href="/admin/dashboard" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard
        </a>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success mb-4"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger mb-4"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <!-- AUTO-REFRESH INDICATOR -->
    <div class="refresh-indicator">
        <div>
            <i class="fas fa-sync-alt me-2"></i>
            <span class="refresh-time" id="refreshTime">Memuat data...</span>
        </div>
        <div>
            <span class="auto-refresh-badge" id="autoRefreshBadge">
                <i class="fas fa-circle me-1" style="font-size: 8px;"></i>
                Auto-Refresh ON
            </span>
            <button onclick="toggleAutoRefresh()" class="btn btn-sm btn-outline-primary ms-2" id="toggleBtn">
                <i class="fas fa-pause"></i> Pause
            </button>
            <button onclick="loadOrders()" class="btn btn-sm btn-primary ms-1">
                <i class="fas fa-sync-alt"></i> Refresh Now
            </button>
        </div>
    </div>

    <!-- TABEL ORDERS -->
    <div class="card card-box">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">ID Order</th>
                        <th>Pelanggan</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody id="orders-table-body">
                    <!-- Loading state -->
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <h5 class="mt-3">Memuat data pesanan...</h5>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // Auto-refresh variables
    let autoRefreshInterval = null;
    let isAutoRefreshActive = true;

    // Function to load orders
    function loadOrders() {
        console.log('Loading orders from API...');
        
        fetch('/admin/orders/json')
            .then(response => {
                console.log('API Response Status:', response.status);
                return response.json();
            })
            .then(orders => {
                console.log('Orders data:', orders);
                
                const tbody = document.getElementById('orders-table-body');
                
                if (!orders || orders.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-box-open fa-3x mb-3"></i>
                                <h5>Belum ada pesanan masuk.</h5>
                            </td>
                        </tr>
                    `;
                    updateRefreshTime();
                    return;
                }

                tbody.innerHTML = '';
                
                orders.forEach(order => {
                    let statusClass = 'secondary';
                    if(order.status == 'pending') statusClass = 'warning text-dark';
                    else if(order.status == 'dikirim') statusClass = 'info';
                    else if(order.status == 'selesai') statusClass = 'success';
                    else if(order.status == 'batal') statusClass = 'danger';

                    const date = new Date(order.created_at);
                    const formattedDate = date.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="ps-4 fw-bold text-primary">#${order.id}</td>
                        <td>
                            <div class="fw-bold">${order.user?.name || 'Guest'}</div>
                            <small class="text-muted">${order.user?.email || '-'}</small>
                        </td>
                        <td>${formattedDate}</td>
                        <td class="fw-bold text-success">
                            Rp ${order.total_harga.toLocaleString('id-ID')}
                        </td>
                        <td>
                            <span class="badge bg-${statusClass} text-uppercase px-3 py-2 rounded-pill">
                                ${order.status}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="/admin/orders/show/${order.id}" class="btn btn-sm btn-info text-white me-1">
                                <i class="fas fa-eye me-1"></i> Detail
                            </a>
                            <button onclick="updateOrderStatus(${order.id}, '${order.status}')" class="btn btn-sm btn-warning text-dark">
                                <i class="fas fa-edit me-1"></i> Update
                            </button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });

                // Update refresh time
                updateRefreshTime();
            })
            .catch(err => {
                console.error('Error loading orders:', err);
                const tbody = document.getElementById('orders-table-body');
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-5 text-danger">
                            <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                            <h5>Gagal memuat data pesanan!</h5>
                            <p class="text-muted">${err.message}</p>
                        </td>
                    </tr>
                `;
                updateRefreshTime();
            });
    }

    // Update refresh time indicator
    function updateRefreshTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID');
        document.getElementById('refreshTime').textContent = `Terakhir diperbarui: ${timeString}`;
    }

    // Start auto-refresh
    function startAutoRefresh() {
        if (autoRefreshInterval) return; // Already running
        
        loadOrders(); // Load immediately
        autoRefreshInterval = setInterval(loadOrders, 10000); // Then every 10 seconds
        isAutoRefreshActive = true;
        
        document.getElementById('autoRefreshBadge').classList.remove('paused');
        document.getElementById('autoRefreshBadge').innerHTML = '<i class="fas fa-circle me-1" style="font-size: 8px;"></i> Auto-Refresh ON';
        document.getElementById('toggleBtn').innerHTML = '<i class="fas fa-pause"></i> Pause';
        
        console.log('✅ Auto-refresh started (every 10 seconds)');
    }

    // Stop auto-refresh
    function stopAutoRefresh() {
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
            autoRefreshInterval = null;
        }
        isAutoRefreshActive = false;
        
        document.getElementById('autoRefreshBadge').classList.add('paused');
        document.getElementById('autoRefreshBadge').innerHTML = '<i class="fas fa-circle me-1" style="font-size: 8px;"></i> Auto-Refresh OFF';
        document.getElementById('toggleBtn').innerHTML = '<i class="fas fa-play"></i> Resume';
        
        console.log('⏸️ Auto-refresh stopped');
    }

    // Toggle auto-refresh
    function toggleAutoRefresh() {
        if (isAutoRefreshActive) {
            stopAutoRefresh();
        } else {
            startAutoRefresh();
        }
    }

    // Update order status function
    async function updateOrderStatus(orderId, currentStatus) {
        const statuses = [
            { value: 'pending', label: 'PENDING (Menunggu Proses)', emoji: '⏳' },
            { value: 'dikirim', label: 'DIKIRIM (Sedang Dikirim)', emoji: '🚚' },
            { value: 'selesai', label: 'SELESAI (Pesanan Selesai)', emoji: '✅' },
            { value: 'batal', label: 'BATAL (Pesanan Dibatalkan)', emoji: '❌' }
        ];
        
        let message = `🔄 UPDATE STATUS ORDER #${orderId}\n\n`;
        message += `Status saat ini: ${currentStatus.toUpperCase()}\n\n`;
        message += `Pilih status baru:\n`;
        statuses.forEach((s, i) => {
            message += `${i + 1}. ${s.emoji} ${s.label}\n`;
        });
        
        const choice = prompt(message + '\nMasukkan nomor (1-4):');
        
        if (!choice || choice < 1 || choice > 4) {
            alert('❌ Batal update status!');
            return;
        }
        
        const newStatus = statuses[choice - 1].value;
        
        if (newStatus === currentStatus) {
            alert('⚠️ Status tidak berubah!');
            return;
        }
        
        if (!confirm(`Yakin ubah status order #${orderId} dari "${currentStatus.toUpperCase()}" ke "${newStatus.toUpperCase()}"?`)) {
            return;
        }
        
        try {
            const response = await fetch(`http://localhost:8090/api/orders/${orderId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ status: newStatus })
            });
            
            const result = await response.json();
            
            if (result.success || response.ok) {
                alert(`✅ Status order #${orderId} berhasil diubah menjadi ${newStatus.toUpperCase()}!`);
                loadOrders(); // Reload data without full page refresh
            } else {
                alert('❌ Gagal update status: ' + (result.message || 'Unknown error'));
            }
        } catch (error) {
            alert('❌ Error: ' + error.message);
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        startAutoRefresh();
    });

    // Stop auto-refresh when user leaves page
    window.addEventListener('beforeunload', function() {
        stopAutoRefresh();
    });
    </script>

</body>
</html>
