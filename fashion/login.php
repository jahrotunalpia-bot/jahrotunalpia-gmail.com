<?php
session_start();
require 'config/database.php';

$err = '';

if (isset($_SESSION['user_id'])) {
    header('Location: ' . ($_SESSION['role'] === 'admin' ? 'admin/dashboard.php' : 'customer/profil.php'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['login']);
    $p = $_POST['password'];

    $q = $pdo->prepare('SELECT * FROM users WHERE username=? OR email=? LIMIT 1');
    $q->execute([$u, $u]);
    $x = $q->fetch(PDO::FETCH_ASSOC);

    if ($x && $p === $x['password']) {
        $_SESSION['user_id'] = $x['id'];
        $_SESSION['nama'] = $x['nama'];
        $_SESSION['username'] = $x['username'];
        $_SESSION['role'] = $x['role'];

        header('Location: ' . ($x['role'] === 'admin'
            ? 'admin/dashboard.php?success=login_success'
            : 'customer/profil.php?success=login_success'));
        exit;
    }

    $err = 'Username/email atau password salah.';
}
?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <title>Login - StyleKita</title>

    <link rel="stylesheet" href="assets/css/style.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/sweetalert.js"></script>

    <style>
        .logo-login {
            width: 120px;
            height: 120px;
            object-fit: contain;
            display: block;
            margin: 0 auto 15px;
        }
    </style>
</head>

<body class="center">

<div class="auth">

    <!-- LOGO STYLEKITA -->
    <a href="index.php">
        <img src="assets/logo.png" alt="StyleKita" class="logo-login">
    </a>

    <h1>Masuk</h1>

    <?php if ($err): ?>
        <script>
            window.__swalError = <?= json_encode($err) ?>;
        </script>
    <?php endif; ?>

    <form method="post" data-swal-loading="Sedang login...">

        <input
            name="login"
            placeholder="Username atau Email"
            required
        >

        <input
            type="password"
            name="password"
            placeholder="Password"
            required
        >

        <button class="btn">
            Login
        </button>

    </form>

    <p>
        Belum punya akun?
        <a href="register.php">Daftar</a>
    </p>

</div>

</body>
</html>