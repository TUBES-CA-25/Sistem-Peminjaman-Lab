<?php
// Define BASE_URL jika belum terdefined
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/Sistem-Peminjaman-Lab');
}

// Configuration
$code = '401';
$title = 'Akses Tidak Terotorisasi';
$message = 'Anda harus login terlebih dahulu untuk mengakses halaman ini. Silakan login dengan akun Anda atau hubungi administrator jika Anda mengalami masalah.';
$icon = 'bi-person-lock';
$iconColor = '#fbbf24'; // Yellow
$textColor = '#fbbf24';

// Warning Box Content
$warning = '<strong>Informasi:</strong> Pastikan Anda sudah memiliki akun dan menggunakan kredensial yang benar.';

// Buttons
$buttons = '
    <a href="' . BASE_URL . '/auth" class="btn-primary-custom">
        <i class="bi bi-box-arrow-in-right"></i>
        Login Sekarang
    </a>
    <a href="' . BASE_URL . '" class="btn-secondary-custom">
        <i class="bi bi-house-door"></i>
        Kembali ke Beranda
    </a>
';

// Load Layout
require_once __DIR__ . '/layout.php';
