<?php

// Base URL Configuration
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$script_name = dirname($_SERVER['SCRIPT_NAME']);
$script_name = str_replace('\\', '/', $script_name); // Normalisasi slash untuk Windows form

// Jika jalankan php -S localhost:8000, script_name biasanya root '/' atau kosong
if ($script_name === '/' || $script_name === '\\') {
    $base_url = $protocol . $host . '/';
} else {
    $base_url = $protocol . $host . $script_name . '/';
}

define('BASE_URL', $base_url);

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'tubes_ca_db');
