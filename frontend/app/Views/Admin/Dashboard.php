<!DOCTYPE html>
<html>
<head>
    <title><?= esc($title ?? 'Dashboard Admin') ?></title>

    <!-- Bootstrap & Icons -->
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
            border: none;
        }

        .table thead th {
            background-color: #343a40;
            color: white;
            font-weight: 500;
            border: none;
            padding: 15px;
        }
        .table tbody td {
            padding: 12px 15px;
            vertical-align: middle;
        }

        .thumb-img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        h3 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #333;
            font-weight: 600;
            font-size: 1.25rem;
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <div class="header">
        <div>
            <h1>Dashboard Admin</h1>
            <p class="text-muted mb-0">
                Selamat Datang, <strong><?= esc(session()->get('name')) ?></strong>
            </p>
        </div>
        <a href="/auth/logout" class="btn btn-danger">
            <i class="fas fa-sign-out-alt me-2"></i> Logout
        </a>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success mb-4"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger mb-4"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <!-- TABEL 1: STOK PRODUK -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>📦 Stok Gudang Real-time</h3>
        <a href="/admin/products/create" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Tambah Produk
        </a>
    </div>

    <div class="card card-box">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($products)): ?>
                        <?php foreach ($products as $key => $item) : ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td>
                                <?php if(!empty($item->gambar)): ?>
                                    <img src="http://localhost:8090/storage/<?= $item->gambar ?>" class="thumb-img">
                                <?php else: ?>
                                    <span class="text-muted small">No IMG</span>
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold"><?= esc($item->nama_produk) ?></td>
                            <td>Rp <?= number_format($item->harga, 0, ',', '.') ?></td>
                            <td><?= $item->stok ?></td>
                            <td>
                                <?php 
                                    if($item->stok == 0)      echo '<span class="badge bg-danger">Habis</span>';
                                    elseif($item->stok < 5)  echo '<span class="badge bg-warning text-dark">Menipis</span>';
                                    else                     echo '<span class="badge bg-success">Aman</span>';
                                ?>
                            </td>
                            <td>
                                <a href="/admin/products/edit/<?= $item->id ?>" class="btn btn-sm btn-warning text-dark me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/admin/products/delete/<?= $item->id ?>" class="btn btn-sm btn-danger"
                                   onclick="return confirm('Hapus produk?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center py-4">Tidak ada data produk.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- TABEL 3: PESANAN MASUK -->
    <div class="d-flex justify-content-between align-items-center mb-3 mt-5">
        <h3>🛒 Pesanan Terbaru (Incoming Orders)</h3>
        <a href="/admin/orders" class="text-decoration-none fw-bold">
            Lihat Semua Pesanan <i class="fas fa-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="card card-box">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background-color: #0d6efd !important;">
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
                    <?php if(!empty($orders)): ?>
                        <?php foreach($orders as $order): ?>
                        <tr>
                            <td class="ps-4 fw-bold text-primary">#<?= $order->id ?></td>
                            <td>
                                <div class="fw-bold"><?= esc($order->user->name ?? 'Guest') ?></div>
                                <small class="text-muted"><?= esc($order->user->email ?? '-') ?></small>
                            </td>
                            <td><?= date('d M Y, H:i', strtotime($order->created_at)) ?></td>
                            <td class="fw-bold text-success">
                                Rp <?= number_format($order->total_harga, 0, ',', '.') ?>
                            </td>
                            <td>
                                <span class="badge badge-<?= strtolower($order->status) ?> text-uppercase px-3 py-2 rounded-pill">
                                    <?= $order->status ?>
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="/admin/orders/show/<?= $order->id ?>" class="btn btn-sm btn-info text-white">
                                    <i class="fas fa-eye me-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-box-open fa-3x mb-3"></i>
                                <h5>Belum ada pesanan masuk.</h5>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script load data langganan -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
    fetch('http://localhost:8091/index.php/admin/subscriptions/json')
        .then(response => response.json())
        .then(res => {
            if (!res.success || !Array.isArray(res.data)) return;

            const tbody = document.querySelector('#table-subscriptions tbody');
            tbody.innerHTML = '';

            res.data.forEach((sub, index) => {
                const tr = document.createElement('tr');
                const jsonSafe = JSON.stringify(sub).replace(/'/g, "&apos;");

                tr.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${sub.user && sub.user.name ? sub.user.name : '-'}</td>
                    <td>${sub.user && sub.user.email ? sub.user.email : '-'}</td>
                    <td>${sub.plan_name}</td>
                    <td>${renderStatusBadge(sub.status)}</td>
                    <td>${sub.started_at ?? '-'}</td>
                    <td>${sub.ends_at ?? '-'}</td>
                    <td>${renderActionButtons(jsonSafe, sub.status, sub.id)}</td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(err => console.error('Gagal load subscriptions', err));
    });

    function renderStatusBadge(status) {
    if (!status) return '-';
    const s = status.toLowerCase();

    if (s === 'active')
        return '<span class="badge bg-success">Aktif</span>';

    if (s === 'pending_payment' || s === 'pending')
        return '<span class="badge bg-warning text-dark">Pending (Belum Bayar)</span>';

    if (s === 'expired')
        return '<span class="badge bg-secondary">Expired</span>';

    if (s === 'canceled' || s === 'cancelled')
        return '<span class="badge bg-danger">Canceled</span>';

    return `<span class="badge bg-light text-dark">${status}</span>`;
    }

    function renderActionButtons(jsonSafe, status, id) {
    const s = (status || '').toLowerCase();

    // Tombol Detail SELALU ada
    let html = `
        <button class="btn btn-sm btn-info text-white me-1"
                onclick='showSubDetail(${jsonSafe})'>
            Detail
        </button>
    `;

    // Pending (belum bayar) -> boleh dibatalkan
    if (s === 'pending_payment' || s === 'pending') {
        html += `
            <form action="/admin/subscriptions/cancel/${id}" method="POST" style="display:inline;" 
                  onsubmit="return confirm('Yakin batalkan langganan pending ini?')">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    Batalkan
                </button>
            </form>
        `;
    }

        // Optional: riwayat bisa dihapus jika canceled / expired
    if (s === 'canceled' || s === 'cancelled' || s === 'expired') {
        html += `
            <form action="/admin/subscriptions/delete/${id}" method="POST" style="display:inline;" 
                  onsubmit="return confirm('Yakin hapus riwayat langganan ini?')">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                <button type="submit" class="btn btn-sm btn-outline-secondary ms-1">
                    Hapus
                </button>
            </form>
        `;
    }


    // Active: cuma Detail (tidak ada Hapus)
    return html;
}

    function showSubDetail(sub) {
    const lines = [
        `ID: ${sub.id}`,
        `User: ${sub.user && sub.user.name ? sub.user.name : '-'}`,
        `Email: ${sub.user && sub.user.email ? sub.user.email : '-'}`,
        `Paket: ${sub.plan_name}`,
        `Telur/minggu: ${sub.eggs_per_week}`,
        `Harga/bulan: Rp ${sub.price_per_month}`,
        `Status: ${sub.status}`,
        `Mulai: ${sub.started_at ?? '-'}`,
        `Berakhir: ${sub.ends_at ?? '-'}`,
        `Dibuat: ${sub.created_at}`
    ];
    alert(lines.join('\n'));
    }
    </script>

     <!-- Script load orders dari Laravel API -->
    <script>
    // Fungsi load orders dari Laravel API
    function loadOrdersFromAPI() {
        fetch('http://localhost:8090/api/orders')
            .then(response => response.json())
            .then(orders => {
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
                    return;
                }

                tbody.innerHTML = '';
                
                orders.forEach(order => {
                    const statusBadge = order.status === 'pending' 
                        ? '<span class="badge bg-warning text-dark text-uppercase px-3 py-2 rounded-pill">PENDING</span>'
                        : order.status === 'dikirim'
                        ? '<span class="badge bg-info text-uppercase px-3 py-2 rounded-pill">DIKIRIM</span>'
                        : order.status === 'selesai'
                        ? '<span class="badge bg-success text-uppercase px-3 py-2 rounded-pill">SELESAI</span>'
                        : '<span class="badge bg-danger text-uppercase px-3 py-2 rounded-pill">BATAL</span>';

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
                        <td>${statusBadge}</td>
                         <td class="text-end pe-4">
                            <button onclick="viewOrderDetail(${order.id})" class="btn btn-sm btn-info text-white me-1">
                                <i class="fas fa-eye me-1"></i> Detail
                            </button>
                            <button onclick="updateOrderStatus(${order.id}, '${order.status}')" class="btn btn-sm btn-warning text-dark">
                                <i class="fas fa-edit me-1"></i> Update
                            </button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            })
            .catch(err => {
                console.error('Error loading orders:', err);
                // Kalau gagal, tampilkan pesan error tapi tetap tampilkan data PHP fallback
            });
    }

    // Panggil saat halaman load
    document.addEventListener('DOMContentLoaded', loadOrdersFromAPI);

    // Fungsi untuk lihat detail order
    async function viewOrderDetail(orderId) {
        try {
            const response = await fetch(`http://localhost:8090/api/orders`);
            const orders = await response.json();
            const order = orders.find(o => o.id === orderId);
            
            if (!order) {
                alert('Order tidak ditemukan!');
                return;
            }
            
            let details = `ORDER #${order.id}\n\n`;
            details += `Customer: ${order.user?.name || 'Guest'}\n`;
            details += `Email: ${order.user?.email || '-'}\n`;
            details += `Status: ${order.status.toUpperCase()}\n`;
            details += `Total: Rp ${order.total_harga.toLocaleString('id-ID')}\n\n`;
            details += `DETAIL PRODUK:\n`;
            
            if (order.details && order.details.length > 0) {
                order.details.forEach((item, index) => {
                    details += `${index + 1}. ${item.product.nama_produk}\n`;
                    details += `   ${item.jumlah} x Rp ${item.harga_saat_ini.toLocaleString('id-ID')} = Rp ${(item.jumlah * item.harga_saat_ini).toLocaleString('id-ID')}\n`;
                });
            }
            
            alert(details);
        } catch (error) {
            alert('Gagal memuat detail order: ' + error.message);
        }
    }

    // Fungsi untuk update status order
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
                loadOrdersFromAPI(); // Reload table
            } else {
                alert('❌ Gagal update status: ' + (result.message || 'Unknown error'));
            }
        } catch (error) {
            alert('❌ Error: ' + error.message);
        }
    }
    </script>

    <!-- ✅ SCRIPT APPROVE/REJECT MEMBERSHIP -->
    <script>
    async function approveMembership(userId, userName) {
        if (!confirm(`Approve membership untuk ${userName}?`)) return;
        
        try {
            const response = await fetch(`http://localhost:8090/api/admin/membership/approve/${userId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                alert(`✅ ${result.message}`);
                // Hapus row dari tabel
                document.getElementById(`member-row-${userId}`).remove();
                
                // Kalau sudah tidak ada pending, tampilkan pesan kosong
                const tbody = document.querySelector('#no-pending-row')?.closest('tbody');
                if (tbody && tbody.children.length === 0) {
                    tbody.innerHTML = `
                        <tr id="no-pending-row">
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-clock fa-3x mb-3"></i>
                                <h5>Tidak ada membership pending approval.</h5>
                            </td>
                        </tr>
                    `;
                }
            } else {
                alert(`❌ ${result.message}`);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('❌ Gagal approve membership: ' + error.message);
        }
    }

    async function rejectMembership(userId, userName) {
        if (!confirm(`Reject membership untuk ${userName}? Tindakan ini tidak bisa dibatalkan!`)) return;
        
        try {
            const response = await fetch(`http://localhost:8090/api/admin/membership/reject/${userId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                alert(`✅ ${result.message}`);
                // Hapus row dari tabel
                document.getElementById(`member-row-${userId}`).remove();
                
                // Kalau sudah tidak ada pending, tampilkan pesan kosong
                const tbody = document.querySelector('#no-pending-row')?.closest('tbody');
                if (tbody && tbody.children.length === 0) {
                    tbody.innerHTML = `
                        <tr id="no-pending-row">
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-clock fa-3x mb-3"></i>
                                <h5>Tidak ada membership pending approval.</h5>
                            </td>
                        </tr>
                    `;
                }
            } else {
                alert(`❌ ${result.message}`);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('❌ Gagal reject membership: ' + error.message);
        }
    }
    </script>
</body>
</html>
