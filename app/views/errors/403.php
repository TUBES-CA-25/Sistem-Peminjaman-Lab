<?php
// Define BASE_URL jika belum terdefined
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/Sistem-Peminjaman-Lab');
}

// Configuration
$code = '403';
$title = 'Akses Ditolak';
$message = 'Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. Halaman ini hanya dapat diakses oleh pengguna dengan role tertentu.';
$icon = 'bi-shield-lock';
$iconColor = '#ef4444'; // Red
$textColor = '#ef4444';

// Warning Box Content
$warning = '<strong>Perhatian:</strong> Jika Anda merasa ini adalah kesalahan, silakan hubungi administrator sistem.';

// Buttons
$buttons = '
    <a href="' . BASE_URL . '" class="btn-secondary-custom">
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
