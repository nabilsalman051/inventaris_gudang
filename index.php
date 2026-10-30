<?php
require_once 'koneksi.php';
require_once 'bahasa.php';

// Proteksi Halaman dengan Sesi
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Proses Hapus Data (Delete)
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $stmt = mysqli_prepare($conn, "DELETE FROM inventaris WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    header("Location: index.php");
    exit;
}

$query = "SELECT * FROM inventaris ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?>">
<head>
    <meta charset="UTF-8">
    <title><?= lang('title') ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f8f9fa; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        th, td { border: 1px solid #dee2e6; padding: 12px; text-align: left; }
        th { background: #343a40; color: white; }
        a.btn { background: #28a745; color: white; padding: 8px 12px; text-decoration: none; border-radius: 4px; font-size: 14px; }
        a.btn-danger { background: #dc3545; }
        a.btn-warning { background: #ffc107; color: black; }
        .lang-menu { margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="lang-menu">
    Bahasa / Language: <a href="?lang=id">Indonesia</a> | <a href="?lang=en">English</a>
</div>

<div class="header">
    <h2><?= lang('title') ?> (<?= lang('welcome') ?>, <?= $_SESSION['username'] ?>)</h2>
    <div>
        <a href="tambah.php" class="btn">+ <?= lang('add_item') ?></a>
        <a href="logout.php" class="btn btn-danger"><?= lang('logout') ?></a>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th><?= lang('code') ?></th>
            <th><?= lang('name') ?></th>
            <th><?= lang('category') ?></th>
            <th><?= lang('qty') ?></th>
            <th><?= lang('price') ?></th>
            <th><?= lang('date') ?></th>
            <th><?= lang('action') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; while ($row = mysqli_fetch_assoc($result)) : ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['kode_barang']) ?></td>
            <td><?= htmlspecialchars($row['nama_barang']) ?></td>
            <td><?= htmlspecialchars($row['kategori']) ?></td>
            <td><?= $row['jumlah'] ?></td>
            <td><?= number_format($row['harga_satuan'], 2, ',', '.') ?></td>
            <td><?= $row['tanggal_masuk'] ?></td>
            <td>
                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning">Edit</a>
                <a href="index.php?hapus=<?= $row['id'] ?>" class="btn btn-danger" onclick="return confirm('<?= lang('delete_confirm') ?>')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>