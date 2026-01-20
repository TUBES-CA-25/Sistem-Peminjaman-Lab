<?php

/**
 * Internal Controller
 * 
 * Menghandle fitur booking laboratorium untuk user internal.
 * User internal bisa booking lab langsung tanpa perlu approval admin.
 * 
 * @author System
 * @version 1.0
 */
class Internal extends Controller
{
    private $ruanganModel;
    private $jadwalModel;
    private $peminjamanModel;

    public function __construct()
    {
        // Load model yang dibutuhkan
        $this->ruanganModel = $this->model('RuanganModel');
        $this->jadwalModel = $this->model('JadwalModel');
        $this->peminjamanModel = $this->model('PeminjamanModel');

        // TODO: Uncomment ketika sistem authentication sudah siap
        // if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'internal') {
        //     header('Location: ' . BASE_URL . '/auth/login');
        //     exit;
        // }
    }

    /**
     * Halaman index - redirect ke booking
     */
    public function index()
    {
        header('Location: ' . BASE_URL . '/internal/booking');
        exit;
    }

    /**
     * Halaman booking utama
     * 
     * Menampilkan card lab dan jadwal untuk tanggal yang dipilih.
     * User bisa lihat slot kosong dan melakukan booking.
     */
    public function booking()
    {
        $data['judul'] = 'Booking Laboratorium';

        // Ambil tanggal yang dipilih dari URL parameter (default: hari ini)
        $selectedDate = $_GET['date'] ?? date('Y-m-d');
        $data['selected_date'] = $selectedDate;
        $data['selected_day'] = $this->getDayName($selectedDate);

        // Siapkan data untuk view
        $data['labs'] = $this->getLabsData();
        $data['jadwal_tetap'] = $this->getFilteredSchedules($selectedDate);
        $data['peminjaman'] = $this->getBookingsInRange($selectedDate);

        // Render views
        $this->view('components/header', $data);
        $this->view('components/internal_navbar', $data);
        $this->view('/internal/booking/index', $data);
        $this->view('components/footer');
    }

    /**
     * Ambil semua data lab dengan format yang sudah ditransformasi
     * 
     * Transform struktur database jadi format yang friendly untuk frontend.
     * Handle ekstraksi nama file gambar untuk ditampilkan dengan benar.
     * 
     * @return array Array berisi data lab
     */
    private function getLabsData()
    {
        $labs = [];

        foreach ($this->ruanganModel->getAll() as $ruangan) {
            $labs[] = [
                'id' => $ruangan['id'],
                'name' => $ruangan['nama_ruangan'],
                'short_name' => $ruangan['nama_ruangan'],
                'capacity' => $ruangan['kapasitas'],
                'building' => $ruangan['lokasi'],
                'pic' => $ruangan['pic'],
                'image' => $this->extractImageFilename($ruangan['gambar']),
                'status' => $ruangan['status'] == 1 ? 'tersedia' : 'terpakai'
            ];
        }

        return $labs;
    }

    /**
     * Ambil jadwal yang difilter berdasarkan hari dari tanggal yang dipilih
     * 
     * Hanya return jadwal yang terjadi di hari yang dipilih (misal: hanya jadwal Senin jika tanggalnya Senin).
     * Optimasi ini mengurangi pemrosesan data yang tidak perlu.
     * 
     * @param string $date Tanggal dalam format Y-m-d
     * @return array Array berisi jadwal yang sudah difilter
     */
    private function getFilteredSchedules($date)
    {
        $dayName = strtolower($this->getDayName($date));
        $schedules = [];

        foreach ($this->jadwalModel->getAll() as $jadwal) {
            // Hanya include jadwal untuk hari yang dipilih
            if (strtolower($jadwal['hari']) === $dayName) {
                $schedules[] = [
                    'lab_id' => $jadwal['lab_id'],
                    'hari' => ucfirst($dayName),
                    'jam_mulai' => substr($jadwal['jam_mulai'], 0, 5),    // Format: HH:MM
                    'jam_selesai' => substr($jadwal['jam_selesai'], 0, 5), // Format: HH:MM
                    'kelas' => $jadwal['nama_kelas'],
                    'matkul' => $jadwal['nama_matakuliah']
                ];
            }
        }

        return $schedules;
    }

    /**
     * Ambil peminjaman dalam rentang tanggal (±7 hari)
     * 
     * Hanya fetch booking yang approved dalam window 2 minggu centered di tanggal yang dipilih.
     * Optimasi ini mencegah loading semua historical bookings.
     * 
     * @param string $selectedDate Tanggal tengah dalam format Y-m-d
     * @return array Array berisi booking dalam range
     */
    private function getBookingsInRange($selectedDate)
    {
        // Hitung rentang tanggal (±7 hari untuk tampilan kalender)
        $startDate = date('Y-m-d', strtotime($selectedDate . ' -7 days'));
        $endDate = date('Y-m-d', strtotime($selectedDate . ' +7 days'));

        $bookings = [];

        foreach ($this->peminjamanModel->getAll() as $peminjaman) {
            $bookingDate = $peminjaman['tanggal_peminjaman'];

            // Filter: hanya booking yang approved dan dalam rentang tanggal
            $isApproved = $peminjaman['status'] === 'disetujui';
            $isInRange = $bookingDate >= $startDate && $bookingDate <= $endDate;

            if ($isApproved && $isInRange) {
                $bookings[] = [
                    'lab_id' => $peminjaman['lab_id'],
                    'tanggal' => $bookingDate,
                    'jam_mulai' => substr($peminjaman['jam_mulai'], 0, 5),
                    'jam_selesai' => substr($peminjaman['jam_selesai'], 0, 5),
                    'type' => $peminjaman['tipe'],
                    'keterangan' => $peminjaman['kegiatan'],
                    'peminjam' => $peminjaman['nama_peminjam']
                ];
            }
        }

        return $bookings;
    }

    /**
     * Ekstrak nama file gambar dari berbagai format
     * 
     * Handle multiple format data gambar:
     * - Base64 encoded images (dari admin panel) → return default
     * - File paths (dari seeder) → ekstrak filename
     * - Empty values → return default
     * 
     * @param string $gambar Data gambar (base64, path, atau filename)
     * @return string Nama file gambar untuk ditampilkan
     */
    private function extractImageFilename($gambar)
    {
        // Case 1: Value kosong
        if (empty($gambar)) {
            return 'StartUp.jpg';
        }

        // Case 2: Data Base64 (dari upload admin)
        if (strpos($gambar, 'data:image') === 0) {
            return 'StartUp.jpg';
        }

        // Case 3: Sudah proper path (public/storage/...)
        if (strpos($gambar, 'public/') === 0 || strpos($gambar, 'storage/') === 0) {
            return $gambar;
        }

        // Case 4: Hanya filename (dari seeder)
        if (strpos($gambar, '/') === false && strpos($gambar, '\\') === false) {
            return $gambar;
        }

        // Case 5: Full path - ekstrak filename saja
        return basename($gambar);
    }

    /**
     * Submit booking (AJAX endpoint)
     * 
     * Handle form submission booking via AJAX.
     * Validasi input, cek konflik, dan simpan ke database.
     * 
     * @return void Output JSON response
     */
    public function submitBooking()
    {
        header('Content-Type: application/json');

        // Hanya terima request POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        // Ekstrak data dari form
        $formData = [
            'tanggal' => $_POST['tanggal'] ?? '',
            'labName' => $_POST['lab'] ?? '',
            'jamMulai' => $_POST['jamMulai'] ?? '',
            'jamSelesai' => $_POST['jamSelesai'] ?? '',
            'namaPeminjam' => $_POST['namaPeminjam'] ?? '',
            'namaKegiatan' => $_POST['namaKegiatan'] ?? ''
        ];

        // Validasi input
        $validation = $this->validateBookingInput($formData);
        if (!$validation['valid']) {
            echo json_encode(['success' => false, 'message' => $validation['message']]);
            return;
        }

        // Cari ID lab berdasarkan nama
        $labId = $this->getLabIdByName($formData['labName']);
        if (!$labId) {
            echo json_encode(['success' => false, 'message' => 'Laboratorium tidak ditemukan']);
            return;
        }

        // Cek konflik dengan jadwal tetap (recurring schedules)
        $scheduleConflict = $this->checkScheduleConflict(
            $labId,
            $formData['tanggal'],
            $formData['jamMulai'],
            $formData['jamSelesai']
        );

        if ($scheduleConflict) {
            echo json_encode(['success' => false, 'message' => $scheduleConflict]);
            return;
        }

        // Cek konflik dengan booking yang sudah ada
        try {
            if ($this->peminjamanModel->checkConflict($labId, $formData['tanggal'], $formData['jamMulai'], $formData['jamSelesai'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Waktu yang dipilih sudah dibooking oleh user lain'
                ]);
                return;
            }

            // Siapkan data untuk insert ke database
            $bookingData = [
                'user_id' => $_SESSION['user_id'] ?? null,
                'lab_id' => $labId,
                'tanggal_peminjaman' => $formData['tanggal'],
                'jam_mulai' => $formData['jamMulai'],
                'jam_selesai' => $formData['jamSelesai'],
                'nama_peminjam' => $formData['namaPeminjam'],
                'kegiatan' => $formData['namaKegiatan'],
                'tipe' => 'internal',
                'status' => 'disetujui',  // Auto-approve untuk user internal
                'catatan' => ''
            ];

            // Simpan ke database
            if ($this->peminjamanModel->create($bookingData)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Booking berhasil! Status: Disetujui'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal menyimpan booking. Silakan coba lagi.'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Validasi input form booking
     * 
     * Cek field yang required dan integritas data dasar.
     * 
     * @param array $data Data dari form
     * @return array ['valid' => bool, 'message' => string]
     */
    private function validateBookingInput($data)
    {
        // Cek field yang required
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
     * Cari ID lab berdasarkan nama lab
     * 
     * @param string $labName Nama lab yang dicari
     * @return int|null ID lab atau null jika tidak ditemukan
     */
    private function getLabIdByName($labName)
    {
        $labs = $this->ruanganModel->getAll();

        foreach ($labs as $lab) {
            if ($lab['nama_ruangan'] === $labName) {
                return $lab['id'];
            }
        }

        return null;
    }

    /**
     * Cek konflik dengan jadwal tetap (recurring schedules)
     * 
     * Return error message jika ada konflik, null jika tidak ada.
     * 
     * @param int $labId ID lab
     * @param string $date Tanggal booking
     * @param string $startTime Jam mulai (HH:MM)
     * @param string $endTime Jam selesai (HH:MM)
     * @return string|null Error message atau null
     */
    private function checkScheduleConflict($labId, $date, $startTime, $endTime)
    {
        $dayName = $this->getDayName($date);
        $schedules = $this->jadwalModel->getByLabAndDay($labId, strtolower($dayName));

        foreach ($schedules as $jadwal) {
            $jadwalStart = substr($jadwal['jam_mulai'], 0, 5);
            $jadwalEnd = substr($jadwal['jam_selesai'], 0, 5);

            // Cek time overlap: (start < jadwal_end) DAN (end > jadwal_start)
            if ($startTime < $jadwalEnd && $endTime > $jadwalStart) {
                $matkul = $jadwal['nama_matakuliah'] ?? 'Praktikum';
                return "Bentrok dengan jadwal praktikum: {$matkul} ({$jadwalStart}-{$jadwalEnd})";
            }
        }

        return null;
    }

    /**
     * Dapatkan nama hari dalam bahasa Indonesia dari tanggal
     * 
     * Convert nama hari dari English ke Indonesian.
     * Contoh: "Monday" → "Senin"
     * 
     * @param string $date Tanggal dalam format Y-m-d
     * @return string Nama hari dalam bahasa Indonesia
     */
    private function getDayName($date)
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
