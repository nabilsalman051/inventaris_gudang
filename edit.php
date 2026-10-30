<?php
require_once 'koneksi.php';
require_once 'bahasa.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);
$result = mysqli_query($conn, "SELECT * FROM inventaris WHERE id = $id");
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header("Location: index.php");
    exit;
}

$pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_barang  = htmlspecialchars(trim($_POST['kode_barang']), ENT_QUOTES, 'UTF-8');
    $nama_barang  = htmlspecialchars(trim($_POST['nama_barang']), ENT_QUOTES, 'UTF-8');
    $kategori     = htmlspecialchars(trim($_POST['kategori']), ENT_QUOTES, 'UTF-8');
    $jumlah       = filter_var($_POST['jumlah'], FILTER_VALIDATE_INT);
    $harga_satuan = filter_var($_POST['harga_satuan'], FILTER_VALIDATE_FLOAT);
    $tanggal_masuk = trim($_POST['tanggal_masuk']);

    if (empty($kode_barang) || empty($nama_barang) || empty($kategori) || $jumlah === false || $harga_satuan === false) {
        $pesan = lang('error_empty');
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE inventaris SET kode_barang = ?, nama_barang = ?, kategori = ?, jumlah = ?, harga_satuan = ?, tanggal_masuk = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "sssidsi", $kode_barang, $nama_barang, $kategori, $jumlah, $harga_satuan, $tanggal_masuk, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php");
            exit;
        } else {
            $pesan = "Gagal memperbarui data.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?>">
<head>
    <meta charset="UTF-8">
    <title>Edit Barang</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f8f9fa; }
        .form-container { background: white; padding: 25px; border-radius: 8px; max-width: 500px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        .btn { background: #ffc107; color: black; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .btn-secondary { background: #6c757d; text-decoration: none; padding: 10px 15px; color: white; border-radius: 4px; display: inline-block; }
        .error { color: red; margin-bottom: 15px; }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Edit Barang</h2>
    <?php if ($pesan): ?><div class="error"><?= $pesan ?></div><?php endif; ?>
    <form action="" method="POST">
        <div class="form-group">
            <label><?= lang('code') ?></label>
            <input type="text" name="kode_barang" value="<?= htmlspecialchars($data['kode_barang']) ?>" required>
        </div>
        <div class="form-group">
            <label><?= lang('name') ?></label>
            <input type="text" name="nama_barang" value="<?= htmlspecialchars($data['nama_barang']) ?>" required>
        </div>
        <div class="form-group">
            <label><?= lang('category') ?></label>
            <input type="text" name="kategori" value="<?= htmlspecialchars($data['kategori']) ?>" required>
        </div>
        <div class="form-group">
            <label><?= lang('qty') ?></label>
            <input type="number" name="jumlah" value="<?= $data['jumlah'] ?>" required>
        </div>
        <div class="form-group">
            <label><?= lang('price') ?></label>
            <input type="number" step="0.01" name="harga_satuan" value="<?= $data['harga_satuan'] ?>" required>
        </div>
        <div class="form-group">
            <label><?= lang('date') ?></label>
            <input type="date" name="tanggal_masuk" value="<?= $data['tanggal_masuk'] ?>" required>
        </div>
        <button type="submit" class="btn"><?= lang('update') ?></button>
        <a href="index.php" class="btn btn-secondary"><?= lang('back') ?></a>
    </form>
</div>
</body>
</html>