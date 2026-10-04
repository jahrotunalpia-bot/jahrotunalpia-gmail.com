<?php
session_start();
require 'config/database.php';

$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $n = trim($_POST['nama']);
    $u = trim($_POST['username']);
    $e = trim($_POST['email']);
    $hp = trim($_POST['hp']);
    $p = $_POST['password'];

    $q = $pdo->prepare('SELECT id FROM users WHERE username=? OR email=?');
    $q->execute([$u, $e]);

    if ($q->fetch()) {
        $err = 'Username/email sudah digunakan.';
    } elseif (strlen($p) < 4) {
        $err = 'Password minimal 4 karakter.';
    } else {
        $q = $pdo->prepare('INSERT INTO users(nama,username,email,hp,password,role) VALUES(?,?,?,?,?,"pelanggan")');
        $q->execute([$n, $u, $e, $hp, $p]);
        header('Location: login.php?success=registered');
        exit;
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Daftar - StyleKita</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/sweetalert.js"></script>
</head>
<body class="center">
<div class="auth">
    <a class="brand" href="index.php">Style<span>Kita</span></a>
    <h1>Daftar Akun</h1>

    <?php if ($err): ?>
        <script>window.__swalError = <?= json_encode($err) ?>;</script>
    <?php endif; ?>

    <form method="post" data-swal-loading="Mendaftarkan akun...">
        <input name="nama" placeholder="Nama Lengkap" required>
        <input name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input name="hp" placeholder="No. HP" required>
        <input type="password" name="password" placeholder="Password" required>
        <button class="btn">Daftar</button>
    </form>

    <p>Sudah punya akun? <a href="login.php">Login</a></p>
</div>
</body>
</html>
