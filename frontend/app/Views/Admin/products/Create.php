<!DOCTYPE html>
<html>
<head>
    <title>Tambah Produk</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .form-container { max-width: 500px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        input, textarea { width: 100%; padding: 8px; margin: 5px 0 15px 0; display: inline-block; border: 1px solid #ccc; box-sizing: border-box; }
        button { background-color: #4CAF50; color: white; padding: 10px 15px; border: none; cursor: pointer; width: 100%; }
        .btn-back { background-color: #f44336; margin-top: 10px; display: block; text-align: center; text-decoration: none; padding: 10px 15px; color: white; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Tambah Produk Baru</h2>
        <!-- enctype multipart wajib untuk upload file -->
        <form action="/admin/products/store" method="post" enctype="multipart/form-data">
            
            <label>Nama Produk</label>
            <input type="text" name="nama_produk" required>

            <label>Harga (Rp)</label>
            <input type="number" name="harga" required>

            <label>Stok</label>
            <input type="number" name="stok" required>

            <label>Deskripsi</label>
            <textarea name="deskripsi" required></textarea>

            <label>Gambar Produk</label>
            <input type="file" name="gambar" required accept="image/*">

            <button type="submit">Simpan Produk</button>
            <a href="/admin/dashboard" class="btn-back">Batal</a>
        </form>
    </div>
</body>
</html>
