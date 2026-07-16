<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

$pageTitle = 'Tambah Produk - StepUp Shoes';
$activePage = 'dashboard';
$isDashboard = true;

$pdo = getConnection();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_produk = clean($_POST['nama_produk'] ?? '');
    $merek       = clean($_POST['merek'] ?? '');
    $kategori    = clean($_POST['kategori'] ?? '');
    $ukuran      = (int) ($_POST['ukuran'] ?? 0);
    $harga       = (float) str_replace(['.', ','], ['', '.'], $_POST['harga'] ?? 0);
    $stok        = (int) ($_POST['stok'] ?? 0);
    $deskripsi   = clean($_POST['deskripsi'] ?? '');
    $gambarNama  = null;

    // Validasi
    if ($nama_produk === '') $errors[] = 'Nama produk wajib diisi.';
    if ($merek === '') $errors[] = 'Merek wajib diisi.';
    if ($ukuran <= 0) $errors[] = 'Ukuran tidak valid.';
    if ($harga <= 0) $errors[] = 'Harga tidak valid.';
    if ($stok < 0) $errors[] = 'Stok tidak valid.';

    // Upload gambar (opsional)
    if (!empty($_FILES['gambar']['name'])) {
        $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExt)) {
            $errors[] = 'Format gambar harus jpg, jpeg, png, atau webp.';
        } elseif ($_FILES['gambar']['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Ukuran gambar maksimal 2MB.';
        } else {
            $gambarNama = uniqid('sepatu_') . '.' . $ext;
            $uploadPath = '../assets/uploads/' . $gambarNama;
            if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $uploadPath)) {
                $errors[] = 'Gagal mengunggah gambar.';
                $gambarNama = null;
            }
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare(
            "INSERT INTO produk (nama_produk, merek, kategori, ukuran, harga, stok, deskripsi, gambar)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([$nama_produk, $merek, $kategori, $ukuran, $harga, $stok, $deskripsi, $gambarNama]);

        setFlash('success', 'Produk berhasil ditambahkan.');
        header('Location: index.php');
        exit;
    }
}

include '../includes/header.php';
?>

<div class="container py-4">
    <h3 class="fw-bold mb-4"><i class="bi bi-plus-circle"></i> Tambah Produk Baru</h3>

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
                               value="<?= htmlspecialchars($_POST['nama_produk'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Merek</label>
                        <input type="text" name="merek" class="form-control"
                               value="<?= htmlspecialchars($_POST['merek'] ?? '') ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-select">
                            <?php foreach (['Sneakers', 'Formal', 'Olahraga', 'Sandal', 'Boots'] as $kat): ?>
                                <option value="<?= $kat ?>" <?= (($_POST['kategori'] ?? '') === $kat) ? 'selected' : '' ?>>
                                    <?= $kat ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Ukuran (EU)</label>
                        <input type="number" name="ukuran" class="form-control" min="30" max="50"
                               value="<?= htmlspecialchars($_POST['ukuran'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" class="form-control" min="0"
                               value="<?= htmlspecialchars($_POST['stok'] ?? '0') ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="text" name="harga" class="form-control" placeholder="contoh: 1250000"
                               value="<?= htmlspecialchars($_POST['harga'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Gambar Produk (opsional)</label>
                        <input type="file" name="gambar" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" class="form-control"><?= htmlspecialchars($_POST['deskripsi'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-warning fw-semibold">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <a href="index.php" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
