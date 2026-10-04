<?php
require '../config/auth.php';
require '../config/database.php';

wajibPelanggan();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $qty = max(1, (int)$_POST['qty']);
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;

    header('Location: keranjang.php?success=cart_added');
    exit;
}

if (isset($_GET['hapus'])) {
    unset($_SESSION['cart'][(int)$_GET['hapus']]);
    header('Location: keranjang.php?success=cart_deleted');
    exit;
}

$items = [];
$total = 0;

if ($_SESSION['cart']) {
    $in = implode(',', array_fill(0, count($_SESSION['cart']), '?'));
    $q = $pdo->prepare("SELECT * FROM products WHERE id IN ($in)");
    $q->execute(array_keys($_SESSION['cart']));

    foreach ($q as $p) {
        $p['qty'] = $_SESSION['cart'][$p['id']];
        $p['subtotal'] = $p['qty'] * $p['harga'];
        $total += $p['subtotal'];
        $items[] = $p;
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Keranjang</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/js/sweetalert.js"></script>
</head>
<body>
<header>
    <a class="brand" href="index.php">Style<span>Kita</span></a>
    <nav>
        <a href="index.php">Produk</a>
        <a href="profil.php">Profil</a>
    </nav>
    <a href="../logout.php">Logout</a>
</header>

<section class="section">
    <h1>Keranjang Belanja</h1>

    <?php if (!$items): ?>
        <p>Keranjang masih kosong.</p>
    <?php else: ?>
        <div class="table">
            <div class="tr head">
                <b>Produk</b><b>Qty</b><b>Harga</b><b>Total</b><b></b>
            </div>

            <?php foreach ($items as $p): ?>
                <div class="tr">
                    <span><?= htmlspecialchars($p['nama']) ?></span>
                    <span><?= $p['qty'] ?></span>
                    <span>Rp <?= number_format($p['harga'],0,',','.') ?></span>
                    <b>Rp <?= number_format($p['subtotal'],0,',','.') ?></b>
                    <a href="?hapus=<?= $p['id'] ?>"
                       data-swal-confirm
                       data-swal-title="Hapus dari keranjang?"
                       data-swal-text="Produk ini akan dihapus dari keranjang.">
                        Hapus
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <h2>Total: Rp <?= number_format($total,0,',','.') ?></h2>
        <a class="btn" href="checkout.php">Beli</a>
    <?php endif; ?>
</section>
</body>
</html>
