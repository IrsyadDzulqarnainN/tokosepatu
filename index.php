<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$pageTitle = 'StepUp Shoes - Toko Sepatu Online';
$activePage = 'home';

$pdo = getConnection();

// Filter kategori (opsional, dari query string)
$kategori = $_GET['kategori'] ?? '';
if ($kategori !== '') {
    $stmt = $pdo->prepare("SELECT * FROM produk WHERE kategori = ? ORDER BY created_at DESC");
    $stmt->execute([$kategori]);
} else {
    $stmt = $pdo->query("SELECT * FROM produk ORDER BY created_at DESC");
}
$produkList = $stmt->fetchAll();

$kategoriList = ['Sneakers', 'Formal', 'Olahraga', 'Sandal', 'Boots'];

include 'includes/header.php';
?>

<!-- ===== Hero Section ===== -->
<section class="hero">
    <div class="container text-center">
        <h1 class="display-4"><i class="bi bi-shop"></i> StepUp <span class="highlight">Shoes</span></h1>
        <p class="lead">Temukan sepatu terbaik untuk setiap langkahmu — sneakers, formal, olahraga, hingga sandal.</p>
        <a href="#produk" class="btn btn-warning btn-lg fw-semibold mt-2">
            <i class="bi bi-bag"></i> Lihat Koleksi
        </a>
    </div>
</section>

<!-- ===== Produk Section ===== -->
<section id="produk" class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-3 mb-md-0"><i class="bi bi-grid-3x3-gap"></i> Koleksi Sepatu Kami</h2>

        <div class="btn-group flex-wrap" role="group">
            <a href="index.php" class="btn btn-sm <?= $kategori === '' ? 'btn-dark' : 'btn-outline-dark' ?>">Semua</a>
            <?php foreach ($kategoriList as $kat): ?>
                <a href="index.php?kategori=<?= urlencode($kat) ?>"
                   class="btn btn-sm <?= $kategori === $kat ? 'btn-dark' : 'btn-outline-dark' ?>">
                    <?= htmlspecialchars($kat) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if (empty($produkList)): ?>
        <div class="alert alert-info text-center">Belum ada produk untuk kategori ini.</div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($produkList as $produk): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card product-card h-100">
                        <div class="img-wrap">
                            <?php if (!empty($produk['gambar']) && file_exists('assets/uploads/' . $produk['gambar'])): ?>
                                <img src="assets/uploads/<?= htmlspecialchars($produk['gambar']) ?>" alt="<?= htmlspecialchars($produk['nama_produk']) ?>">
                            <?php else: ?>
                                <i class="bi bi-bag-heart"></i>
                            <?php endif; ?>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <span class="badge badge-kategori mb-2 align-self-start"><?= htmlspecialchars($produk['kategori']) ?></span>
                            <h6 class="fw-bold mb-1"><?= htmlspecialchars($produk['nama_produk']) ?></h6>
                            <p class="text-muted small mb-1"><?= htmlspecialchars($produk['merek']) ?> &middot; Size <?= htmlspecialchars($produk['ukuran']) ?></p>
                            <p class="fw-bold text-dark mb-1"><?= formatRupiah($produk['harga']) ?></p>
                            <p class="small text-muted mb-0">Stok: <?= (int) $produk['stok'] ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>
