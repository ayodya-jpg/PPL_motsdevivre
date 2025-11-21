<!DOCTYPE html>
<html>
<head>
    <title><?= esc($title) ?></title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f9f9f9; }
        .header { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .grid-container { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
        .card { background: white; border: 1px solid #ddd; border-radius: 8px; padding: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .card h3 { margin-top: 0; }
        .price { font-size: 18px; color: #007bff; font-weight: bold; }
        .btn-beli { display: block; width: 100%; padding: 10px; background: #28a745; color: white; text-align: center; text-decoration: none; border-radius: 4px; margin-top: 10px; }
        .btn-beli:hover { background: #218838; }
        .habis { background: #ccc; cursor: not-allowed; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Selamat Datang, <?= session()->get('name') ?></h1>
        <div>
            <a href="/cart" style="font-weight: bold; text-decoration: none; background: #007bff; color: white; padding: 5px 10px; border-radius: 4px;">
                Keranjang (<?= $cart_count ?? 0 ?>)
            </a> 
            | 
            <a href="/auth/logout" style="color: red;">Logout</a>
        </div>
    </div>

    <div class="grid-container">
        <?php if(!empty($products)): ?>
            <?php foreach ($products as $item) : ?>
            <div class="card">
                <div style="height: 150px; background: #eee; margin-bottom: 10px; display: flex; align-items: center; justify-content: center; color: #aaa;">
                    Gambar Produk
                </div>
                
                <h3><?= esc($item->nama_produk) ?></h3>
                <p><?= esc($item->deskripsi) ?></p>
                <p>Stok: <strong><?= $item->stok ?></strong></p>
                <div class="price">Rp <?= number_format($item->harga, 0, ',', '.') ?></div>

                <?php if($item->stok > 0): ?>
                    <a href="/shop/add/<?= $item->id ?>" class="btn-beli">Beli Sekarang</a>
                <?php else: ?>
                    <button class="btn-beli habis" disabled>Stok Habis</button>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>