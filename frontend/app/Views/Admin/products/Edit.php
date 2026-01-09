<!DOCTYPE html>
<html>
<head>
    <title>Edit Produk</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .form-container { max-width: 500px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        input, textarea { width: 100%; padding: 8px; margin: 5px 0 15px 0; display: inline-block; border: 1px solid #ccc; box-sizing: border-box; }
        button { background-color: #FFA500; color: white; padding: 10px 15px; border: none; cursor: pointer; width: 100%; }
        .btn-back { background-color: #555; margin-top: 10px; display: block; text-align: center; text-decoration: none; padding: 10px 15px; color: white; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Produk</h2>
        <form action="/admin/products/update/<?= $product->id ?>" method="post" enctype="multipart/form-data">
            
            <label>Nama Produk</label>
            <input type="text" name="nama_produk" value="<?= $product->nama_produk ?>" required>

            <label>Harga (Rp)</label>
            <input type="number" name="harga" value="<?= $product->harga ?>" required>

            <label>Stok</label>
            <input type="number" name="stok" value="<?= $product->stok ?>" required>

            <label>Deskripsi</label>
            <textarea name="deskripsi" required><?= $product->deskripsi ?></textarea>

            <label>Ganti Gambar (Biarkan kosong jika tidak ingin mengganti)</label>
            <br>
            <?php if($product->gambar): ?>
                <img src="http://localhost:8090/storage/<?= $product->gambar ?>" width="100" style="margin-bottom: 10px;">
            <?php endif; ?>
            <input type="file" name="gambar" accept="image/*">

            <button type="submit">Update Produk</button>
            <a href="/admin/dashboard" class="btn-back">Batal</a>
        </form>
    </div>
</body>
</html>