<!DOCTYPE html>
<html>
<head>
    <title><?= esc($title) ?></title>
    <style>
        body { font-family: sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
        h1 { border-bottom: 1px solid #ddd; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f8f9fa; }
        .btn { padding: 8px 15px; text-decoration: none; color: white; border-radius: 4px; font-size: 14px; display: inline-block; }
        .btn-danger { background-color: #dc3545; }
        .btn-success { background-color: #28a745; }
        .btn-secondary { background-color: #6c757d; }
        .text-right { text-align: right; }
    </style>
</head>
<body>

    <h1>Keranjang Belanja</h1>

    <a href="/shop" class="btn btn-secondary" style="margin-bottom: 15px;">&larr; Kembali Belanja</a>

    <?php if(empty($cart)): ?>
        <div style="text-align: center; padding: 50px; background: #f9f9f9;">
            <h3>Keranjang Anda Kosong</h3>
            <p>Silahkan pilih produk di katalog.</p>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($cart as $id => $item): ?>
                <tr>
                    <td>
                        <strong><?= esc($item['nama']) ?></strong>
                    </td>
                    <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                    <td><?= $item['qty'] ?></td>
                    <td>Rp <?= number_format($item['harga'] * $item['qty'], 0, ',', '.') ?></td>
                    <td>
                        <a href="/shop/remove/<?= $id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus produk ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <tr style="background-color: #f1f1f1; font-weight: bold;">
                    <td colspan="3" class="text-right">TOTAL BAYAR:</td>
                    <td colspan="2" style="font-size: 18px; color: #28a745;">
                        Rp <?= number_format($total, 0, ',', '.') ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="text-right">
            <a href="/shop/clear" class="btn btn-danger" onclick="return confirm('Kosongkan keranjang?')">Kosongkan</a>
            
            <a href="/checkout" class="btn btn-success" style="padding: 10px 30px; font-size: 16px;">CHECKOUT SEKARANG &rarr;</a>
        </div>
    <?php endif; ?>

</body>
</html>