<?php 
require '../config/auth.php';
require '../config/database.php';
wajibPelanggan();
$ps=$pdo->query('SELECT * FROM products ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Pelanggan</title>
        <link rel="stylesheet" href="../assets/css/style.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="../assets/js/sweetalert.js"></script>
    </head>
    <body>
        <header>
            <a class="brand" href="../index.php">Style<span>Kita</span>
        </a>
        <nav>
            <a href="index.php">Produk</a>
            <a href="keranjang.php">Keranjang</a>
            <a href="profil.php">Profil</a>
        </nav>
        <a href="../logout.php">Logout</a>
    </header>
    <section class="section">
        <small>HALAMAN PELANGGAN</small>
        <h1>Halo, <?=htmlspecialchars($_SESSION['nama'])?></h1>
        <div class="grid">
            <?php 
            foreach($ps as $p):?>
            <article>

    <a href="../detail.php?id=<?=$p['id']?>">

        <div class="pic">
            <?php if($p['gambar']):?>
                <img src="../<?=htmlspecialchars($p['gambar'])?>" alt="">
            <?php else:?>
                👕
            <?php endif;?>
        </div>

        <small><?=htmlspecialchars($p['kategori'])?></small>

        <h3><?=htmlspecialchars($p['nama'])?></h3>

        <b>Rp <?=number_format($p['harga'],0,',','.')?></b>

    </a>

    <div style="display:flex; gap:12px; padding:15px;">

    <a href="../detail.php?id=<?=$p['id']?>"
       style="
           flex:1;
           display:block;
           text-align:center;
           padding:11px 5px;
           background:white;
           color:#6e4c42;
           border:1px solid #6e4c42;
           border-radius:8px;
           text-decoration:none;
           font-weight:600;
       ">
        Detail
    </a>

    <a href="../detail.php?id=<?=$p['id']?>"
       style="
           flex:1;
           display:block;
           text-align:center;
           padding:11px 5px;
           background:#6e4c42;
           color:white;
           border:1px solid #6e4c42;
           border-radius:8px;
           text-decoration:none;
           font-weight:600;
       ">
        Beli
    </a>

</div>

</article>
                    <?php 
                    endforeach;
                    ?>
                    </div>
                </section>
            </body>
            </html>
