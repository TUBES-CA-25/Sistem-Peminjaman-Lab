-- 1. MATIKAN SAFETY CHECK (Agar bisa hapus tabel tanpa error #1451)
SET FOREIGN_KEY_CHECKS = 0;

-- 2. HAPUS SEMUA TABEL LAMA (BERSIH-BERSIH)
DROP TABLE IF EXISTS pengajuan_external;
DROP TABLE IF EXISTS peminjaman;
DROP TABLE IF EXISTS jadwal;
DROP TABLE IF EXISTS matakuliah;
DROP TABLE IF EXISTS kelas;
DROP TABLE IF EXISTS users;   -- Tabel ini yang bermasalah tadi
DROP TABLE IF EXISTS ruangan;
DROP TABLE IF EXISTS pengguna; -- Hapus juga jika ada tabel sisa bernama pengguna

-- 3. BUAT ULANG TABEL DENGAN STRUKTUR YANG BENAR

-- A. Table Ruangan
CREATE TABLE ruangan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_ruangan VARCHAR(100) NOT NULL,
    kapasitas INT NOT NULL,
    lokasi VARCHAR(100) NOT NULL,
    pic VARCHAR(100) NOT NULL,
    email_pic VARCHAR(100) NOT NULL,
    fasilitas TEXT,
    deskripsi TEXT,
    gambar VARCHAR(255),
    status VARCHAR(50) DEFAULT 'Tersedia',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- B. Table Users (PENTING: Kolom sudah disesuaikan dengan kodingan PHP)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'internal', 'external') NOT NULL DEFAULT 'external',
    status VARCHAR(50) DEFAULT 'Mahasiswa',
    telepon VARCHAR(20), 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Table Jurusan
CREATE TABLE IF NOT EXISTS jurusan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_jurusan VARCHAR(100) NOT NULL,
    singkatan VARCHAR(20) NOT NULL
);

-- 4. Table Tahun Ajaran
CREATE TABLE IF NOT EXISTS tahun_ajaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(50) NOT NULL,
    status ENUM('Aktif', 'Tidak Aktif') DEFAULT 'Tidak Aktif'
);

-- 5. Table Kelas
DROP TABLE IF EXISTS kelas;

CREATE TABLE IF NOT EXISTS kelas (
-- C. Table Kelas
CREATE TABLE kelas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kelas VARCHAR(50) NOT NULL UNIQUE,
    jurusan_id INT NULL,
    angkatan VARCHAR(4) NULL,
    FOREIGN KEY (jurusan_id) REFERENCES jurusan (id) ON DELETE SET NULL
);

-- 6. Table Matakuliah
DROP TABLE IF EXISTS matakuliah;

CREATE TABLE IF NOT EXISTS matakuliah (
-- D. Table Matakuliah
CREATE TABLE matakuliah (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_matakuliah VARCHAR(100) NOT NULL,
    kode_matakuliah VARCHAR(20) NOT NULL UNIQUE,
    singkatan VARCHAR(20) NULL,
    semester ENUM('Ganjil', 'Genap') NULL,
    sks INT NULL,
    jurusan_id INT NULL,
    FOREIGN KEY (jurusan_id) REFERENCES jurusan (id) ON DELETE SET NULL
);

-- 7. Table Jadwal
DROP TABLE IF EXISTS jadwal;

CREATE TABLE IF NOT EXISTS jadwal (
-- E. Table Jadwal
CREATE TABLE jadwal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lab_id INT NOT NULL,
    hari ENUM('senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu') NOT NULL,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    matakuliah_id INT NOT NULL,
    kelas_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lab_id) REFERENCES ruangan (id) ON DELETE CASCADE,
    FOREIGN KEY (matakuliah_id) REFERENCES matakuliah (id) ON DELETE CASCADE,
    FOREIGN KEY (kelas_id) REFERENCES kelas (id) ON DELETE CASCADE
);

-- 8. Table Peminjaman
DROP TABLE IF EXISTS peminjaman;

CREATE TABLE IF NOT EXISTS peminjaman (
-- F. Table Peminjaman
CREATE TABLE peminjaman (
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
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL,
    FOREIGN KEY (lab_id) REFERENCES ruangan (id) ON DELETE CASCADE
);

-- G. Table Pengajuan External
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
    status ENUM('Menunggu Konfirmasi','Menunggu Interview','Disetujui','Ditolak') DEFAULT 'Menunggu Konfirmasi',
    alasan_penolakan TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_user_external FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
);

-- 4. NYALAKAN LAGI SAFETY CHECK
SET FOREIGN_KEY_CHECKS = 1;