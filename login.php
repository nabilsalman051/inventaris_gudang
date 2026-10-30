<?php
require_once 'koneksi.php';
require_once 'bahasa.php';

if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Sanitasi input
    $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Validasi password (untuk contoh sederhana menggunakan plain text atau password_verify jika di-hash)
        if ($password === $row['password']) {
            $_SESSION['login'] = true;
            $_SESSION['username'] = $row['username'];
            header("Location: index.php");
            exit;
        }
    }
    $error = lang('login_err');
}
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?>">
<head>
    <meta charset="UTF-8">
    <title><?= lang('login_title') ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 300px; }
        .login-card h2 { margin-bottom: 20px; font-size: 20px; text-align: center; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-size: 14px; }
        .form-group input { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        .btn { width: 100%; background: #007bff; color: white; border: none; padding: 10px; border-radius: 4px; cursor: pointer; }
        .btn:hover { background: #0056b3; }
        .error { color: red; font-size: 13px; margin-bottom: 10px; text-align: center; }
        .lang-switch { text-align: center; margin-top: 15px; font-size: 13px; }
    </style>
</head>
<body>
<div class="login-card">
    <h2><?= lang('login_title') ?></h2>
    <?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
    <form action="" method="POST">
        <div class="form-group">
            <label><?= lang('username') ?></label>
            <input type="text" name="username" required>
        </div>
        <div class="form-group">
            <label><?= lang('password') ?></label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" class="btn"><?= lang('login_btn') ?></button>
    </form>
    <div class="lang-switch">
        <a href="?lang=id">ID</a> | <a href="?lang=en">EN</a>
    </div>
</div>
</body>
</html>