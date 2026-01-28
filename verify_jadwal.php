<?php
// verify_jadwal.php
require_once 'app/config/Config.php';
require_once 'app/core/Database.php';
require_once 'app/models/JadwalModel.php';

$db = new Database();
$jadwalModel = new JadwalModel();

echo "Running Verification for Jadwal Frequency...\n";

// 1. Create with Frequency
$data = [
    'lab_id' => 1, // Using ID 1 as assumption, assuming it exists
    'hari' => 'senin',
    'jam_mulai' => '07:00:00',
    'jam_selesai' => '09:00:00',
    'matakuliah_id' => 1, // Assuming exists
    'kelas_id' => 1, // Assuming exists
    'frekuensi' => 'TEST_FREQ_123'
];

// Delete if exists to clean up potential collisions from previous runs
// A bit hacky but for verification script okay
$db->query("DELETE FROM jadwal WHERE frekuensi = 'TEST_FREQ_123' OR frekuensi = 'TEST_FREQ_UPDATED'");
$db->execute();

$inserted = $jadwalModel->create($data);
echo "Insert Result: " . ($inserted > 0 ? "Success" : "Failed") . "\n";

// 2. Read
// We need to find the ID of the inserted item, or just search by attrib
$db->query("SELECT * FROM jadwal WHERE frekuensi = 'TEST_FREQ_123' LIMIT 1");
$row = $db->single();

if ($row) {
    echo "Read Success. Frequency: " . $row['frekuensi'] . "\n";
    $id = $row['id'];
} else {
    echo "Read Failed.\n";
    exit;
}

// 3. Update
$data['frekuensi'] = 'TEST_FREQ_UPDATED';
$updated = $jadwalModel->update($id, $data);
echo "Update Result: " . ($updated > 0 ? "Success" : "Failed") . "\n";

// 4. Read Again
$rowAfter = $jadwalModel->getById($id);
if ($rowAfter && $rowAfter['frekuensi'] === 'TEST_FREQ_UPDATED') {
    echo "Update Verification Success: " . $rowAfter['frekuensi'] . "\n";
} else {
    echo "Update Verification Failed.\n";
}

// 5. Delete
$deleted = $jadwalModel->delete($id);
echo "Delete Result: " . ($deleted > 0 ? "Success" : "Failed") . "\n";

echo "Verification Complete.\n";
