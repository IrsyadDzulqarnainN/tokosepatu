<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

$pageTitle = 'Edit Produk - StepUp Shoes';
$activePage = 'dashboard';
$isDashboard = true;

$pdo = getConnection();
$errors = [];

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM produk WHERE id = ?");
$stmt->execute([$id]);
$produk = $stmt->fetch();

if (!$produk) {
    setFlash('error', 'Produk tidak ditemukan.');
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_produk = clean($_POST['nama_produk'] ?? '');
    $merek       = clean($_POST['merek'] ?? '');
    $kategori    = clean($_POST['kategori'] ?? '');
    $ukuran      = (int) ($_POST['ukuran'] ?? 0);
    $harga       = (float) str_replace(['.', ','], ['', '.'], $_POST['harga'] ?? 0);
    $stok        = (int) ($_POST['stok'] ?? 0);
    $deskripsi   = clean($_POST['deskripsi'] ?? '');
    $gambarNama  = $produk['gambar'];

    if ($nama_produk === '') $errors[] = 'Nama produk wajib diisi.';
    if ($merek === '') $errors[] = 'Merek wajib diisi.';
    if ($ukuran <= 0) $errors[] = 'Ukuran tidak valid.';
    if ($harga <= 0) $errors[] = 'Harga tidak valid.';
    if ($stok < 0) $errors[] = 'Stok tidak valid.';

    if (!empty($_FILES['gambar']['name'])) {
        $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExt)) {
            $errors[] = 'Format gambar harus jpg, jpeg, png, atau webp.';
        } elseif ($_FILES['gambar']['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Ukuran gambar maksimal 2MB.';
        } else {
            $newGambar = uniqid('sepatu_') . '.' . $ext;
            $uploadPath = '../assets/uploads/' . $newGambar;
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $uploadPath)) {
                // Hapus gambar lama jika ada
                if (!empty($produk['gambar']) && file_exists('../assets/uploads/' . $produk['gambar'])) {
                    unlink('../assets/uploads/' . $produk['gambar']);
                }
                $gambarNama = $newGambar;
            } else {
                $errors[] = 'Gagal mengunggah gambar.';
            }
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare(
            "UPDATE produk SET nama_produk=?, merek=?, kategori=?, ukuran=?, harga=?, stok=?, deskripsi=?, gambar=?
             WHERE id=?"
        );
        $stmt->execute([$nama_produk, $merek, $kategori, $ukuran, $harga, $stok, $deskripsi, $gambarNama, $id]);

        setFlash('success', 'Produk berhasil diperbarui.');
        header('Location: index.php');
        exit;
    } else {
        // supaya form tetap menampilkan input terakhir jika error
        $produk = array_merge($produk, $_POST);
    }
}

include '../includes/header.php';
?>

<div class="container py-4">
    <h3 class="fw-bold mb-4"><i class="bi bi-pencil-square"></i> Edit Produk</h3>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card dashboard-card">
        <div class="card-body">
            <form method="post" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="nama_produk" class="form-control"
                               value="<?= htmlspecialchars($produk['nama_produk']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Merek</label>
                        <input type="text" name="merek" class="form-control"
                               value="<?= htmlspecialchars($produk['merek']) ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-select">
                            <?php foreach (['Sneakers', 'Formal', 'Olahraga', 'Sandal', 'Boots'] as $kat): ?>
                                <option value="<?= $kat ?>" <?= ($produk['kategori'] === $kat) ? 'selected' : '' ?>>
                                    <?= $kat ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Ukuran (EU)</label>
                        <input type="number" name="ukuran" class="form-control" min="30" max="50"
                               value="<?= htmlspecialchars($produk['ukuran']) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" class="form-control" min="0"
                               value="<?= htmlspecialchars($produk['stok']) ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="text" name="harga" class="form-control"
                               value="<?= htmlspecialchars($produk['harga']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Gambar Produk</label>
                        <input type="file" name="gambar" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                        <?php if (!empty($produk['gambar']) && file_exists('../assets/uploads/' . $produk['gambar'])): ?>
                            <img src="../assets/uploads/<?= htmlspecialchars($produk['gambar']) ?>" class="thumb-preview mt-2">
                        <?php endif; ?>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" class="form-control"><?= htmlspecialchars($produk['deskripsi']) ?></textarea>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-warning fw-semibold">
                        <i class="bi bi-save"></i> Perbarui
                    </button>
                    <a href="index.php" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
