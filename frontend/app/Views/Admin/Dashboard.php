<!DOCTYPE html>
<html>
<head>
    <title><?= esc($title) ?></title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px;}
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn-logout { background: #dc3545; color: white; text-decoration: none; padding: 8px 15px; border-radius: 4px; }
        .stok-aman { color: green; font-weight: bold; }
        .stok-tipis { color: orange; font-weight: bold; }
        .stok-habis { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Dashboard Admin</h1>
        <div>
            Halo, <strong><?= session()->get('name') ?></strong> 
            <a href="/auth/logout" class="btn-logout">Logout</a>
        </div>
    </div>

    <h3>Stok Gudang Real-time</h3>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($products)): ?>
                <?php foreach ($products as $item) : ?>
                <tr>
                    <td><?= $item->id ?></td>
                    <td><?= esc($item->nama_produk) ?></td>
                    <td>Rp <?= number_format($item->harga, 0, ',', '.') ?></td>
                    <td><?= $item->stok ?></td>
                    <td>
                        <?php 
                            if($item->stok == 0) echo '<span class="stok-habis">Habis</span>';
                            elseif($item->stok < 5) echo '<span class="stok-tipis">Menipis</span>';
                            else echo '<span class="stok-aman">Aman</span>';
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">Tidak ada data produk / Gagal koneksi API</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>