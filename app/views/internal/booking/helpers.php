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
            // HANYA tampilkan jika minimal 30 menit
            if (strtotime($slot['mulai']) - strtotime($currentTime) >= 1800) {
                $kosong[] = ['mulai' => $currentTime, 'selesai' => $slot['mulai']];
            }
        }
        
        // Update waktu sekarang ke akhir slot ini
        if ($slot['selesai'] > $currentTime) {
            $currentTime = $slot['selesai'];
        }
    }
    
    // Langkah 4: Cek apakah masih ada waktu tersisa setelah slot terakhir
    if ($currentTime < $jamTutup) {
        // HANYA tampilkan jika minimal 30 menit
        if (strtotime($jamTutup) - strtotime($currentTime) >= 1800) {
            $kosong[] = ['mulai' => $currentTime, 'selesai' => $jamTutup];
        }
    }
    
    return $kosong;
}

/**
 * Gabungkan dan urutkan semua jenis slot (Praktikum, Peminjaman, Kosong)
 * berdasarkan waktu mulai secara kronologis.
 * 
 * @param array $jadwalLab Data praktikum
 * @param array $peminjamanLab Data peminjaman
 * @param array $slotKosong Data slot kosong
 * @return array Array of slot objects sorted by start time
 */
function getSortedSlots($jadwalLab, $peminjamanLab, $slotKosong) {
    $allSlots = [];

    // 1. Praktikum (Jadwal Tetap)
    foreach ($jadwalLab as $j) {
        $isOverridden = false;
        $overriddenBy = '';

        // Cek apakah praktikum ini digeser oleh peminjaman
        foreach ($peminjamanLab as $p) {
            // Jika jam bentrok (StartA < EndB dan EndA > StartB)
            if ($j['jam_mulai'] < $p['jam_selesai'] && $j['jam_selesai'] > $p['jam_mulai']) {
                $isOverridden = true;
                $overriddenBy = $p['peminjam'];
                break;
            }
        }

        $allSlots[] = [
            'type' => $isOverridden ? 'tergeser' : 'praktikum',
            'start' => $j['jam_mulai'],
            'end'   => $j['jam_selesai'],
            'data'  => $j,
            'is_overridden' => $isOverridden,
            'overridden_by' => $overriddenBy
        ];
    }

    // 2. Peminjaman
    foreach ($peminjamanLab as $p) {
        $allSlots[] = [
            'type' => 'peminjaman',
            'start' => $p['jam_mulai'],
            'end'   => $p['jam_selesai'],
            'data'  => $p
        ];
    }

    // 3. Slot Kosong
    foreach ($slotKosong as $k) {
        $allSlots[] = [
            'type' => 'kosong',
            'start' => $k['mulai'],
            'end'   => $k['selesai'],
            'data'  => $k
        ];
    }

    // Sort berdasarkan jam mulai
    usort($allSlots, function($a, $b) {
        // 1. Sort utama berdasarkan jam mulai
        if ($a['start'] !== $b['start']) {
            return strcmp($a['start'], $b['start']);
        }
        
        // 2. Jika jam mulai sama, gunakan prioritas tipe (Ranking)
        $priority = [
            'peminjaman' => 1, // Prioritas tertinggi
            'praktikum'  => 2,
            'tergeser'   => 3, // Di bawah yang menggeser
            'kosong'     => 4  // Paling bawah
        ];
        
        $pA = $priority[$a['type']] ?? 99;
        $pB = $priority[$b['type']] ?? 99;
        
        return $pA - $pB;
    });

    return $allSlots;
}

/**
 * Cek apakah sebuah slot waktu pada tanggal tertentu sudah terlewati
 * dibanding waktu sistem saat ini.
 * 
 * @param string $tanggal Tanggal (Y-m-d)
 * @param string $jamMulais Jam mulai (HH:MM:SS atau HH:MM)
 * @return bool True jika sudah lewat
 */
/**
 * Cek apakah sebuah slot waktu pada tanggal tertentu sudah benar-benar selesai
 * dibanding waktu sistem saat ini.
 * 
 * @param string $tanggal Tanggal (Y-m-d)
 * @param string $jamSelesai Jam selesai slot (HH:MM:SS atau HH:MM)
 * @return bool True jika sudah lewat
 */
function isPastSlot($tanggal, $jamSelesai) {
    $currentDate = date('Y-m-d');
    $currentTime = date('H:i');
    
    // Pastikan jamSelesai hanya HH:MM untuk perbandingan string aman
    $jamSelesai = substr($jamSelesai, 0, 5);

    if ($tanggal < $currentDate) {
        return true;
    } elseif ($tanggal == $currentDate) {
        // HANYA dianggap lewat jika jam SELESAI sudah lewat waktu sekarang
        if ($jamSelesai <= $currentTime) {
            return true;
        }
    }
    
    return false;
}
