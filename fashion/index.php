<?php
require 'config/database.php';
session_start();

$cats = $pdo->query('SELECT DISTINCT kategori FROM products ORDER BY kategori')
           ->fetchAll(PDO::FETCH_COLUMN);

$ps = $pdo->query('SELECT * FROM products ORDER BY id DESC LIMIT 8')
          ->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>StyleKita — Fashion UMKM</title>

    <link rel="stylesheet" href="assets/css/style.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/sweetalert.js"></script>

    <style>
        /* LOGO */
        .brand img {
            width: 120px;
            height: 70px;
            object-fit: contain;
            display: block;
        }

        .navbar {
            min-height: 90px;
        }

        .brand {
            display: flex;
            align-items: center;
        }

        /* GAMBAR BAJU DAN CELANA */
        .hero-card {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 35px;
    padding: 25px;
    width: 100%;
    height: 100%;
}

.hero-card img {
    width: 260px;
    height: 300px;
    object-fit: contain;
    display: block;
}

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .hero-card {
                gap: 10px;
                padding: 20px;
            }

            .hero-card img {
                width: 140px;
                height: 190px;
            }
        }

        @media (max-width: 480px) {
            .hero-card img {
                width: 110px;
                height: 160px;
            }
        }
    </style>

</head>

<body>

<!-- ================= NAVBAR ================= -->

<header class="navbar">

    <!-- LOGO -->
    <a class="brand" href="index.php">
        <img src="assets/logo.png" alt="StyleKita">
    </a>

    <!-- MENU -->
    <nav>
        <a href="index.php">Beranda</a>
        <a href="tentang.php">Tentang Kami</a>
        <a href="produk.php">Katalog Produk</a>
        <a href="kontak.php">Kontak</a>
    </nav>

    <!-- AKSI -->
    <div class="nav-actions">

        <a href="customer/keranjang.php">
            🛒 Keranjang
        </a>

        <?php if (isset($_SESSION['user_id'])): ?>

            <a href="customer/profil.php">
                👤 Profil
            </a>

            <a href="logout.php">
                🚪 Logout
            </a>

        <?php else: ?>

            <a href="login.php">
                🔐 Login
            </a>

            <a href="register.php">
                📝 Daftar
            </a>

        <?php endif; ?>

    </div>

</header>


<!-- ================= MAIN ================= -->

<main>

    <!-- ================= HERO ================= -->

    <section class="hero">

        <div class="hero-copy">

            <span class="eyebrow">
                Fashion Lokal • Nyaman • Kekinian
            </span>

            <h1>
                Tampil Stylish,
                <br>
                <em>Tetap Nyaman.</em>
            </h1>

            <p>
                Temukan pilihan baju dan celana dari StyleKita
                untuk menemani aktivitas sehari-hari dengan gaya
                yang simpel dan nyaman.
            </p>

            <div class="actions">

                <a class="btn btn-primary" href="produk.php">
                    Lihat Produk
                </a>

                <a class="btn btn-light" href="produk.php">
                    Belanja Sekarang
                </a>

            </div>

        </div>


        <!-- GAMBAR BAJU DAN CELANA -->
        <div class="hero-card">

            <img
                src="assets/baju.jpg"
                alt="Baju StyleKita"
            >

            <img
                src="assets/celana.jpeg"
                alt="Celana StyleKita"
            >

        </div>

    </section>


    <!-- ================= KATEGORI ================= -->

    <section class="section">

        <div class="section-heading">

            <span class="eyebrow">
                Kategori
            </span>

            <h2>
                Temukan Gayamu
            </h2>

            <p>
                Pilih kategori fashion sesuai kebutuhanmu.
            </p>

        </div>


        <div class="cats">

            <?php foreach ($cats as $c): ?>

                <a href="produk.php?kategori=<?= urlencode($c) ?>">

                    <b>
                        <?= htmlspecialchars(strtoupper($c)) ?>
                    </b>

                    <span>
                        →
                    </span>

                </a>

            <?php endforeach; ?>

        </div>

    </section>


    <!-- ================= PRODUK TERBARU ================= -->

    <section class="section white">

        <div class="section-heading">

            <span class="eyebrow">
                Produk Terbaru
            </span>

            <h2>
                Pilihan Untukmu
            </h2>

            <p>
                Produk fashion terbaru dari StyleKita.
            </p>

        </div>


        <div class="grid">

            <?php foreach ($ps as $p): ?>

                <article>

                    <a href="detail.php?id=<?= $p['id'] ?>">

                        <div class="pic">

                            <?php if (!empty($p['gambar'])): ?>

                                <img
                                    src="<?= htmlspecialchars($p['gambar']) ?>"
                                    alt="<?= htmlspecialchars($p['nama']) ?>"
                                >

                            <?php else: ?>

                                👕

                            <?php endif; ?>

                        </div>


                        <small>
                            <?= htmlspecialchars($p['kategori']) ?>
                        </small>


                        <h3>
                            <?= htmlspecialchars($p['nama']) ?>
                        </h3>


                        <b>
                            Rp <?= number_format($p['harga'], 0, ',', '.') ?>
                        </b>

                    </a>


                    <div style="display:flex; gap:12px; padding:15px;">

                        <a
                            href="detail.php?id=<?= $p['id'] ?>"
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
                            "
                        >
                            Detail
                        </a>


                        <a
                            href="detail.php?id=<?= $p['id'] ?>"
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
                            "
                        >
                            Beli
                        </a>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>

    </section>


    <!-- ================= WHY STYLEKITA ================= -->

    <section class="section soft">

        <div class="section-heading">

            <span class="eyebrow">
                Why StyleKita?
            </span>

            <h2>
                Kenapa Belanja di Sini? 💕
            </h2>

        </div>


        <div class="cats">

            <div class="form-card">

                ✨

                <h3>
                    Produk Pilihan
                </h3>

                <p>
                    Koleksi baju dan celana yang mudah dipadukan
                    untuk berbagai aktivitas.
                </p>

            </div>


            <div class="form-card">

                🧵

                <h3>
                    Nyaman Dipakai
                </h3>

                <p>
                    Mengutamakan pilihan bahan dan model yang nyaman
                    untuk penggunaan sehari-hari.
                </p>

            </div>

        </div>

    </section>


    <!-- ================= KONTAK ================= -->

    <section id="contact" class="section two-col">

        <div>

            <span class="eyebrow">
                Contact
            </span>

            <h2>
                Hubungi StyleKita
            </h2>

            <p>
                Punya pertanyaan, ingin custom produk,
                atau ingin melakukan pemesanan?
                Kami siap membantu.
            </p>

        </div>


        <div class="contact-card">

            <p>
                📱 <strong>WhatsApp</strong>
                <br>
                0856-0829-1380
            </p>

            <p>
                📷 <strong>Instagram</strong>
                <br>
                @stylekita
            </p>

            <p>
                📍 <strong>Alamat</strong>
                <br>
                jl.paduraksa
            </p>

            <p>
                🕐 <strong>Jam Operasional</strong>
                <br>
                Senin–Sabtu: 08.00–20.00
            </p>

        </div>

    </section>

</main>


<!-- ================= FOOTER ================= -->

<footer>

    <div>

        <strong>
            StyleKita
        </strong>

        <p>
            Fashion lokal dengan gaya simpel,
            nyaman, dan kekinian.
        </p>

    </div>


    <div>

        <strong>
            Navigasi
        </strong>

        <p>
            Beranda • Tentang Kami • Katalog • Kontak
        </p>

    </div>


    <div>

        <strong>
            Produk
        </strong>

        <p>
            Baju • Celana • Fashion Lokal
        </p>

    </div>


    <p>
        © 2026 StyleKita — UMKM Fashion.
        All Rights Reserved.
    </p>

</footer>

</body>
</html>