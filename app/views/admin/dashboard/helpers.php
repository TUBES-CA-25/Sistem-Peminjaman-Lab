<?php
/**
 * app/views/admin/dashboard/helpers.php
 * 
 * Helper functions untuk admin dashboard view.
 * Functions ini digunakan untuk:
 * - Filter jadwal/peminjaman berdasarkan lab & tanggal
 * - Hitung slot waktu yang tersedia
 * - (SINKRON DENGAN LOGIC INTERNAL)
 */

/**
 * Ambil jadwal tetap (recurring schedules) untuk lab dan hari tertentu
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
 */
function getSlotKosong($jadwalLab, $peminjamanLab, $jamBuka = '07:00', $jamTutup = '18:25') {
    $occupied = [];
    foreach ($jadwalLab as $j) {
        $occupied[] = ['mulai' => $j['jam_mulai'], 'selesai' => $j['jam_selesai']];
    }
    foreach ($peminjamanLab as $p) {
        $occupied[] = ['mulai' => $p['jam_mulai'], 'selesai' => $p['jam_selesai']];
    }
    
    usort($occupied, function($a, $b) {
        return strcmp($a['mulai'], $b['mulai']);
    });
    
    $kosong = [];
    $currentTime = $jamBuka;
    
    foreach ($occupied as $slot) {
        if ($slot['mulai'] > $currentTime) {
            // HANYA tampilkan jika minimal 30 menit
            if (strtotime($slot['mulai']) - strtotime($currentTime) >= 1800) {
                $kosong[] = ['mulai' => $currentTime, 'selesai' => $slot['mulai']];
            }
        }
        if ($slot['selesai'] > $currentTime) {
            $currentTime = $slot['selesai'];
        }
    }
    
    if ($currentTime < $jamTutup) {
        if (strtotime($jamTutup) - strtotime($currentTime) >= 1800) {
            $kosong[] = ['mulai' => $currentTime, 'selesai' => $jamTutup];
        }
    }
    
    return $kosong;
}

/**
 * Gabungkan dan urutkan semua jenis slot (Praktikum, Peminjaman, Kosong)
 * SINKRON DENGAN LOGIC INTERNAL: Praktikum bisa 'tergeser'
 */
function getSortedSlots($jadwalLab, $peminjamanLab, $slotKosong) {
    $allSlots = [];

    // 1. Praktikum (Jadwal Tetap)
    foreach ($jadwalLab as $j) {
        $isOverridden = false;
        $overriddenBy = '';

        foreach ($peminjamanLab as $p) {
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

    // Sort berdasarkan jam mulai + Prioritas
    usort($allSlots, function($a, $b) {
        if ($a['start'] !== $b['start']) {
            return strcmp($a['start'], $b['start']);
        }
        
        $priority = [
            'peminjaman' => 1,
            'praktikum'  => 2,
            'tergeser'   => 3,
            'kosong'     => 4
        ];
        
        $pA = $priority[$a['type']] ?? 99;
        $pB = $priority[$b['type']] ?? 99;
        
        return $pA - $pB;
    });

    return $allSlots;
}

/**
 * Cek apakah sebuah slot waktu pada tanggal tertentu sudah benar-benar selesai
 */
function isPastSlot($tanggal, $jamSelesai) {
    $currentDate = date('Y-m-d');
    $currentTime = date('H:i');
    $jamSelesai = substr($jamSelesai, 0, 5);

    if ($tanggal < $currentDate) {
        return true;
    } elseif ($tanggal == $currentDate) {
        if ($jamSelesai <= $currentTime) {
            return true;
        }
    }
    return false;
}
