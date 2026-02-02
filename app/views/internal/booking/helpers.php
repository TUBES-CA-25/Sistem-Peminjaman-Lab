<?php

function getJadwalLab($jadwalTetap, $labId, $hari)
{
    $result = [];

    foreach ($jadwalTetap as $j) {
        if ($j['lab_id'] == $labId && $j['hari'] == $hari) {
            $result[] = $j;
        }
    }

    return $result;
}


function getPeminjamanLab($peminjaman, $labId, $tanggal)
{
    $result = [];

    foreach ($peminjaman as $p) {
        if ($p['lab_id'] == $labId && $p['tanggal'] == $tanggal) {
            $result[] = $p;
        }
    }

    return $result;
}


function getSlotKosong($jadwalLab, $peminjamanLab, $jamBuka = '07:00', $jamTutup = '18:20')
{
    // Langkah 1: Gabungkan semua slot yang terisi (jadwal + peminjaman)
    $occupied = [];

    foreach ($jadwalLab as $j) {
        $occupied[] = ['mulai' => $j['jam_mulai'], 'selesai' => $j['jam_selesai']];
    }

    foreach ($peminjamanLab as $p) {
        $occupied[] = ['mulai' => $p['jam_mulai'], 'selesai' => $p['jam_selesai']];
    }

    // Langkah 2: Sort slot yang terisi berdasarkan waktu mulai
    usort($occupied, function ($a, $b) {
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


function getSortedSlots($jadwalLab, $peminjamanLab, $slotKosong)
{
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
            'end' => $j['jam_selesai'],
            'data' => $j,
            'is_overridden' => $isOverridden,
            'overridden_by' => $overriddenBy
        ];
    }

    // 2. Peminjaman
    foreach ($peminjamanLab as $p) {
        $allSlots[] = [
            'type' => 'peminjaman',
            'start' => $p['jam_mulai'],
            'end' => $p['jam_selesai'],
            'data' => $p
        ];
    }

    // 3. Slot Kosong
    foreach ($slotKosong as $k) {
        $allSlots[] = [
            'type' => 'kosong',
            'start' => $k['mulai'],
            'end' => $k['selesai'],
            'data' => $k
        ];
    }

    // Sort berdasarkan jam mulai
    usort($allSlots, function ($a, $b) {
        // 1. Sort utama berdasarkan jam mulai
        if ($a['start'] !== $b['start']) {
            return strcmp($a['start'], $b['start']);
        }


        $priority = [
            'peminjaman' => 1,
            'praktikum' => 2,
            'tergeser' => 3,
            'kosong' => 4
        ];

        $pA = $priority[$a['type']] ?? 99;
        $pB = $priority[$b['type']] ?? 99;

        return $pA - $pB;
    });

    return $allSlots;
}


function isPastSlot($tanggal, $jamSelesai)
{
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
