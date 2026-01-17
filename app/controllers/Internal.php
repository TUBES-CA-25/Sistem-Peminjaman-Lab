<?php

class Internal extends Controller
{
    private $ruanganModel;
    private $jadwalModel;
    private $peminjamanModel;

    public function __construct()
    {
        // Load models
        $this->ruanganModel = $this->model('RuanganModel');
        $this->jadwalModel = $this->model('JadwalModel');
        $this->peminjamanModel = $this->model('PeminjamanModel');

        // Cek login & role (uncomment when auth is ready)
        // if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'internal') {
        //     header('Location: ' . BASE_URL . '/auth/login');
        //     exit;
        // }
    }

    public function index()
    {
        // Redirect ke booking
        header('Location: ' . BASE_URL . '/internal/booking');
        exit;
    }

    public function booking()
    {
        $data['judul'] = 'Booking Laboratorium';

        // Get all labs from database
        $ruanganData = $this->ruanganModel->getAll();

        // Transform ruangan data to match internal booking format
        $data['labs'] = [];
        foreach ($ruanganData as $ruangan) {
            $data['labs'][] = [
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

        // Get selected date (default today)
        $data['selected_date'] = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
        $data['selected_day'] = $this->getDayName($data['selected_date']);

        // Get jadwal tetap from database
        $jadwalData = $this->jadwalModel->getAll();
        $data['jadwal_tetap'] = [];

        foreach ($jadwalData as $jadwal) {
            $data['jadwal_tetap'][] = [
                'lab_id' => $jadwal['lab_id'],
                'hari' => ucfirst(strtolower($jadwal['hari'])),
                'jam_mulai' => substr($jadwal['jam_mulai'], 0, 5),
                'jam_selesai' => substr($jadwal['jam_selesai'], 0, 5),
                'kelas' => $jadwal['nama_kelas'],
                'matkul' => $jadwal['nama_matakuliah']
            ];
        }

        // Get approved peminjaman from database
        $peminjamanData = $this->peminjamanModel->getAll();
        $data['peminjaman'] = [];

        foreach ($peminjamanData as $peminjaman) {
            if ($peminjaman['status'] == 'disetujui') {
                $data['peminjaman'][] = [
                    'lab_id' => $peminjaman['lab_id'],
                    'tanggal' => $peminjaman['tanggal_peminjaman'],
                    'jam_mulai' => substr($peminjaman['jam_mulai'], 0, 5),
                    'jam_selesai' => substr($peminjaman['jam_selesai'], 0, 5),
                    'type' => $peminjaman['tipe'],
                    'keterangan' => $peminjaman['kegiatan'],
                    'peminjam' => $peminjaman['nama_peminjam']
                ];
            }
        }

        $this->view('components/header', $data);
        $this->view('components/internal_navbar', $data);
        $this->view('/internal/booking/index', $data);
        $this->view('components/footer');
    }

    /**
     * Extract image filename from base64 or path
     */
    private function extractImageFilename($gambar)
    {
        // If empty, return default
        if (empty($gambar)) {
            return 'StartUp.jpg';
        }

        // If it's base64, return default (admin uses base64)
        if (strpos($gambar, 'data:image') === 0) {
            return 'StartUp.jpg';
        }

        // If it contains 'public/storage', return as is (it's a path)
        if (strpos($gambar, 'public/') === 0 || strpos($gambar, 'storage/') === 0) {
            return $gambar;
        }

        // If it's just a filename (from seeder), return as is
        if (strpos($gambar, '/') === false && strpos($gambar, '\\') === false) {
            return $gambar;
        }

        // If it's a path, extract filename
        return basename($gambar);
    }

    /**
     * Handle booking submission from internal users
     */
    public function submitBooking()
    {
        // Only accept POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }


        // Get POST data
        // DEBUGGING: Log raw input
        $debugData = print_r($_POST, true);
        file_put_contents(__DIR__ . '/../../debug_booking.log', "POST Data:\n" . $debugData . "\n", FILE_APPEND);

        $tanggal = $_POST['tanggal'] ?? '';
        $labName = $_POST['lab'] ?? '';
        $jamMulai = $_POST['jamMulai'] ?? '';
        $jamSelesai = $_POST['jamSelesai'] ?? '';
        $namaPeminjam = $_POST['namaPeminjam'] ?? '';
        $namaKegiatan = $_POST['namaKegiatan'] ?? '';

        // Validate required fields
        if (empty($tanggal) || empty($labName) || empty($jamMulai) || empty($jamSelesai) || empty($namaPeminjam) || empty($namaKegiatan)) {
            echo json_encode(['success' => false, 'message' => 'Semua field harus diisi']);
            return;
        }

        // Convert time format from 07.00 to 07:00
        $jamMulai = str_replace('.', ':', $jamMulai);
        $jamSelesai = str_replace('.', ':', $jamSelesai);

        // Validate time format
        if (!preg_match('/^\d{2}:\d{2}$/', $jamMulai) || !preg_match('/^\d{2}:\d{2}$/', $jamSelesai)) {
            echo json_encode(['success' => false, 'message' => 'Format waktu tidak valid']);
            return;
        }

        // Get lab ID from lab name
        $ruanganData = $this->ruanganModel->getAll();
        $labId = null;
        foreach ($ruanganData as $ruangan) {
            if (trim($ruangan['nama_ruangan']) == trim($labName)) {
                $labId = $ruangan['id'];
                break;
            }
        }

        if (!$labId) {
            echo json_encode(['success' => false, 'message' => 'Lab tidak ditemukan']);
            return;
        }

        // Check for conflicts with jadwal tetap
        $hari = $this->getDayName($tanggal);
        $jadwalData = $this->jadwalModel->getByLabAndDay($labId, strtolower($hari));

        foreach ($jadwalData as $jadwal) {
            $jadwalStart = substr($jadwal['jam_mulai'], 0, 5);
            $jadwalEnd = substr($jadwal['jam_selesai'], 0, 5);

            // Check overlap: (start < jadwal_end AND end > jadwal_start)
            if ($jamMulai < $jadwalEnd && $jamSelesai > $jadwalStart) {
                $matkul = $jadwal['nama_matakuliah'] ?? 'Praktikum';
                echo json_encode([
                    'success' => false,
                    'message' => "Bentrok dengan jadwal praktikum: {$matkul} ({$jadwalStart}-{$jadwalEnd})"
                ]);
                return;
            }
        }

        // Check for conflicts with existing peminjaman
        try {
            if ($this->peminjamanModel->checkConflict($labId, $tanggal, $jamMulai, $jamSelesai)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Waktu yang dipilih sudah dibooking oleh user lain'
                ]);
                return;
            }

            // Prepare data for insertion
            $bookingData = [
                'user_id' => $_SESSION['user_id'] ?? null,
                'lab_id' => $labId,
                'tanggal_peminjaman' => $tanggal,
                'jam_mulai' => $jamMulai,
                'jam_selesai' => $jamSelesai,
                'nama_peminjam' => $namaPeminjam,
                'kegiatan' => $namaKegiatan,
                'tipe' => 'internal',
                'status' => 'disetujui', // Auto-approve for internal users
                'catatan' => ''
            ];

            // Save to database
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
            // Return actual error for debugging
            echo json_encode([
                'success' => false,
                'message' => 'System Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Helper: Get Indonesian day name from date
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
