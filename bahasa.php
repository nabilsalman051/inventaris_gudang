<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set default bahasa ke Indonesia jika belum ada
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'id';
}

// Ubah bahasa jika ada parameter GET
if (isset($_GET['lang']) && in_array($_GET['lang'], ['id', 'en'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

// Kamus terjemahan
$translations = [
    'id' => [
        'title' => 'Sistem Inventaris Gudang',
        'welcome' => 'Selamat datang',
        'logout' => 'Keluar',
        'add_item' => 'Tambah Barang Baru',
        'code' => 'Kode Barang',
        'name' => 'Nama Barang',
        'category' => 'Kategori',
        'qty' => 'Jumlah',
        'price' => 'Harga Satuan (Rp)',
        'date' => 'Tanggal Masuk',
        'action' => 'Aksi',
        'save' => 'Simpan Data',
        'update' => 'Perbarui Data',
        'back' => 'Kembali',
        'delete_confirm' => 'Yakin ingin menghapus data ini?',
        'success_add' => 'Barang berhasil ditambahkan!',
        'success_update' => 'Barang berhasil diperbarui!',
        'success_delete' => 'Barang berhasil dihapus!',
        'error_empty' => 'Semua kolom wajib diisi dengan benar!',
        'login_title' => 'Login Administrator',
        'username' => 'Username',
        'password' => 'Password',
        'login_btn' => 'Masuk',
        'login_err' => 'Username atau Password salah!'
    ],
    'en' => [
        'title' => 'Warehouse Inventory System',
        'welcome' => 'Welcome',
        'logout' => 'Logout',
        'add_item' => 'Add New Item',
        'code' => 'Item Code',
        'name' => 'Item Name',
        'category' => 'Category',
        'qty' => 'Quantity',
        'price' => 'Unit Price ($)',
        'date' => 'Entry Date',
        'action' => 'Actions',
        'save' => 'Save Data',
        'update' => 'Update Data',
        'back' => 'Back',
        'delete_confirm' => 'Are you sure you want to delete this item?',
        'success_add' => 'Item successfully added!',
        'success_update' => 'Item successfully updated!',
        'success_delete' => 'Item successfully deleted!',
        'error_empty' => 'All fields must be filled correctly!',
        'login_title' => 'Administrator Login',
        'username' => 'Username',
        'password' => 'Password',
        'login_btn' => 'Login',
        'login_err' => 'Invalid Username or Password!'
    ]
];

function lang($phrase) {
    global $translations;
    $lang = $_SESSION['lang'];
    return $translations[$lang][$phrase] ?? $phrase;
}
?>