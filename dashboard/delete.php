<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

$pdo = getConnection();
$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM produk WHERE id = ?");
$stmt->execute([$id]);
$produk = $stmt->fetch();

if ($produk) {
    // Hapus file gambar jika ada
    if (!empty($produk['gambar']) && file_exists('../assets/uploads/' . $produk['gambar'])) {
        unlink('../assets/uploads/' . $produk['gambar']);
    }

    $del = $pdo->prepare("DELETE FROM produk WHERE id = ?");
    $del->execute([$id]);

    setFlash('success', 'Produk "' . $produk['nama_produk'] . '" berhasil dihapus.');
} else {
    setFlash('error', 'Produk tidak ditemukan.');
}

header('Location: index.php');
exit;
