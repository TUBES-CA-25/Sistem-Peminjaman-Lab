-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: tubes_ca_db
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;
/*!40101 SET NAMES utf8mb4 */
;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */
;
/*!40103 SET TIME_ZONE='+00:00' */
;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */
;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */
;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */
;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */
;

--
-- Table structure for table `jadwal`
--

DROP TABLE IF EXISTS `jadwal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */
;
/*!40101 SET character_set_client = utf8 */
;
CREATE TABLE `jadwal` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `lab_id` int(11) NOT NULL,
    `hari` enum(
        'senin',
        'selasa',
        'rabu',
        'kamis',
        'jumat',
        'sabtu',
        'minggu'
    ) NOT NULL,
    `jam_mulai` time NOT NULL,
    `jam_selesai` time NOT NULL,
    `matakuliah_id` int(11) NOT NULL,
    `frekuensi` varchar(50) DEFAULT NULL,
    `kelas_id` int(11) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `lab_id` (`lab_id`),
    KEY `matakuliah_id` (`matakuliah_id`),
    KEY `kelas_id` (`kelas_id`),
    CONSTRAINT `jadwal_ibfk_1` FOREIGN KEY (`lab_id`) REFERENCES `ruangan` (`id`) ON DELETE CASCADE,
    CONSTRAINT `jadwal_ibfk_2` FOREIGN KEY (`matakuliah_id`) REFERENCES `matakuliah` (`id`) ON DELETE CASCADE,
    CONSTRAINT `jadwal_ibfk_3` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 4 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */
;

--
-- Table structure for table `jurusan`
--

DROP TABLE IF EXISTS `jurusan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */
;
/*!40101 SET character_set_client = utf8 */
;
CREATE TABLE `jurusan` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `nama_jurusan` varchar(100) NOT NULL,
    `singkatan` varchar(20) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB AUTO_INCREMENT = 6 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */
;

--
-- Table structure for table `kelas`
--

DROP TABLE IF EXISTS `kelas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */
;
/*!40101 SET character_set_client = utf8 */
;
CREATE TABLE `kelas` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `nama_kelas` varchar(50) NOT NULL,
    `jurusan_id` int(11) DEFAULT NULL,
    `angkatan` varchar(4) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `jurusan_id` (`jurusan_id`),
    CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`jurusan_id`) REFERENCES `jurusan` (`id`) ON DELETE SET NULL
) ENGINE = InnoDB AUTO_INCREMENT = 7 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */
;

--
-- Table structure for table `matakuliah`
--

DROP TABLE IF EXISTS `matakuliah`;
/*!40101 SET @saved_cs_client     = @@character_set_client */
;
/*!40101 SET character_set_client = utf8 */
;
CREATE TABLE `matakuliah` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `nama_matakuliah` varchar(100) NOT NULL,
    `kode_matakuliah` varchar(20) NOT NULL,
    `singkatan` varchar(20) DEFAULT NULL,
    `semester` enum('Ganjil', 'Genap') DEFAULT NULL,
    `sks` int(11) DEFAULT NULL,
    `jurusan_id` int(11) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `kode_matakuliah` (`kode_matakuliah`),
    KEY `jurusan_id` (`jurusan_id`),
    CONSTRAINT `matakuliah_ibfk_1` FOREIGN KEY (`jurusan_id`) REFERENCES `jurusan` (`id`) ON DELETE SET NULL
) ENGINE = InnoDB AUTO_INCREMENT = 2 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */
;

--
-- Table structure for table `peminjaman`
--

DROP TABLE IF EXISTS `peminjaman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */
;
/*!40101 SET character_set_client = utf8 */
;
CREATE TABLE `peminjaman` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) DEFAULT NULL,
    `lab_id` int(11) NOT NULL,
    `tanggal_peminjaman` date NOT NULL,
    `jam_mulai` time NOT NULL,
    `jam_selesai` time NOT NULL,
    `nama_peminjam` varchar(100) NOT NULL,
    `kegiatan` text NOT NULL,
    `tipe` varchar(50) NOT NULL,
    `status` varchar(50) DEFAULT 'menunggu',
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    KEY `lab_id` (`lab_id`),
    CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`lab_id`) REFERENCES `ruangan` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 16 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */
;

--
-- Table structure for table `pengajuan_external`
--

DROP TABLE IF EXISTS `pengajuan_external`;
/*!40101 SET @saved_cs_client     = @@character_set_client */
;
/*!40101 SET character_set_client = utf8 */
;
CREATE TABLE `pengajuan_external` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) DEFAULT NULL,
    `nama_lengkap` varchar(100) NOT NULL,
    `email` varchar(100) NOT NULL,
    `telepon` varchar(20) NOT NULL,
    `jumlah_peserta` int(11) NOT NULL,
    `nama_kegiatan` varchar(255) NOT NULL,
    `tgl_mulai` date NOT NULL,
    `tgl_selesai` date NOT NULL,
    `file_proposal` varchar(255) NOT NULL,
    `status` enum(
        'Menunggu Konfirmasi',
        'Menunggu Interview',
        'Disetujui',
        'Ditolak'
    ) DEFAULT 'Menunggu Konfirmasi',
    `alasan_penolakan` text DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `fk_user_external` (`user_id`),
    CONSTRAINT `fk_user_external` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 4 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */
;

--
-- Table structure for table `ruangan`
--

DROP TABLE IF EXISTS `ruangan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */
;
/*!40101 SET character_set_client = utf8 */
;
CREATE TABLE `ruangan` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `nama_ruangan` varchar(100) NOT NULL,
    `kapasitas` int(11) NOT NULL,
    `pic` varchar(100) NOT NULL,
    `email_pic` varchar(100) NOT NULL,
    `gambar` varchar(255) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB AUTO_INCREMENT = 8 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

/*Table structure for table `email_verifications`*/
DROP TABLE IF EXISTS `email_verifications`;
CREATE TABLE `email_verifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `new_email` varchar(100) NOT NULL,
  `token` varchar(6) NOT NULL,
  `expired_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `email_verifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `users`*/
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `nama` varchar(150) NOT NULL,
    `email` varchar(100) NOT NULL,
    `password` varchar(255) NOT NULL,
    `role` enum(
        'admin',
        'internal',
        'external'
    ) NOT NULL DEFAULT 'external',
    `status` varchar(50) DEFAULT 'Mahasiswa',
    `telepon` varchar(20) DEFAULT NULL,
    `foto` varchar(255) DEFAULT NULL,
    `verification_code` varchar(255) DEFAULT NULL,
    `is_verified` tinyint(1) DEFAULT 0,
    `reset_token` varchar(255) DEFAULT NULL,
    `reset_token_expire` datetime DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`)
) ENGINE = InnoDB AUTO_INCREMENT = 6 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
