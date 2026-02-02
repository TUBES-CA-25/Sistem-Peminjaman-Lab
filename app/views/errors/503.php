<?php
// Define BASE_URL jika belum terdefined
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/Sistem-Peminjaman-Lab');
}

// Configuration
$code = '503';
$title = 'Layanan Tidak Tersedia';
$message = 'Maaf, sistem sedang dalam pemeliharaan (maintenance) atau mengalami beban tinggi. Silakan coba beberapa saat lagi.';
$icon = 'bi-cone-striped';
$iconColor = '#f59e0b'; // Orange
$textColor = '#f59e0b';

// Warning Box
$warning = '<strong>Maintenance:</strong> Kami sedang melakukan pembaruan sistem untuk meningkatkan layanan.';

// Buttons
$buttons = '
    <a href="javascript:location.reload()" class="btn-primary-custom">
        <i class="bi bi-arrow-clockwise"></i>
        Coba Lagi
    </a>
';

// Load Layout
require_once __DIR__ . '/layout.php';