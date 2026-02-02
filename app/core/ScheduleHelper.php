<?php

class ScheduleHelper
{
    /**
     * Ambil data lab dengan format yang sudah ditransformasi
     */
    public function getLabsData($ruanganModel)
    {
        $labs = [];
        foreach ($ruanganModel->getAll() as $ruangan) {
            $labs[] = [
                'id' => $ruangan['id'],
                'name' => $ruangan['nama_ruangan'],
                'short_name' => $ruangan['nama_ruangan'],
                'capacity' => $ruangan['kapasitas'],
                'pic' => $ruangan['pic'],
                'image' => $this->extractImageFilename($ruangan['gambar']),
            ];
        }
        return $labs;
    }

    /**
     * Ambil jadwal yang difilter berdasarkan hari dari tanggal yang dipilih
     */
    public function getFilteredSchedules($jadwalModel, $date)
    {
        $dayName = strtolower($this->getDayName($date));
        $schedules = [];

        foreach ($jadwalModel->getAll() as $jadwal) {
            if (strtolower($jadwal['hari']) === $dayName) {
                $schedules[] = [
                    'lab_id' => $jadwal['lab_id'],
                    'hari' => ucfirst($dayName),
                    'jam_mulai' => substr($jadwal['jam_mulai'], 0, 5),
                    'jam_selesai' => substr($jadwal['jam_selesai'], 0, 5),
                    'kelas' => $jadwal['nama_kelas'],
                    'matkul' => $jadwal['nama_matakuliah']
                ];
            }
        }
        return $schedules;
    }

    /**
     * Ambil peminjaman dalam rentang tanggal (±7 hari)
     */
    public function getBookingsInRange($peminjamanModel, $selectedDate)
    {
        $startDate = date('Y-m-d', strtotime($selectedDate . ' -7 days'));
        $endDate = date('Y-m-d', strtotime($selectedDate . ' +7 days'));

        $bookings = [];

        foreach ($peminjamanModel->getAll() as $peminjaman) {
            if (!isset($peminjaman['tanggal']) || !isset($peminjaman['lab_id'])) {
                continue;
            }

            $bookingDate = $peminjaman['tanggal'];
            $isApproved = ($peminjaman['status'] ?? '') === 'disetujui';
            $isInRange = $bookingDate >= $startDate && $bookingDate <= $endDate;

            if ($isApproved && $isInRange) {
                $bookings[] = [
                    'lab_id' => $peminjaman['lab_id'],
                    'tanggal' => $bookingDate,
                    'jam_mulai' => substr($peminjaman['jam_mulai'] ?? '00:00:00', 0, 5),
                    'jam_selesai' => substr($peminjaman['jam_selesai'] ?? '00:00:00', 0, 5),
                    'type' => $peminjaman['tipe'] ?? 'internal',
                    'keterangan' => $peminjaman['kegiatan'] ?? '',
                    'peminjam' => $peminjaman['nama_peminjam'] ?? 'Unknown'
                ];
            }
        }
        return $bookings;
    }

    /**
     * Cek konflik dengan jadwal tetap
     */
    public function checkScheduleConflict($jadwalModel, $labId, $date, $startTime, $endTime)
    {
        $dayName = $this->getDayName($date);
        $schedules = $jadwalModel->getByLabAndDay($labId, strtolower($dayName));

        foreach ($schedules as $jadwal) {
            $jadwalStart = substr($jadwal['jam_mulai'], 0, 5);
            $jadwalEnd = substr($jadwal['jam_selesai'], 0, 5);

            if ($startTime < $jadwalEnd && $endTime > $jadwalStart) {
                $matkul = $jadwal['nama_matakuliah'] ?? 'Praktikum';
                return "Bentrok dengan jadwal praktikum: {$matkul} ({$jadwalStart}-{$jadwalEnd})";
            }
        }
        return null;
    }

    /**
     * Validasi input form booking
     */
    public function validateBookingInput($data)
    {
        if (
            empty($data['tanggal']) || empty($data['labName']) ||
            empty($data['jamMulai']) || empty($data['jamSelesai'])
        ) {
            return ['valid' => false, 'message' => 'Data tidak lengkap'];
        }

        if (empty($data['namaPeminjam']) || empty($data['namaKegiatan'])) {
            return ['valid' => false, 'message' => 'Nama peminjam dan nama kegiatan wajib diisi'];
        }

        return ['valid' => true, 'message' => ''];
    }

    /**
     * Ekstrak nama file gambar
     */
    public function extractImageFilename($gambar)
    {
        if (empty($gambar))
            return 'StartUp.jpg';
        if (strpos($gambar, 'data:image') === 0)
            return 'StartUp.jpg';
        if (strpos($gambar, '/public/') === 0 || strpos($gambar, 'public/') === 0 || strpos($gambar, 'storage/') === 0)
            return $gambar;
        if (strpos($gambar, '/') === false && strpos($gambar, '\\') === false)
            return $gambar;
        return basename($gambar);
    }

    /**
     * Dapatkan nama hari dalam bahasa Indonesia
     */
    public function getDayName($date)
    {
        $days = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        $dayEnglish = date('l', strtotime($date));
        return $days[$dayEnglish] ?? $dayEnglish;
    }
}
