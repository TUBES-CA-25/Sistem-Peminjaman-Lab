<?php

class Admin extends Controller
{
    private $ruanganModel;
    private $jadwalModel;
    private $peminjamanModel;
    private $userService;

    public function __construct()
    {
        $this->ruanganModel = $this->model('RuanganModel');
        $this->jadwalModel = $this->model('JadwalModel');
        $this->peminjamanModel = $this->model('PeminjamanModel');
        $this->userService = $this->service('UserService');

        if (!isset($_SESSION['user_id'])) {
            // Belum login → Error 401
            http_response_code(401);
            require_once __DIR__ . '/../views/errors/401.php';
            exit;
        }

        if ($_SESSION['role'] !== 'admin') {
            // Sudah login tapi bukan admin → Error 403
            http_response_code(403);
            require_once __DIR__ . '/../views/errors/403.php';
            exit;
        }
    }

    /**
     * Halaman index - redirect ke dashboard
     */
    public function index()
    {
        header("Location: " . BASE_URL . "/admin/dashboard");
        exit;
    }

    /**
     * Halaman Dashboard Admin - Monitoring jadwal semua lab
     */
    public function dashboard()
    {
        $selectedDate = $_GET['date'] ?? date('Y-m-d');
        $selectedDay = $this->getDayName($selectedDate);

        // Ambil semua data yang diperlukan
        $data = [
            'active_page' => 'dashboard',
            'judul' => 'Dashboard Admin',
            'selected_date' => $selectedDate,
            'selected_day' => $selectedDay,
            'labs' => $this->getLabsData(),
            'jadwal_tetap' => $this->getFilteredSchedules($selectedDate),
            'peminjaman' => $this->getBookingsForDate($selectedDate)
        ];

        // Load Views
        $this->view('components/admin_head', $data);
        $this->view('components/admin_navbar', $data);
        $this->view('components/admin_sidebar', $data);
        $this->view('admin/dashboard/index', $data);
        $this->view('components/admin_footer', $data);
    }

    /**
     * Ambil data labs dengan transformasi untuk dashboard
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
            ];
        }
        return $labs;
    }

    /**
     * Ambil jadwal yang difilter berdasarkan hari
     */
    private function getFilteredSchedules($date)
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
     * Ambil semua booking untuk tanggal tertentu
     */
    private function getBookingsForDate($date)
    {
        $bookings = [];

        foreach ($this->peminjamanModel->getAll() as $peminjaman) {
            // Skip if required fields are missing
            if (!isset($peminjaman['tanggal']) || !isset($peminjaman['lab_id'])) {
                continue;
            }

            $bookingDate = $peminjaman['tanggal'];

            // Filter: hanya yang disetujui (yang tergeser disembunyikan sesuai logic internal)
            $isShown = ($peminjaman['status'] ?? '') === 'disetujui';

            if ($bookingDate == $date && $isShown) {
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
     * Halaman Profil Admin
     */
    public function profile()
    {
        $data['judul'] = 'Profil Saya';
        $data['active_page'] = 'profile'; // Untuk sidebar active state

        $userModel = $this->model('UserModel');
        $data['user'] = $userModel->getUserById($_SESSION['user_id']);

        $this->view('components/admin_head', $data);
        $this->view('components/admin_navbar', $data);
        $this->view('components/admin_sidebar', $data); // Sidebar butuh active_page
        $this->view('admin/profile/index', $data);
        $this->view('components/admin_footer', $data);
    }

    /**
     * Proses Update Profil Admin
     */
    public function prosesUpdateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/profile');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $input = $_POST;
        $files = $_FILES;

        $result = $this->userService->updateProfile($userId, $input, $files);

        if ($result['success']) {
            if (isset($result['data']['nama'])) {
                $_SESSION['nama'] = $result['data']['nama']; // Update session nama
            }
            Flasher::setFlash('Berhasil', $result['message'], 'success');
        } else {
            Flasher::setFlash('Gagal', $result['message'], 'danger');
        }

        header('Location: ' . BASE_URL . '/admin/profile');
        exit;
    }

    /**
     * Konversi tanggal ke nama hari dalam bahasa Indonesia
     */
    private function getDayName($dateStr)
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

        $dayEn = date('l', strtotime($dateStr));
        return $days[$dayEn] ?? 'Unknown';
    }
}
