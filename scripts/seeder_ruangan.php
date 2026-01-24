<?php
/**
 * Seeder: Ruangan + Jurusan + Matakuliah + Kelas + Jadwal (10 each)
 * Usage (from repo root):
 *   php scripts/seeder_ruangan_full.php
 *
 * Script ini idempotent (cek existence sebelum insert).
 */

// Load application bootstrap (init will load config and core classes)
require_once __DIR__ . '/../app/init.php';

$db = new Database();

function out($msg) { echo $msg . PHP_EOL; }

try {
    out("Starting seeder for jurusan, matakuliah, kelas, ruangan, jadwal...");

    // Ensure required tables exist (quick check)
    $required = ['jurusan','matakuliah','kelas','ruangan','jadwal'];
    foreach ($required as $t) {
        $db->query("SHOW TABLES LIKE :t");
        $db->bind('t', $t);
        $db->execute();
        if ($db->rowCount() == 0) {
            throw new Exception("Table '{$t}' does not exist. Please run migrations first.");
        }
    }

    // ----------------------------
    // 1) Jurusan (10)
    // ----------------------------
    $jurusanList = [
        'Teknik Informatika', 'Sistem Informasi', 'Teknik Elektro', 'Teknik Komputer',
        'Matematika', 'Fisika', 'Teknik Mesin', 'Teknik Industri',
        'Ilmu Komputer', 'Teknologi Informasi'
    ];

    $insertedJur = 0;
    foreach ($jurusanList as $name) {
        $db->query("SELECT id FROM jurusan WHERE singkatan = :s");
        // create singkatan (simple)
        $sing = strtoupper(substr($name,0,3));
        $db->bind('s', $sing);
        $db->execute();
        if ($db->rowCount() > 0) {
            out("Skipped jurusan (exists): {$name} ({$sing})");
            continue;
        }
        $db->query("INSERT INTO jurusan (nama_jurusan, singkatan) VALUES (:nama_jurusan, :singkatan)");
        $db->bind('nama_jurusan', $name);
        $db->bind('singkatan', $sing);
        $db->execute();
        if ($db->rowCount() > 0) { $insertedJur++; out("Inserted jurusan: {$name} ({$sing})"); }
    }

    // Helper: get id by value
    function getIdBy($db, $table, $col, $value) {
        $db->query("SELECT id FROM {$table} WHERE {$col} = :v LIMIT 1");
        $db->bind('v', $value);
        $db->execute();
        $r = $db->single();
        return $r ? $r['id'] : null;
    }

    // Grab jurusan ids for assignment
    $db->query("SELECT id FROM jurusan");
    $jurusanRows = $db->resultSet();
    $jurusanIds = array_column($jurusanRows, 'id');
    if (count($jurusanIds) == 0) throw new Exception("No jurusan found after seeding.");

    // ----------------------------
    // 2) Matakuliah (10)
    // ----------------------------
    $insertedMat = 0;
    for ($i=1; $i<=10; $i++) {
        $kode = 'MK' . str_pad($i,3,'0',STR_PAD_LEFT);
        $nama = "Matakuliah {$i}";
        $db->query("SELECT id FROM matakuliah WHERE kode_matakuliah = :kode");
        $db->bind('kode', $kode);
        $db->execute();
        if ($db->rowCount() > 0) { out("Skipped matakuliah (exists): {$kode}"); continue; }
        $jurId = $jurusanIds[array_rand($jurusanIds)];
        $db->query("INSERT INTO matakuliah (nama_matakuliah, kode_matakuliah, singkatan, semester, sks, jurusan_id)
                    VALUES (:nama, :kode, :sing, :semester, :sks, :jurusan_id)");
        $db->bind('nama', $nama);
        $db->bind('kode', $kode);
        $db->bind('sing', 'M' . $i);
        // matakuliah.semester is enum('Ganjil','Genap') in schema
        $semesterLabel = (($i % 2) === 1) ? 'Ganjil' : 'Genap';
        $db->bind('semester', $semesterLabel);
        $db->bind('sks', 3);
        $db->bind('jurusan_id', $jurId);
        $db->execute();
        if ($db->rowCount() > 0) { $insertedMat++; out("Inserted matakuliah: {$kode}"); }
    }

    // ----------------------------
    // 3) Kelas (10)
    // ----------------------------
    $insertedKelas = 0;
    for ($i=1; $i<=10; $i++) {
        $nama_kelas = 'KLS-' . (2000 + $i);
        $db->query("SELECT id FROM kelas WHERE nama_kelas = :nama");
        $db->bind('nama', $nama_kelas);
        $db->execute();
        if ($db->rowCount() > 0) { out("Skipped kelas: {$nama_kelas}"); continue; }
        $jurId = $jurusanIds[array_rand($jurusanIds)];
        $angkatan = 2015 + ($i % 10);
        $db->query("INSERT INTO kelas (nama_kelas, jurusan_id, angkatan) VALUES (:nama_kelas, :jurusan_id, :angkatan)");
        $db->bind('nama_kelas', $nama_kelas);
        $db->bind('jurusan_id', $jurId);
        $db->bind('angkatan', $angkatan);
        $db->execute();
        if ($db->rowCount() > 0) { $insertedKelas++; out("Inserted kelas: {$nama_kelas}"); }
    }

    // ----------------------------
    // 4) Ruangan (10) - use images from public/storage/images
    // ----------------------------
    $imagesDir = __DIR__ . '/../public/storage/images';
    $globPattern = $imagesDir . '/*.{jpg,jpeg,png,gif,webp}';
    $images = glob($globPattern, GLOB_BRACE);
    // Fallback: list files without extension filter
    if (!$images) $images = glob($imagesDir . '/*');

    if (!$images) {
        out("Warning: no images found in public/storage/images. Ruangan will be created without images.");
    } else {
        // convert to relative path stored in DB (existing app expects filename or path)
        foreach ($images as $k => $p) {
            $images[$k] = basename($p);
        }
    }

    $insertedRuang = 0;
    // Inspect ruangan table columns to build compatible insert
    $db->query("DESCRIBE ruangan");
    $cols = $db->resultSet();
    $ruanganCols = array_column($cols, 'Field');

    for ($i=1; $i<=10; $i++) {
        $nama_ruangan = "Lab Seeder {$i}";
        $db->query("SELECT id FROM ruangan WHERE nama_ruangan = :nama");
        $db->bind('nama', $nama_ruangan);
        $db->execute();
        if ($db->rowCount() > 0) { out("Skipped ruangan: {$nama_ruangan}"); continue; }

        $kap = rand(15,40);
        $lok = "Gedung Seeder, Lantai " . rand(1,4);
        $pic = "Asisten Seeder {$i}";
        $email_pic = "asisten{$i}@iclabs.local";
        $fasilitas = "Komputer {$kap} unit, Proyektor";
        $deskripsi = "Deskripsi ruangan seeder {$i}";
        $gambar = $images ? $images[($i-1) % count($images)] : null;
        $status = 1;

        // candidate data keys (some may not exist in table schema)
        $dataRow = [
            'nama_ruangan' => $nama_ruangan,
            'kapasitas' => $kap,
            'lokasi' => $lok,
            'pic' => $pic,
            'email_pic' => $email_pic,
            'fasilitas' => $fasilitas,
            'deskripsi' => $deskripsi,
            'gambar' => $gambar,
            'status' => $status
        ];

        // pick only columns that exist in table
        $toInsertCols = array_values(array_intersect(array_keys($dataRow), $ruanganCols));
        if (empty($toInsertCols)) {
            out("No compatible columns found for ruangan table. Skipping.");
            continue;
        }

        $placeholders = array_map(function($c){ return ':' . $c; }, $toInsertCols);
        $sql = "INSERT INTO ruangan (" . implode(', ', $toInsertCols) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $db->query($sql);
        foreach ($toInsertCols as $col) {
            $db->bind($col, $dataRow[$col]);
        }
        $db->execute();
        if ($db->rowCount() > 0) { $insertedRuang++; out("Inserted ruangan: {$nama_ruangan} (image: {$gambar})"); }
    }

    // ----------------------------
    // 5) Jadwal (10) - random combination
    // ----------------------------
    // fetch ids for ruangan, matakuliah, kelas
    $db->query("SELECT id FROM ruangan");
    $ruRows = $db->resultSet();
    $ruIds = array_column($ruRows, 'id');

    $db->query("SELECT id FROM matakuliah");
    $mRows = $db->resultSet();
    $mIds = array_column($mRows, 'id');

    $db->query("SELECT id FROM kelas");
    $kRows = $db->resultSet();
    $kIds = array_column($kRows, 'id');

    if (empty($ruIds) || empty($mIds) || empty($kIds)) {
        out("Skipping jadwal: missing ruangan/matakuliah/kelas IDs.");
    } else {
        $days = ['senin','selasa','rabu','kamis','jumat','sabtu'];
        $times = [
            ['08:00:00','10:00:00'],
            ['10:00:00','12:00:00'],
            ['13:00:00','15:00:00'],
            ['15:00:00','17:00:00'],
        ];
        $insertedJad = 0;
        $attempt = 0;
        while ($insertedJad < 10 && $attempt < 50) {
            $attempt++;
            $lab = $ruIds[array_rand($ruIds)];
            $mat = $mIds[array_rand($mIds)];
            $kel = $kIds[array_rand($kIds)];
            $hari = $days[array_rand($days)];
            $t = $times[array_rand($times)];
            // check duplicate
            $db->query("SELECT id FROM jadwal WHERE lab_id=:lab AND hari=:hari AND jam_mulai=:jm AND jam_selesai=:js");
            $db->bind('lab', $lab);
            $db->bind('hari', $hari);
            $db->bind('jm', $t[0]);
            $db->bind('js', $t[1]);
            $db->execute();
            if ($db->rowCount() > 0) continue;

            $db->query("INSERT INTO jadwal (lab_id, hari, jam_mulai, jam_selesai, matakuliah_id, kelas_id)
                        VALUES (:lab_id, :hari, :jm, :js, :mat_id, :kelas_id)");
            $db->bind('lab_id', $lab);
            $db->bind('hari', $hari);
            $db->bind('jm', $t[0]);
            $db->bind('js', $t[1]);
            $db->bind('mat_id', $mat);
            $db->bind('kelas_id', $kel);
            $db->execute();
            if ($db->rowCount() > 0) { $insertedJad++; out("Inserted jadwal: lab {$lab} {$hari} {$t[0]}-{$t[1]}"); }
        }
    }

    // Summary
    out(PHP_EOL . "Seeder finished.");
    out("Jurusan inserted/skipped above.");
    out("Matakuliah inserted: {$insertedMat}");
    out("Kelas inserted: {$insertedKelas}");
    out("Ruangan inserted: {$insertedRuang}");
    out("Jadwal inserted: {$insertedJad}");
    out("Done.");

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}