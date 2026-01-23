<?php
// scripts/drop_kelas_unique.php
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/Database.php';

try {
    $db = new Database();

    // Drop the unique index
    // Note: The index name is usually the column name if generic, or specifically named. 
    // In the previous dump it was UNIQUE KEY `nama_kelas` (`nama_kelas`)
    $query = "ALTER TABLE kelas DROP INDEX nama_kelas";

    $db->query($query);
    $db->execute();

    echo "Unique constraint on 'nama_kelas' dropped successfully.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
