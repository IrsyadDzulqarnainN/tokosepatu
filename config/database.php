<?php
/**
 * Konfigurasi koneksi database (PDO)
 * Sesuaikan DB_HOST, DB_USER, DB_PASS jika perlu.
 */
define('DB_HOST', 'localhost');
define('DB_NAME', 'toko_sepatu');
define('DB_USER', 'root');
define('DB_PASS', '');

function getConnection()
{
    static $pdo = null;

    if ($pdo === null) {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die('Koneksi database gagal: ' . $e->getMessage());
        }
    }

    return $pdo;
}
