<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

$pageTitle = 'Dashboard - StepUp Shoes';
$activePage = 'dashboard';
$isDashboard = true;

$pdo = getConnection();

// Pencarian sederhana
$search = trim($_GET['q'] ?? '');
if ($search !== '') {
    $stmt = $pdo->prepare("SELECT * FROM produk WHERE nama_produk LIKE ? OR merek LIKE ? ORDER BY created_at DESC");
    $like = '%' . $search . '%';
    $stmt->execute([$like, $like]);
} else {
    $stmt = $pdo->query("SELECT * FROM produk ORDER BY created_at DESC");
}
$produkList = $stmt->fetchAll();

// Statistik ringkas
$totalProduk = $pdo->query("SELECT COUNT(*) FROM produk")->fetchColumn();
$totalStok = $pdo->query("SELECT COALESCE(SUM(stok),0) FROM produk")->fetchColumn();
$totalNilai = $pdo->query("SELECT COALESCE(SUM(harga*stok),0) FROM produk")->fetchColumn();

include '../includes/header.php';
?>

<div class="container py-4">
    <?php showFlash(); ?>

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-3 mb-md-0"><i class="bi bi-speedometer2"></i> Dashboard Produk</h3>
        <a href="create.php" class="btn btn-warning fw-semibold">
            <i class="bi bi-plus-circle"></i> Tambah Produk
        </a>
    </div>

    <!-- Statistik -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-box" style="background:#1c1c1e;">
                <div class="small">Total Produk</div>
                <div class="fs-3 fw-bold"><?= (int) $totalProduk ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-box" style="background:#0d6efd;">
                <div class="small">Total Stok</div>
                <div class="fs-3 fw-bold"><?= (int) $totalStok ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-box" style="background:#198754;">
                <div class="small">Estimasi Nilai Stok</div>
                <div class="fs-4 fw-bold"><?= formatRupiah($totalNilai) ?></div>
            </div>
        </div>
    </div>

    <!-- Pencarian -->
    <form class="row g-2 mb-3" method="get">
        <div class="col-8 col-md-4">
            <input type="text" name="q" class="form-control" placeholder="Cari nama / merek..." value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-4 col-md-2">
            <button class="btn btn-dark w-100" type="submit"><i class="bi bi-search"></i> Cari</button>
        </div>
    </form>

    <!-- Tabel Produk -->
    <div class="card dashboard-card">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Merek</th>
                        <th>Kategori</th>
                        <th>Ukuran</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($produkList)): ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">Belum ada data produk.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($produkList as $i => $produk): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td>
                                    <?php if (!empty($produk['gambar']) && file_exists('../assets/uploads/' . $produk['gambar'])): ?>
                                        <img src="../assets/uploads/<?= htmlspecialchars($produk['gambar']) ?>" class="thumb-preview">
                                    <?php else: ?>
                                        <div class="thumb-preview d-flex align-items-center justify-content-center">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-semibold"><?= htmlspecialchars($produk['nama_produk']) ?></td>
                                <td><?= htmlspecialchars($produk['merek']) ?></td>
                                <td><span class="badge badge-kategori"><?= htmlspecialchars($produk['kategori']) ?></span></td>
                                <td><?= htmlspecialchars($produk['ukuran']) ?></td>
                                <td><?= formatRupiah($produk['harga']) ?></td>
                                <td><?= (int) $produk['stok'] ?></td>
                                <td class="text-center text-nowrap">
                                    <a href="edit.php?id=<?= $produk['id'] ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="delete.php?id=<?= $produk['id'] ?>" class="btn btn-sm btn-outline-danger"
                                       title="Hapus"
                                       onclick="return confirm('Yakin ingin menghapus produk \'<?= htmlspecialchars(addslashes($produk['nama_produk'])) ?>\'?');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
