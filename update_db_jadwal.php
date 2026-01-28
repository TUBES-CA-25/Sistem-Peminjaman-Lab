<?php
// update_db_jadwal.php
require_once 'app/config/Config.php';
require_once 'app/core/Database.php';

$db = new Database();

try {
    // Check if column exists
    $db->query("SHOW COLUMNS FROM jadwal LIKE 'frekuensi'");
    $result = $db->single();

    if ($result) {
        echo "Column 'frekuensi' already exists.\n";
    } else {
        // Add column
        $db->query("ALTER TABLE jadwal ADD COLUMN frekuensi VARCHAR(50) DEFAULT NULL AFTER matakuliah_id");
        $db->execute();
        echo "Success: Column 'frekuensi' added to 'jadwal' table.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
