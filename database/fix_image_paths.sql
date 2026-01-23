-- Quick Fix: Update existing lab image paths to include 'public/'
-- Run this in phpMyAdmin or MySQL client

UPDATE ruangan
SET
    gambar = CONCAT('/public', gambar)
WHERE
    gambar LIKE '/uploads/labs/%';

-- Verify
SELECT id, nama_ruangan, gambar FROM ruangan;