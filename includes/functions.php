<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Set pesan flash yang akan tampil sekali di halaman berikutnya
 */
function setFlash($type, $message)
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/**
 * Tampilkan & hapus pesan flash
 */
function showFlash()
{
    if (!empty($_SESSION['flash'])) {
        $type = $_SESSION['flash']['type'] === 'error' ? 'danger' : $_SESSION['flash']['type'];
        $message = htmlspecialchars($_SESSION['flash']['message']);
        echo "<div class=\"alert alert-{$type} alert-dismissible fade show m-3\" role=\"alert\">
                {$message}
                <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
              </div>";
        unset($_SESSION['flash']);
    }
}

/**
 * Format angka ke format Rupiah
 */
function formatRupiah($angka)
{
    return 'Rp ' . number_format((float) $angka, 0, ',', '.');
}

/**
 * Bersihkan input string
 */
function clean($str)
{
    return htmlspecialchars(trim($str ?? ''), ENT_QUOTES, 'UTF-8');
}
