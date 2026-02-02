<?php
// Define BASE_URL jika belum terdefined
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/Sistem-Peminjaman-Lab');
}

// Configuration
$code = '500';
$title = 'Terjadi Kesalahan Server';
$message = 'Maaf, terjadi kesalahan internal pada server kami. Tim teknis kami telah diberitahu dan sedang bekerja untuk memperbaikinya.';
$icon = 'bi-hdd-network';
$iconColor = '#dc2626'; // Red
$textColor = '#dc2626';

// Warning Box
$warning = '<strong>Catatan:</strong> Silakan coba muat ulang halaman atau kembali lagi nanti.';

// Buttons
$buttons = '
    <a href="javascript:location.reload()" class="btn-primary-custom">
        <i class="bi bi-arrow-clockwise"></i>
        Muat Ulang Halaman
    </a>
    <a href="' . BASE_URL . '" class="btn-secondary-custom">
        <i class="bi bi-house-door"></i>
        Kembali ke Beranda
    </a>
';

// Load Layout
require_once __DIR__ . '/layout.php';