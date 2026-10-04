<?php
require 'config/database.php';

$id = (int)$_GET['id'];

$q = $pdo->prepare('SELECT * FROM products WHERE id=?');
$q->execute([$id]);
$p = $q->fetch(PDO::FETCH_ASSOC);

if (!$p) die('Produk tidak ditemukan');

/* Ambil ukuran, harga, dan stok */
$qSize = $pdo->prepare('
    SELECT ukuran, harga, stok
    FROM product_sizes
    WHERE product_id=?
    ORDER BY id ASC
');
$qSize->execute([$id]);
$sizes = $qSize->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <title><?=htmlspecialchars($p['nama'])?></title>

    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/sweetalert.js"></script>
</head>

<body>

<header>
    <a class="brand" href="index.php">
        Style<span>Kita</span>
    </a>

    <nav>
        <a href="index.php">Beranda</a>
        <a href="produk.php">Produk</a>
    </nav>

    <a href="customer/keranjang.php">🛒</a>
</header>


<section class="detail">

    <!-- GAMBAR PRODUK -->
    <div class="detail-pic">

        <?php if($p['gambar']): ?>

            <img src="<?=htmlspecialchars($p['gambar'])?>">

        <?php else: ?>

            👕
            
        <?php endif; ?>

    </div>


    <!-- INFORMASI PRODUK -->
    <div>

        <small>
            <?=strtoupper(htmlspecialchars($p['kategori']))?>
        </small>

        <h1>
            <?=htmlspecialchars($p['nama'])?>
        </h1>


        <!-- HARGA -->
        <h2 id="hargaProduk">
            Rp <?=number_format($p['harga'],0,',','.')?>
        </h2>


        <p>
            <?=htmlspecialchars($p['deskripsi'])?>
        </p>


        <!-- PILIH UKURAN -->
        <?php if($sizes): ?>

            <p>
                <b>Pilih Ukuran:</b>
            </p>

            <div style="display:flex;gap:10px;flex-wrap:wrap;margin:10px 0 20px;">

                <?php foreach($sizes as $index => $s): ?>

                    <button
                        type="button"
                        class="size-btn"
                        data-ukuran="<?=htmlspecialchars($s['ukuran'])?>"
                        data-harga="<?=htmlspecialchars($s['harga'])?>"
                        data-stok="<?=$s['stok']?>"
                        style="
                            padding:10px 18px;
                            border:1px solid #6e4c42;
                            background:white;
                            border-radius:8px;
                            cursor:pointer;
                        "
                    >
                        <?=htmlspecialchars($s['ukuran'])?>
                    </button>

                <?php endforeach; ?>

            </div>


            <!-- STOK -->
            <p id="stokProduk">
                <b>Stok:</b> -
            </p>


        <?php else: ?>

            <p>
                <b>Ukuran:</b> <?=htmlspecialchars($p['ukuran'])?>
                &nbsp;
                <b>Stok:</b> <?=$p['stok']?>
            </p>

        <?php endif; ?>


        <!-- FORM KERANJANG -->
        <form action="customer/keranjang.php" method="post" data-swal-loading="Menambahkan ke keranjang...">

            <input
                type="hidden"
                name="id"
                value="<?=$p['id']?>"
            >

            <input
                type="hidden"
                name="ukuran"
                id="ukuranInput"
                value=""
            >

            <input
                type="hidden"
                name="harga"
                id="hargaInput"
                value="<?=$p['harga']?>"
            >


            <div class="qty-control">
                <button type="button" id="minusBtn">−</button>

            <input
                type="text"
                name="qty"
                id="qtyInput"
                value="1"
                readonly
            >

                <button type="button" id="plusBtn">+</button>
            </div>

            <button
                class="btn"
                type="submit"
                id="btnKeranjang"
            >
                Tambah ke Keranjang
            </button>

        </form>

    </div>

</section>

<script>

const tombolUkuran = document.querySelectorAll('.size-btn');
const hargaProduk = document.getElementById('hargaProduk');
const stokProduk = document.getElementById('stokProduk');

const ukuranInput = document.getElementById('ukuranInput');
const hargaInput = document.getElementById('hargaInput');
const qtyInput = document.getElementById('qtyInput');

const minusBtn = document.getElementById('minusBtn');
const plusBtn = document.getElementById('plusBtn');


/* PILIH UKURAN */
tombolUkuran.forEach(function(button) {

    button.addEventListener('click', function() {

        const ukuran = this.getAttribute('data-ukuran');
        const harga = parseFloat(this.getAttribute('data-harga'));
        const stok = parseInt(this.getAttribute('data-stok'));

        /* Ubah harga */
        hargaProduk.textContent =
            'Rp ' + harga.toLocaleString('id-ID');

        /* Ubah stok */
        stokProduk.innerHTML =
            '<b>Stok:</b> ' + stok;

        /* Simpan ukuran dan harga */
        ukuranInput.value = ukuran;
        hargaInput.value = harga;

        /* Atur stok maksimal */
        qtyInput.max = stok;

        /* Reset jumlah */
        qtyInput.value = stok > 0 ? 1 : 0;

        /* Tandai ukuran yang dipilih */
        tombolUkuran.forEach(function(btn) {
            btn.style.background = 'white';
            btn.style.color = '#3e302c';
        });

        this.style.background = '#6e4c42';
        this.style.color = 'white';

    });

});


/* TOMBOL MINUS */
minusBtn.addEventListener('click', function() {

    let jumlah = parseInt(qtyInput.value);

    if (jumlah > 1) {
        qtyInput.value = jumlah - 1;
    }

});


/* TOMBOL PLUS */
plusBtn.addEventListener('click', function() {

    let jumlah = parseInt(qtyInput.value);
    let stok = parseInt(qtyInput.max);

    if (jumlah < stok) {
        qtyInput.value = jumlah + 1;
    }

});

</script>

</body>
</html>