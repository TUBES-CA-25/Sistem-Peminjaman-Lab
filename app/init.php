<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1. Muat Konfigurasi / Konstanta DULUAN
// Pastikan DB_HOST, DB_USER, DB_PASS, DB_NAME ada di salah satu file ini.
// Jika Anda menyimpannya di config/config.php, aktifkan baris ini:
//require_once 'config/config.php'; 

// Jika Anda menyimpannya di core/Constants.php (seperti kode lama Anda), biarkan ini:
require_once 'config/Config.php';


// 2. Muat Inti Aplikasi (Core)
require_once 'core/App.php';
require_once 'core/Controller.php';

// --- PERBAIKAN UTAMA ADA DISINI ---
// Ubah dari 'config/Database.php' menjadi 'core/Database.php'
require_once 'core/Database.php';
require_once 'core/Flasher.php';
require_once 'core/Mailer.php';
require_once 'core/ScheduleHelper.php';

// autoload Composer
require_once __DIR__ . '/../vendor/autoload.php';