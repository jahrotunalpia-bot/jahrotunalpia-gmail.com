<?php
require '../config/auth.php';
require '../config/database.php';

wajibAdmin();

$act = $_GET['act'] ?? '';

/* =========================
   HAPUS PRODUK
========================= */
if ($act === 'delp') {
    $pdo->prepare('DELETE FROM products WHERE id=?')
        ->execute([(int)$_GET['id']]);

    header('Location: dashboard.php?success=product_deleted');
    exit;
}

/* =========================
   HAPUS TRANSAKSI
========================= */
if ($act === 'delt') {
    $pdo->prepare('DELETE FROM transactions WHERE id=?')
        ->execute([(int)$_GET['id']]);

    header('Location: dashboard.php?tab=transaksi&success=transaction_deleted');
    exit;
}

/* =========================
   RESET PASSWORD PELANGGAN
========================= */
if ($act === 'reset') {
    $pdo->prepare(
        'UPDATE users SET password="123456" WHERE id=? AND role="pelanggan"'
    )->execute([(int)$_GET['id']]);

    header('Location: dashboard.php?tab=pelanggan&success=password_reset');
    exit;
}

/* =========================
   TAMBAH PRODUK
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nama'])) {

    $gambar = '';

    /* Folder upload */
    $folder = '../uploads/';

    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    /* Upload gambar */
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {

        $namaAsli = basename($_FILES['gambar']['name']);
        $namaFile = time() . '_' . $namaAsli;

        $tujuan = $folder . $namaFile;

        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $tujuan)) {
            $gambar = 'uploads/' . $namaFile;
        }
    }

    /* Simpan produk */
    $q = $pdo->prepare(
        'INSERT INTO products
        (nama, kategori, harga, stok, ukuran, deskripsi, gambar)
        VALUES (?, ?, ?, ?, ?, ?, ?)'
    );

    $q->execute([
        $_POST['nama'],
        $_POST['kategori'],
        $_POST['harga'],
        $_POST['stok'],
        $_POST['ukuran'],
        $_POST['deskripsi'],
        $gambar
    ]);

    header('Location: dashboard.php?success=product_added');
    exit;
}

/* =========================
   AMBIL DATA
========================= */

$ps = $pdo->query(
    'SELECT * FROM products ORDER BY id DESC'
)->fetchAll(PDO::FETCH_ASSOC);

$ts = $pdo->query(
    'SELECT t.*, u.nama
     FROM transactions t
     JOIN users u ON u.id=t.user_id
     ORDER BY t.id DESC'
)->fetchAll(PDO::FETCH_ASSOC);

$us = $pdo->query(
    'SELECT * FROM users
     WHERE role="pelanggan"
     ORDER BY id DESC'
)->fetchAll(PDO::FETCH_ASSOC);

$tab = $_GET['tab'] ?? 'produk';
?>

<!doctype html>
<html lang="id">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width,initial-scale=1">

    <title>Admin Dashboard</title>

    <link rel="stylesheet"
          href="../assets/css/style.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/js/sweetalert.js"></script>

</head>

<body>

<div class="admin-layout">

    <!-- SIDEBAR -->
    <aside>

        <a class="brand" href="../index.php">
            Style<span>Kita</span>
        </a>

        <h4>MENU</h4>

        <a class="side" href="?tab=produk">
            📦 Produk
        </a>

        <a class="side" href="?tab=transaksi">
            🧾 Transaksi
        </a>

        <a class="side" href="?tab=pelanggan">
            👥 Pelanggan
        </a>

        <a class="side" href="../logout.php">
            ↪ Logout
        </a>

    </aside>


    <!-- CONTENT -->
    <main class="admin-main">

        <h1>Dashboard Admin</h1>


        <?php if ($tab === 'produk'): ?>


        <!-- =========================
             TAMBAH PRODUK
        ========================== -->

        <div class="admin-card">

            <h2>Tambah Produk</h2>

            <form method="post"
                  enctype="multipart/form-data"
                  class="admin-form">

                <input
                    name="nama"
                    placeholder="Nama produk"
                    required
                >

                <select name="kategori">

                    <option value="baju">
                        Baju
                    </option>

                    <option value="celana">
                        Celana
                    </option>

                </select>

                <input
                    name="harga"
                    type="number"
                    placeholder="Harga"
                    required
                >

                <input
                    name="stok"
                    type="number"
                    placeholder="Stok"
                    required
                >

                <input
                    name="ukuran"
                    placeholder="Ukuran"
                >

                <label>
                    Gambar Produk
                </label>

                <input
                    type="file"
                    name="gambar"
                    accept="image/*"
                >

                <textarea
                    name="deskripsi"
                    placeholder="Deskripsi"
                ></textarea>

                <button
                    type="submit"
                    class="btn"
                >
                    Tambah Produk
                </button>

            </form>

        </div>


        <!-- =========================
             DATA PRODUK
        ========================== -->

        <div class="admin-card">

            <h2>Data Produk</h2>

            <div class="table">

                <div class="tr head">

                    <b>Gambar</b>
                    <b>Nama</b>
                    <b>Kategori</b>
                    <b>Harga</b>
                    <b>Stok</b>
                    <b>Aksi</b>

                </div>


                <?php foreach ($ps as $p): ?>

                <div class="tr">

                    <!-- GAMBAR -->
                    <span>

                        <?php if (!empty($p['gambar'])): ?>

                            <img
                                src="../<?= htmlspecialchars($p['gambar']) ?>"
                                alt="<?= htmlspecialchars($p['nama']) ?>"
                                style="
                                    width:70px;
                                    height:70px;
                                    object-fit:cover;
                                    border-radius:10px;
                                "
                            >

                        <?php else: ?>

                            <span>
                                Tidak ada gambar
                            </span>

                        <?php endif; ?>

                    </span>


                    <!-- NAMA -->
                    <span>
                        <?= htmlspecialchars($p['nama']) ?>
                    </span>


                    <!-- KATEGORI -->
                    <span>
                        <?= htmlspecialchars($p['kategori']) ?>
                    </span>


                    <!-- HARGA -->
                    <span>
                        Rp <?= number_format(
                            $p['harga'],
                            0,
                            ',',
                            '.'
                        ) ?>
                    </span>


                    <!-- STOK -->
                    <span>
                        <?= htmlspecialchars($p['stok']) ?>
                    </span>


                    <!-- AKSI -->
                    <span>

                        <a href="edit.php?id=<?= $p['id'] ?>">
                            Edit
                        </a>

                        |

                        <a
                            href="?act=delp&id=<?= $p['id'] ?>"
                            data-swal-confirm data-swal-title="Hapus produk?" data-swal-text="Produk ini akan dihapus dari data."
                        >
                            Hapus
                        </a>

                    </span>

                </div>

                <?php endforeach; ?>

            </div>

        </div>


        <?php elseif ($tab === 'transaksi'): ?>


        <!-- =========================
             TRANSAKSI
        ========================== -->

        <div class="admin-card">

            <h2>Data Transaksi</h2>

            <div class="table">

                <div class="tr head">

                    <b>ID</b>
                    <b>Pelanggan</b>
                    <b>Total</b>
                    <b>Status</b>
                    <b>Aksi</b>

                </div>


                <?php foreach ($ts as $t): ?>

                <div class="tr">

                    <span>
                        #<?= $t['id'] ?>
                    </span>

                    <span>
                        <?= htmlspecialchars($t['nama']) ?>
                    </span>

                    <span>
                        Rp <?= number_format(
                            $t['total'],
                            0,
                            ',',
                            '.'
                        ) ?>
                    </span>

                    <span>
                        <?= htmlspecialchars($t['status']) ?>
                    </span>

                    <span>

                        <a href="invoice.php?id=<?= $t['id'] ?>">
                            Detail/Cetak
                        </a>

                        |

                        <a
                            href="?act=delt&id=<?= $t['id'] ?>"
                            data-swal-confirm data-swal-title="Hapus transaksi?" data-swal-text="Transaksi ini akan dihapus dari data."
                        >
                            Hapus
                        </a>

                    </span>

                </div>

                <?php endforeach; ?>

            </div>

        </div>


        <?php else: ?>


        <!-- =========================
             PELANGGAN
        ========================== -->

        <div class="admin-card">

            <h2>Data Pelanggan</h2>

            <div class="table">

                <div class="tr head">

                    <b>Nama</b>
                    <b>Username</b>
                    <b>Email</b>
                    <b>HP</b>
                    <b>Aksi</b>

                </div>


                <?php foreach ($us as $u): ?>

                <div class="tr">

                    <span>
                        <?= htmlspecialchars($u['nama']) ?>
                    </span>

                    <span>
                        <?= htmlspecialchars($u['username']) ?>
                    </span>

                    <span>
                        <?= htmlspecialchars($u['email']) ?>
                    </span>

                    <span>
                        <?= htmlspecialchars($u['hp']) ?>
                    </span>

                    <span>

                        <a
                            href="?act=reset&id=<?= $u['id'] ?>"
                            data-swal-confirm data-swal-title="Reset password?" data-swal-text="Password pelanggan akan diubah menjadi 123456."
                        >
                            Reset Password
                        </a>

                    </span>

                </div>

                <?php endforeach; ?>

            </div>

        </div>


        <?php endif; ?>


    </main>

</div>

</body>

</html>