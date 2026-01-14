<?php
// app/views/internal/booking/helpers.php
// Helper functions untuk internal booking

/**
 * Get jadwal berdasarkan lab_id dan hari
 */
function getJadwalLab($jadwalTetap, $labId, $hari) {
    $result = [];
    foreach ($jadwalTetap as $j) {
        if ($j['lab_id'] == $labId && $j['hari'] == $hari) {
            $result[] = $j;
        }
    }
    return $result;
}

/**
 * Get peminjaman berdasarkan lab_id dan tanggal
 */
function getPeminjamanLab($peminjaman, $labId, $tanggal) {
    $result = [];
    foreach ($peminjaman as $p) {
        if ($p['lab_id'] == $labId && $p['tanggal'] == $tanggal) {
            $result[] = $p;
        }
    }
    return $result;
}

/**
 * Hitung slot kosong berdasarkan jadwal dan peminjaman
 */
function getSlotKosong($jadwalLab, $peminjamanLab, $jamBuka = '07:00', $jamTutup = '18:25') {
    // Gabungkan semua slot yang terisi
    $occupied = [];
    foreach ($jadwalLab as $j) {
        $occupied[] = ['mulai' => $j['jam_mulai'], 'selesai' => $j['jam_selesai']];
    }
    foreach ($peminjamanLab as $p) {
        $occupied[] = ['mulai' => $p['jam_mulai'], 'selesai' => $p['jam_selesai']];
    }
    
    // Sort by jam_mulai
    usort($occupied, function($a, $b) {
        return strcmp($a['mulai'], $b['mulai']);
    });
    
    // Hitung slot kosong
    $kosong = [];
    $currentTime = $jamBuka;
    
    foreach ($occupied as $slot) {
        if ($slot['mulai'] > $currentTime) {
            $kosong[] = ['mulai' => $currentTime, 'selesai' => $slot['mulai']];
        }
        if ($slot['selesai'] > $currentTime) {
            $currentTime = $slot['selesai'];
        }
    }
    
    // Cek sisa waktu setelah slot terakhir
    if ($currentTime < $jamTutup) {
        $kosong[] = ['mulai' => $currentTime, 'selesai' => $jamTutup];
    }
    
    return $kosong;
}
