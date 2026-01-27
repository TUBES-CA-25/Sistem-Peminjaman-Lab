<?php

/**
 * Load Environment Variables from .env file
 */
function loadEnv($path)
{
    if (!file_exists($path)) {
        die('❌ ERROR: .env file not found at: ' . $path);
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Parse KEY=VALUE
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            // Remove quotes if present
            $value = trim($value, '"\'');

            // Set environment variable
            putenv("$key=$value");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}

// Load .env file from ROOT directory
$envPath = __DIR__ . '/../../.env';
loadEnv($envPath);

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'tubes_ca_db');

// BASE URL Configuration
define('BASE_URL', 'http://localhost/Sistem-Peminjaman-Lab');

// Email Configuration Validation
$requiredEnvVars = ['SMTP_HOST', 'SMTP_PORT', 'SMTP_USERNAME', 'SMTP_PASSWORD', 'SMTP_FROM_EMAIL', 'SMTP_FROM_NAME'];
$missingVars = [];

foreach ($requiredEnvVars as $var) {
    if (!getenv($var)) {
        $missingVars[] = $var;
    }
}

if (!empty($missingVars)) {
    die('❌ ERROR: Missing required environment variables in .env file: ' . implode(', ', $missingVars));
}