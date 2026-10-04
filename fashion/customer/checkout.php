<?php
require '../config/auth.php';
require '../config/database.php';

wajibPelanggan();

if (empty($_SESSION['cart'])) {
    header('Location: keranjang.php');
    exit;
}

$ids = array_keys($_SESSION['cart']);
$in = implode(',', array_fill(0, count($ids), '?'));
$q = $pdo->prepare("SELECT * FROM products WHERE id IN ($in)");
$q->execute($ids);
$items = $q->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
foreach ($items as $p) {
    $total += $p['harga'] * $_SESSION['cart'][$p['id']];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $q = $pdo->prepare(
        'INSERT INTO transactions(user_id,total,pembayaran,pengiriman,status)
         VALUES(?,?,?,?,"Diproses")'
    );
    $q->execute([
        $_SESSION['user_id'],
        $total,
        $_POST['pembayaran'],
        $_POST['pengiriman']
    ]);

    $tid = $pdo->lastInsertId();

    $qi = $pdo->prepare(
        'INSERT INTO transaction_items(transaction_id,product_id,qty,harga)
         VALUES(?,?,?,?)'
    );

    foreach ($items as $p) {
        $qi->execute([
            $tid,
            $p['id'],
            $_SESSION['cart'][$p['id']],
            $p['harga']
        ]);
    }

    unset($_SESSION['cart']);

    header('Location: invoice.php?id=' . $tid . '&success=checkout_success');
    exit;
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Checkout</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/js/sweetalert.js"></script>
</head>
<body>
<div class="auth">
    <div class="auth wide">
        <h1>Checkout</h1>
        <p>Total belanja: <b>Rp <?= number_format($total,0,',','.') ?></b></p>

        <form method="post" data-swal-loading="Memproses pesanan...">
            <label>Metode Pembayaran</label>
            <select name="pembayaran">
                <option>Transfer Bank</option>
                <option>COD</option>
                <option>E-Wallet</option>
            </select>

            <label>Metode Pengiriman</label>
            <select name="pengiriman">
                <option>Kurir Reguler</option>
                <option>Kurir Express</option>
                <option>Ambil di Toko</option>
            </select>

            <button class="btn">Beli Sekarang</button>
        </form>
    </div>
</div>
</body>
</html>
