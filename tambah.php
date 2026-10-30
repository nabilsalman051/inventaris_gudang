<?php
require_once 'koneksi.php';
require_once 'bahasa.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitasi Data Input
    $kode_barang  = htmlspecialchars(trim($_POST['kode_barang']), ENT_QUOTES, 'UTF-8');
    $nama_barang  = htmlspecialchars(trim($_POST['nama_barang']), ENT_QUOTES, 'UTF-8');
    $kategori     = htmlspecialchars(trim($_POST['kategori']), ENT_QUOTES, 'UTF-8');
    $jumlah       = filter_var($_POST['jumlah'], FILTER_VALIDATE_INT);
    $harga_satuan = filter_var($_POST['harga_satuan'], FILTER_VALIDATE_FLOAT);
    $tanggal_masuk = trim($_POST['tanggal_masuk']);

    // Struktur Kontrol Percabangan (if-else) untuk Validasi Kelengkapan Data
    if (empty($kode_barang) || empty($nama_barang) || empty($kategori) || $jumlah === false || $harga_satuan === false || empty($tanggal_masuk)) {
        $pesan = lang('error_empty');
    } else {
        // Query Prepared Statement untuk keamanan (mencegah SQL Injection)
        $stmt = mysqli_prepare($conn, "INSERT INTO inventaris (kode_barang, nama_barang, kategori, jumlah, harga_satuan, tanggal_masuk) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssids", $kode_barang, $nama_barang, $kategori, $jumlah, $harga_satuan, $tanggal_masuk);
        
        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php");
            exit;
        } else {
            $pesan = "Gagal menyimpan data ke database.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?>">
<head>
    <meta charset="UTF-8">
    <title><?= lang('add_item') ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f8f9fa; }
        .form-container { background: white; padding: 25px; border-radius: 8px; max-width: 500px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        .btn { background: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-secondary { background: #6c75d7; text-decoration: none; padding: 10px 15px; color: white; border-radius: 4px; display: inline-block; }
        .error { color: red; margin-bottom: 15px; }
    </style>
</head>
<body>
<div class="form-container">
    <h2><?= lang('add_item') ?></h2>
    <?php if ($pesan): ?><div class="error"><?= $pesan ?></div><?php endif; ?>
    <form action="" method="POST">
        <div class="form-group">
            <label><?= lang('code') ?></label>
            <input type="text" name="kode_barang" required>
        </div>
        <div class="form-group">
            <label><?= lang('name') ?></label>
            <input type="text" name="nama_barang" required>
        </div>
        <div class="form-group">
            <label><?= lang('category') ?></label>
            <input type="text" name="kategori" required>
        </div>
        <div class="form-group">
            <label><?= lang('qty') ?></label>
            <input type="number" name="jumlah" required>
        </div>
        <div class="form-group">
            <label><?= lang('price') ?></label>
            <input type="number" step="0.01" name="harga_satuan" required>
        </div>
        <div class="form-group">
            <label><?= lang('date') ?></label>
            <input type="date" name="tanggal_masuk" required>
        </div>
        <button type="submit" class="btn"><?= lang('save') ?></button>
        <a href="index.php" class="btn btn-secondary"><?= lang('back') ?></a>
    </form>
</div>
</body>
</html>