<?php
/**
 * Database Seeder - Ruangan
 * Populate initial data for lab rooms
 * Access: http://localhost:8000/seeder.php
 */

require_once 'app/core/Constants.php';
require_once 'app/core/Database.php';

$db = new Database();

echo "<!DOCTYPE html>
<html>
<head>
    <title>Database Seeder - Ruangan</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 50px auto; padding: 20px; }
        .success { color: green; padding: 10px; background: #d4edda; border-radius: 5px; margin: 10px 0; }
        .error { color: red; padding: 10px; background: #f8d7da; border-radius: 5px; margin: 10px 0; }
        h1 { color: #333; }
        .lab-item { padding: 8px; background: #f8f9fa; margin: 5px 0; border-left: 3px solid #007bff; }
    </style>
</head>
<body>
    <h1>🌱 Database Seeder - Ruangan</h1>";

try {
    // Check if table exists using new wrapper
    $db->query("SHOW TABLES LIKE 'ruangan'");
    $db->execute();
    if ($db->rowCount() == 0) {
        throw new Exception("Table 'ruangan' does not exist! Please run setup.php first.");
    }

    // Lab data - 8 labs
    $labs = [
        [
            'nama_ruangan' => 'Lab Start Up',
            'kapasitas' => 30,
            'lokasi' => 'Gedung F, Lantai 3',
            'pic' => 'Dr. Budi Santoso',
            'email_pic' => 'budi.santoso@iclabs.ac.id',
            'fasilitas' => 'Komputer 30 unit, Proyektor, AC, Whiteboard',
            'deskripsi' => 'Lab Start Up dengan fasilitas lengkap',
            'gambar' => 'StartUp.jpg',
            'status' => 1
        ],
        [
            'nama_ruangan' => 'Lab Internet of Things',
            'kapasitas' => 25,
            'lokasi' => 'Gedung E, Lantai 1',
            'pic' => 'Dr. Budi Santoso',
            'email_pic' => 'budi.santoso@iclabs.ac.id',
            'fasilitas' => 'Komputer 25 unit, IoT Devices, Arduino Kit',
            'deskripsi' => 'Lab khusus untuk Internet of Things',
            'gambar' => 'IoT.jpg',
            'status' => 1
        ],
        [
            'nama_ruangan' => 'Lab Multimedia',
            'kapasitas' => 28,
            'lokasi' => 'Gedung F, Lantai 2',
            'pic' => 'Dr. Budi Santoso',
            'email_pic' => 'budi.santoso@iclabs.ac.id',
            'fasilitas' => 'Komputer High-End 28 unit, Wacom Tablet',
            'deskripsi' => 'Lab Multimedia untuk desain grafis',
            'gambar' => 'Mulmed.jpg',
            'status' => 1
        ],
        [
            'nama_ruangan' => 'Lab Computer Networking',
            'kapasitas' => 30,
            'lokasi' => 'Gedung F, Lantai 3',
            'pic' => 'Dr. Budi Santoso',
            'email_pic' => 'budi.santoso@iclabs.ac.id',
            'fasilitas' => 'Komputer 30 unit, Cisco Router, Switch',
            'deskripsi' => 'Lab Networking dengan perangkat lengkap',
            'gambar' => 'comnet.png',
            'status' => 1
        ],
        [
            'nama_ruangan' => 'Lab Data Science',
            'kapasitas' => 32,
            'lokasi' => 'Gedung F, Lantai 1',
            'pic' => 'Dr. Budi Santoso',
            'email_pic' => 'budi.santoso@iclabs.ac.id',
            'fasilitas' => 'Komputer 32 unit, GPU Server',
            'deskripsi' => 'Lab Data Science dengan server GPU',
            'gambar' => 'DS.jpg',
            'status' => 1
        ],
        [
            'nama_ruangan' => 'Lab Computer Vision',
            'kapasitas' => 20,
            'lokasi' => 'Gedung F, Lantai 2',
            'pic' => 'Dr. Budi Santoso',
            'email_pic' => 'budi.santoso@iclabs.ac.id',
            'fasilitas' => 'Komputer 20 unit, Camera Equipment',
            'deskripsi' => 'Lab Computer Vision',
            'gambar' => 'CV.jpg',
            'status' => 1
        ],
        [
            'nama_ruangan' => 'Lab Microcontroller',
            'kapasitas' => 25,
            'lokasi' => 'Gedung E, Lantai 2',
            'pic' => 'Dr. Budi Santoso',
            'email_pic' => 'budi.santoso@iclabs.ac.id',
            'fasilitas' => 'Komputer 25 unit, Microcontroller Kit',
            'deskripsi' => 'Lab Microcontroller untuk embedded systems',
            'gambar' => 'Micro.jpg',
            'status' => 1
        ],
        [
            'nama_ruangan' => 'Riset 2',
            'kapasitas' => 28,
            'lokasi' => 'Gedung F, Lantai 3',
            'pic' => 'Dr. Budi Santoso',
            'email_pic' => 'budi.santoso@iclabs.ac.id',
            'fasilitas' => 'Komputer 28 unit, Research Tools',
            'deskripsi' => 'Laboratorium riset',
            'gambar' => 'Riset.jpg',
            'status' => 1
        ]
    ];

    echo "<h2>Inserting Lab Data...</h2>";
    
    $inserted = 0;
    $skipped = 0;
    
    foreach ($labs as $lab) {
        // Check if lab already exists using wrapper
        $db->query("SELECT id FROM ruangan WHERE nama_ruangan = :nama");
        $db->bind('nama', $lab['nama_ruangan']);
        $db->execute();
        
        if ($db->rowCount() > 0) {
            echo "<div class='lab-item'>⚠️ Skipped: {$lab['nama_ruangan']} (already exists)</div>";
            $skipped++;
            continue;
        }
        
        // Insert lab using wrapper
        $sql = "INSERT INTO ruangan (nama_ruangan, kapasitas, lokasi, pic, email_pic, fasilitas, deskripsi, gambar, status) 
                VALUES (:nama, :kapasitas, :lokasi, :pic, :email, :fasilitas, :deskripsi, :gambar, :status)";
        
        $db->query($sql);
        $db->bind('nama', $lab['nama_ruangan']);
        $db->bind('kapasitas', $lab['kapasitas']);
        $db->bind('lokasi', $lab['lokasi']);
        $db->bind('pic', $lab['pic']);
        $db->bind('email', $lab['email_pic']);
        $db->bind('fasilitas', $lab['fasilitas']);
        $db->bind('deskripsi', $lab['deskripsi']);
        $db->bind('gambar', $lab['gambar']);
        $db->bind('status', $lab['status']);
        $db->execute();
        
        if ($db->rowCount() > 0) {
            echo "<div class='lab-item'>✓ Inserted: {$lab['nama_ruangan']}</div>";
            $inserted++;
        }
    }
    
    echo "<div class='success'>
        <h3>✅ Seeding Complete!</h3>
        <strong>Summary:</strong><br>
        ✓ Inserted: {$inserted} labs<br>
        ⚠️ Skipped: {$skipped} labs<br><br>
        <a href='http://localhost:8000/internal/booking' style='color: blue;'>→ Internal Booking</a><br>
        <a href='http://localhost:8000/auth/login' style='color: blue;'>→ Admin Login</a>
    </div>";
    
} catch (Exception $e) {
    echo "<div class='error'><strong>Error:</strong> " . $e->getMessage() . "</div>";
}

echo "</body></html>";
?>
