<?php
require '../config/auth.php';
require '../config/database.php';

wajibAdmin();

$id = (int)$_GET['id'];

$q = $pdo->prepare('SELECT * FROM products WHERE id=?');
$q->execute([$id]);
$p = $q->fetch(PDO::FETCH_ASSOC);

if (!$p) {
    die('Produk tidak ditemukan.');
}


/* =========================
   UPDATE PRODUK
========================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $gambar = $p['gambar'];

    /* Folder upload */
    $folder = '../uploads/';

    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    /* Jika memilih gambar baru */
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {

        $namaAsli = basename($_FILES['gambar']['name']);
        $namaFile = time() . '_' . $namaAsli;

        $tujuan = $folder . $namaFile;

        if (move_uploaded_file(
            $_FILES['gambar']['tmp_name'],
            $tujuan
        )) {
            $gambar = 'uploads/' . $namaFile;
        }
    }


    /* Update database */
    $q = $pdo->prepare(
        'UPDATE products SET
        nama=?,
        kategori=?,
        harga=?,
        stok=?,
        ukuran=?,
        deskripsi=?,
        gambar=?
        WHERE id=?'
    );

    $q->execute([
        $_POST['nama'],
        $_POST['kategori'],
        $_POST['harga'],
        $_POST['stok'],
        $_POST['ukuran'],
        $_POST['deskripsi'],
        $gambar,
        $id
    ]);

    header('Location: dashboard.php?success=product_updated');
    exit;
}
?>

<!doctype html>

<html lang="id">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width,initial-scale=1"
    >

    <title>Edit Produk</title>

    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/js/sweetalert.js"></script>

</head>

<body class="center">

    <div class="auth wide">

        <h1>Edit Produk</h1>

        <form
            method="post"
            enctype="multipart/form-data"
            data-swal-loading="Menyimpan perubahan..."
        >

            <!-- NAMA -->
            <input
                name="nama"
                value="<?= htmlspecialchars($p['nama']) ?>"
                placeholder="Nama produk"
                required
            >


            <!-- KATEGORI -->
            <select name="kategori">

                <option
                    value="baju"
                    <?= $p['kategori'] === 'baju' ? 'selected' : '' ?>
                >
                    Baju
                </option>

                <option
                    value="celana"
                    <?= $p['kategori'] === 'celana' ? 'selected' : '' ?>
                >
                    Celana
                </option>

            </select>


            <!-- HARGA -->
            <input
                name="harga"
                type="number"
                value="<?= htmlspecialchars($p['harga']) ?>"
                placeholder="Harga"
                required
            >


            <!-- STOK -->
            <input
                name="stok"
                type="number"
                value="<?= htmlspecialchars($p['stok']) ?>"
                placeholder="Stok"
                required
            >


            <!-- UKURAN -->
            <input
                name="ukuran"
                value="<?= htmlspecialchars($p['ukuran']) ?>"
                placeholder="Ukuran"
            >


            <!-- GAMBAR -->
            <label>
                Gambar Produk
            </label>

            <?php if (!empty($p['gambar'])): ?>

                <p style="font-size:13px;color:#85766f;">
                    Gambar saat ini:
                </p>

                <img
                    src="../<?= htmlspecialchars($p['gambar']) ?>"
                    alt="<?= htmlspecialchars($p['nama']) ?>"
                    style="
                        width:120px;
                        height:120px;
                        object-fit:cover;
                        border-radius:15px;
                        margin-bottom:10px;
                    "
                >

            <?php endif; ?>


            <input
                type="file"
                name="gambar"
                accept="image/*"
            >


            <!-- DESKRIPSI -->
            <textarea
                name="deskripsi"
                placeholder="Deskripsi"
            ><?= htmlspecialchars($p['deskripsi']) ?></textarea>


            <!-- UPDATE -->
            <button
                type="submit"
                class="btn"
            >
                Update
            </button>

        </form>

    </div>

</body>

</html>