<?php
// scripts/drop_ruangan_columns.php
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/Database.php';

try {
    $db = new Database();

    // Check if columns exist before dropping to avoid errors if run multiple times
    // But simplified approach: just try to drop them.

    $query = "ALTER TABLE ruangan 
              DROP COLUMN IF EXISTS lokasi,
              DROP COLUMN IF EXISTS fasilitas,
              DROP COLUMN IF EXISTS status,
              DROP COLUMN IF EXISTS deskripsi";

    $db->query($query);
    $db->execute();

    echo "Columns dropped successfully.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
