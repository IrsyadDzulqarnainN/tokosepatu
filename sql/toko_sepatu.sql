-- =========================================
-- Database: toko_sepatu
-- =========================================
CREATE DATABASE IF NOT EXISTS toko_sepatu CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE toko_sepatu;

-- Tabel produk (sepatu)
CREATE TABLE IF NOT EXISTS produk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(150) NOT NULL,
    merek VARCHAR(100) NOT NULL,
    kategori ENUM('Sneakers', 'Formal', 'Olahraga', 'Sandal', 'Boots') NOT NULL DEFAULT 'Sneakers',
    ukuran INT NOT NULL,
    harga DECIMAL(12,2) NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    deskripsi TEXT,
    gambar VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Data contoh
INSERT INTO produk (nama_produk, merek, kategori, ukuran, harga, stok, deskripsi, gambar) VALUES
('Air Max Runner', 'Nike', 'Sneakers', 42, 1250000, 15, 'Sepatu sneakers ringan dengan bantalan udara untuk kenyamanan maksimal.', NULL),
('Ultraboost Pro', 'Adidas', 'Olahraga', 41, 1850000, 10, 'Sepatu lari dengan teknologi boost untuk performa terbaik.', NULL),
('Oxford Classic', 'Bally', 'Formal', 40, 2100000, 8, 'Sepatu formal kulit asli, cocok untuk acara resmi.', NULL),
('Trail Blazer Boots', 'Timberland', 'Boots', 43, 1750000, 5, 'Sepatu boots tahan air untuk aktivitas outdoor.', NULL),
('Comfy Slide', 'Havaianas', 'Sandal', 39, 250000, 25, 'Sandal santai untuk penggunaan sehari-hari.', NULL);
