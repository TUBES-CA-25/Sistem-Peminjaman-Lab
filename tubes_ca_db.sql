-- Database: tubes_ca_db

CREATE DATABASE IF NOT EXISTS tubes_ca_db;

USE tubes_ca_db;

CREATE TABLE IF NOT EXISTS ruangan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_ruangan VARCHAR(100) NOT NULL,
    kapasitas INT NOT NULL,
    lokasi VARCHAR(100) NOT NULL,
    pic VARCHAR(100) NOT NULL,
    email_pic VARCHAR(100) NOT NULL,
    fasilitas TEXT,
    deskripsi TEXT,
    gambar LONGTEXT, -- Stores Base64 images
    status BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table Pengguna
CREATE TABLE IF NOT EXISTS pengguna (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    posisi ENUM('Dosen', 'Asisten') NULL, -- Replaces instansi
    role ENUM('internal', 'eksternal') NOT NULL, -- Simplified options
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Example Admin/User
-- Password default: '12345678' -> Needs hash in real usage, but for manual insert:
-- INSERT INTO pengguna (...) VALUES (...);