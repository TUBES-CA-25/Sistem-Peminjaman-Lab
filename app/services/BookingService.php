<?php

/**
 * BookingService
 * 
 * Service untuk menangani logika bisnis peminjaman laboratorium.
 * Termasuk validasi jadwal, pengecekan konflik, dan operasi CRUD peminjaman.
 * 
 * @author System
 * @version 1.0
 */
class BookingService
{
    private $ruanganModel;
    private $jadwalModel;
    private $peminjamanModel;

    public function __construct()
    {
        // Load Models manually since Service is not a Controller
        if (!class_exists('RuanganModel')) {
            require_once __DIR__ . '/../models/RuanganModel.php';
        }
        if (!class_exists('JadwalModel')) {
            require_once __DIR__ . '/../models/JadwalModel.php';
        }
        if (!class_exists('PeminjamanModel')) {
            require_once __DIR__ . '/../models/PeminjamanModel.php';
        }

        $this->ruanganModel = new RuanganModel();
        $this->jadwalModel = new JadwalModel();
        $this->peminjamanModel = new PeminjamanModel();
    }

    /**
     * Helper untuk mengambil data jadwal yang umum digunakan
     */
    public function getCommonScheduleData($activePage)
    {
        $selectedDate = $_GET['date'] ?? date('Y-m-d');

        return [
            'active_page' => $activePage,
            'selected_date' => $selectedDate,
            'selected_day' => $this->getDayName($selectedDate),
            'labs' => $this->getLabsData(),
            'jadwal_tetap' => $this->getFilteredSchedules($selectedDate),
            'peminjaman' => $this->getBookingsInRange($selectedDate)
        ];
    }

    /**
     * Ambil data lab dengan transformasi format untuk frontend
     */
    public function getLabsData()
    {
        $labs = [];
        foreach ($this->ruanganModel->getAll() as $ruangan) {
            $labs[] = [
                'id' => $ruangan['id'],
                'name' => $ruangan['nama_ruangan'],
                'short_name' => $ruangan['nama_ruangan'],
                'capacity' => $ruangan['kapasitas'],
                'pic' => $ruangan['pic'],
                'image' => $this->extractImageFilename($ruangan['gambar']),
                'email_pic' => $ruangan['email_pic'] ?? null
            ];
        }
        return $labs;
    }

    /**
     * Ambil jadwal tetap yang difilter hari
     */
    public function getFilteredSchedules($date)
    {
        $dayName = strtolower($this->getDayName($date));
        $schedules = [];

        foreach ($this->jadwalModel->getAll() as $jadwal) {
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
     * Ambil peminjaman approved dalam rentang waktu
     */
    public function getBookingsInRange($selectedDate)
    {
        $startDate = date('Y-m-d', strtotime($selectedDate . ' -7 days'));
        $endDate = date('Y-m-d', strtotime($selectedDate . ' +7 days'));
        $bookings = [];

        foreach ($this->peminjamanModel->getAll() as $peminjaman) {
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
     * Validasi input booking form
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
     * Get Lab ID by Name
     */
    public function getLabIdByName($labName)
    {
        // Optimization: Could use a specific query in Model, but keeping logic same as before
        $labs = $this->ruanganModel->getAll();
        foreach ($labs as $lab) {
            if ($lab['nama_ruangan'] === $labName) {
                return $lab['id'];
            }
        }
        return null;
    }

    /**
     * Cek konflik dengan jadwal tetap
     */
    public function checkScheduleConflict($labId, $date, $startTime, $endTime)
    {
        $dayName = $this->getDayName($date);
        $schedules = $this->jadwalModel->getByLabAndDay($labId, strtolower($dayName));

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
     * Check if booking conflict exists (Wrapper for PeminjamanModel)
     */
    public function checkBookingConflict($labId, $date, $startTime, $endTime)
    {
        return $this->peminjamanModel->checkConflict($labId, $date, $startTime, $endTime);
    }

    /**
     * Create Booking
     */
    public function createBooking($data)
    {
        return $this->peminjamanModel->create($data);
    }

    /**
     * Get Room Info
     * @return array|mixed
     */
    public function getLabById($id)
    {
        return $this->ruanganModel->getById($id);
    }

    // --- CRUD WRAPPERS FOR HISTORY ---

    public function getBookingByUserId($userId)
    {
        return $this->peminjamanModel->getByUserId($userId);
    }

    public function getBookingById($id)
    {
        return $this->peminjamanModel->getById($id);
    }

    public function updateBooking($id, $data)
    {
        return $this->peminjamanModel->update($id, $data);
    }

    public function deleteBooking($id)
    {
        return $this->peminjamanModel->delete($id);
    }

    // --- Helpers ---

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

    private function extractImageFilename($gambar)
    {
        if (empty($gambar))
            return 'StartUp.jpg';
        if (strpos($gambar, 'data:image') === 0)
            return 'StartUp.jpg';
        if (strpos($gambar, '/public/') === 0 || strpos($gambar, 'public/') === 0 || strpos($gambar, 'storage/') === 0) {
            return $gambar;
        }
        if (strpos($gambar, '/') === false && strpos($gambar, '\\') === false) {
            return $gambar;
        }
        return basename($gambar);
    }
}
