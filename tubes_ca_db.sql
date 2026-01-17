-- Database: tubes_ca_db

CREATE DATABASE IF NOT EXISTS tubes_ca_db;

USE tubes_ca_db;

-- 1. Table Ruangan
-- Not dropping to preserve room data if possible, but structure might need check
CREATE TABLE IF NOT EXISTS ruangan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_ruangan VARCHAR(100) NOT NULL,
    kapasitas INT NOT NULL,
    lokasi VARCHAR(100) NOT NULL,
    pic VARCHAR(100) NOT NULL,
    email_pic VARCHAR(100) NOT NULL,
    fasilitas TEXT,
    deskripsi TEXT,
    gambar LONGTEXT,
    status VARCHAR(50) DEFAULT 'Tersedia',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Table Pengguna
CREATE TABLE IF NOT EXISTS pengguna (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM(
        'admin',
        'internal',
        'eksternal'
    ) NOT NULL DEFAULT 'internal',
    status VARCHAR(50) DEFAULT 'Mahasiswa',
    nomor_hp VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Table Kelas
DROP TABLE IF EXISTS kelas;

CREATE TABLE IF NOT EXISTS kelas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kelas VARCHAR(50) NOT NULL UNIQUE
);

-- 4. Table Matakuliah
DROP TABLE IF EXISTS matakuliah;

CREATE TABLE IF NOT EXISTS matakuliah (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_matakuliah VARCHAR(100) NOT NULL,
    kode_matakuliah VARCHAR(20) NOT NULL UNIQUE
);

-- 5. Table Jadwal
DROP TABLE IF EXISTS jadwal;

CREATE TABLE IF NOT EXISTS jadwal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lab_id INT NOT NULL,
    hari ENUM(
        'senin',
        'selasa',
        'rabu',
        'kamis',
        'jumat',
        'sabtu',
        'minggu'
    ) NOT NULL,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    matakuliah_id INT NOT NULL,
    kelas_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lab_id) REFERENCES ruangan (id) ON DELETE CASCADE,
    FOREIGN KEY (matakuliah_id) REFERENCES matakuliah (id) ON DELETE CASCADE,
    FOREIGN KEY (kelas_id) REFERENCES kelas (id) ON DELETE CASCADE
);

-- 6. Table Peminjaman
DROP TABLE IF EXISTS peminjaman;

CREATE TABLE IF NOT EXISTS peminjaman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    lab_id INT NOT NULL,
    tanggal_peminjaman DATE NOT NULL,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    nama_peminjam VARCHAR(100) NOT NULL,
    kegiatan TEXT NOT NULL,
    tipe VARCHAR(50) NOT NULL,
    status VARCHAR(50) DEFAULT 'menunggu',
    catatan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES pengguna (id) ON DELETE SET NULL,
    FOREIGN KEY (lab_id) REFERENCES ruangan (id) ON DELETE CASCADE
);

CREATE TABLE pengajuan_external (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telepon VARCHAR(20) NOT NULL,
    jumlah_peserta INT NOT NULL,
    nama_kegiatan VARCHAR(255) NOT NULL,
    tgl_mulai DATE NOT NULL,
    tgl_selesai DATE NOT NULL,
    file_proposal VARCHAR(255) NOT NULL,
    status ENUM('Menunggu Konfirmasi', 'Menunggu Interview', 'Disetujui', 'Ditolak') DEFAULT 'Menunggu Konfirmasi',
    alasan_penolakan TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);