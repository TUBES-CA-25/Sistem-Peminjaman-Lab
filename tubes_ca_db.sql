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

-- Example Data