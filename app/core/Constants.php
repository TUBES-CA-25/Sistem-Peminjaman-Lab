<?php

// 1. Detect Protocol & Host
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];

// 2. Detect Script Name (Folder Path)
$script_name = dirname($_SERVER['SCRIPT_NAME']);
$script_name = str_replace('\\', '/', $script_name); // Normalisasi Windows

// 3. Bersihkan Slash di Akhir (PENTING)
// Agar tidak terjadi double slash //
$script_name = rtrim($script_name, '/');

// 4. Susun Base URL
$base_url = $protocol . $host . $script_name;

// 5. Pastikan Mengarah ke 'public' (SOLUSI PDF NOT FOUND)
// Jika URL belum mengandung kata '/public', kita tambahkan manual.
if (strpos($base_url, '/public') === false) {
    $base_url .= '/public';
}

// 6. Definisikan Constant
define('BASE_URL', $base_url);

// Database Configuration
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'tubes_ca_db');