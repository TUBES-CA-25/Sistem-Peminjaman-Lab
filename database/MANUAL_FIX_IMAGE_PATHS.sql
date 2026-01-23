-- =====================================================
-- MANUAL FIX: Update Lab Image Paths
-- =====================================================
-- Copy-paste script ini ke phpMyAdmin (http://localhost/phpmyadmin)
-- 1. Pilih database: tubes_ca_db
-- 2. Klik tab SQL
-- 3. Paste script ini
-- 4. Klik Go
-- =====================================================

-- Step 1: Cek data sekarang
SELECT id, nama_ruangan, gambar
FROM ruangan
WHERE
    gambar IS NOT NULL;

-- Step 2: Update paths yang dimulai dengan /uploads/labs/ menjadi /public/uploads/labs/
UPDATE ruangan
SET
    gambar = CONCAT('/public', gambar)
WHERE
    gambar LIKE '/uploads/labs/%';

-- Step 3: Verify hasil
SELECT id, nama_ruangan, gambar
FROM ruangan
WHERE
    gambar IS NOT NULL;

-- =====================================================
-- Expected Result:
-- Path sebelum: /uploads/labs/lab_xxx.jpeg
-- Path sesudah: /public/uploads/labs/lab_xxx.jpeg
-- =====================================================