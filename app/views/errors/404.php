<?php
// Define BASE_URL jika belum terdefined
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/Sistem-Peminjaman-Lab');
}

// Configuration
$code = '404';
$title = 'Halaman Tidak Ditemukan';
$message = 'Maaf, halaman yang Anda cari tidak dapat ditemukan. Mungkin halaman telah dipindahkan atau URL yang Anda masukkan salah.';
$icon = 'bi-exclamation-triangle';
$iconColor = '#3b82f6'; // Blue
$textColor = '#3b82f6';

// Buttons
$buttons = '
    <a href="' . BASE_URL . '" class="btn-primary-custom">
        <i class="bi bi-house-door"></i>
        Kembali ke Beranda
    </a>
    <a href="javascript:history.back()" class="btn-secondary-custom">
        <i class="bi bi-arrow-left"></i>
        Halaman Sebelumnya
    </a>
';

// Load Layout
require_once __DIR__ . '/layout.php';
