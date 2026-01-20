<?php
/**
 * app/views/internal/booking/helpers.php
 * 
 * Helper functions untuk internal booking view.
 * Functions ini digunakan untuk:
 * - Filter jadwal/peminjaman berdasarkan lab & tanggal
 * - Hitung slot waktu yang tersedia
 */

/**
 * Ambil jadwal tetap (recurring schedules) untuk lab dan hari tertentu
 * 
 * Contoh use case: Ambil semua jadwal praktikum di Lab A2 pada hari Senin
 * 
 * @param array $jadwalTetap Semua jadwal tetap dari database
 * @param int $labId ID lab yang akan difilter
 * @param string $hari Nama hari dalam bahasa Indonesia (misal: "Senin", "Selasa")
 * @return array Array berisi jadwal yang sesuai kriteria
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
 * Ambil peminjaman (bookings) untuk lab dan tanggal tertentu
 * 
 * Contoh use case: Ambil semua booking di Lab B pada tanggal 2026-01-19
 * 
 * @param array $peminjaman Semua peminjaman (yang sudah approved) dari database
 * @param int $labId ID lab yang akan difilter
 * @param string $tanggal Tanggal dalam format Y-m-d
 * @return array Array berisi peminjaman yang sesuai kriteria
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
 * Hitung slot waktu yang tersedia untuk sebuah lab
 * 
 * Algoritma:
 * 1. Gabungkan semua jadwal tetap + peminjaman jadi slot "terisi"
 * 2. Sort berdasarkan waktu mulai
 * 3. Cari celah (gap) di antara slot yang terisi
 * 4. Return celah sebagai slot yang tersedia
 * 
 * Contoh:
 * Lab buka: 07:00-18:25
 * Terisi: 08:00-10:00, 13:00-15:00
 * Hasil: [07:00-08:00], [10:00-13:00], [15:00-18:25]
 * 
 * @param array $jadwalLab Jadwal tetap untuk lab ini hari ini
 * @param array $peminjamanLab Bookings untuk lab ini hari ini
 * @param string $jamBuka Jam buka lab (default: 07:00)
 * @param string $jamTutup Jam tutup lab (default: 18:25)
 * @return array Array berisi slot tersedia ['mulai' => HH:MM, 'selesai' => HH:MM]
 */
function getSlotKosong($jadwalLab, $peminjamanLab, $jamBuka = '07:00', $jamTutup = '18:25') {
    // Langkah 1: Gabungkan semua slot yang terisi (jadwal + peminjaman)
    $occupied = [];
    
    foreach ($jadwalLab as $j) {
        $occupied[] = ['mulai' => $j['jam_mulai'], 'selesai' => $j['jam_selesai']];
    }
    
    foreach ($peminjamanLab as $p) {
        $occupied[] = ['mulai' => $p['jam_mulai'], 'selesai' => $p['jam_selesai']];
    }
    
    // Langkah 2: Sort slot yang terisi berdasarkan waktu mulai
    usort($occupied, function($a, $b) {
        return strcmp($a['mulai'], $b['mulai']);
    });
    
    // Langkah 3: Cari celah di antara slot yang terisi
    $kosong = [];
    $currentTime = $jamBuka;  // Track akhir dari slot terisi terakhir
    
    foreach ($occupied as $slot) {
        // Jika ada celah sebelum slot ini mulai
        if ($slot['mulai'] > $currentTime) {
            $kosong[] = ['mulai' => $currentTime, 'selesai' => $slot['mulai']];
        }
        
        // Update waktu sekarang ke akhir slot ini
        if ($slot['selesai'] > $currentTime) {
            $currentTime = $slot['selesai'];
        }
    }
    
    // Langkah 4: Cek apakah masih ada waktu tersisa setelah slot terakhir
    if ($currentTime < $jamTutup) {
        $kosong[] = ['mulai' => $currentTime, 'selesai' => $jamTutup];
    }
    
    return $kosong;
}
