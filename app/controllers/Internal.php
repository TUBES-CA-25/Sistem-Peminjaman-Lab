<?php


class Internal extends Controller
{
    private $ruanganModel;
    private $jadwalModel;
    private $peminjamanModel;
    private $scheduleHelper;

    public function __construct()
    {
        $this->ruanganModel = $this->model('RuanganModel');
        $this->jadwalModel = $this->model('JadwalModel');
        $this->peminjamanModel = $this->model('PeminjamanModel');
        $this->scheduleHelper = new ScheduleHelper();

        // Proteksi Halaman Internal
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            require_once __DIR__ . '/../views/errors/401.php';
            exit;
        }

        if ($_SESSION['role'] !== 'internal') {
            http_response_code(403);
            require_once __DIR__ . '/../views/errors/403.php';
            exit;
        }
    }

    /**
     * Halaman index - redirect ke dashboard (jadwal)
     */
    public function index()
    {
        header('Location: ' . BASE_URL . '/internal/jadwal');
        exit;
    }

    /**
     * Halaman booking utama
     */
    public function booking()
    {
        $data = $this->getCommonScheduleData('booking');
        $data['judul'] = 'Booking Laboratorium';
        $data['active_page'] = 'booking';

        // Ambil tanggal yang dipilih dari URL parameter (default: hari ini)
        $selectedDate = $_GET['date'] ?? date('Y-m-d');
        $data['selected_date'] = $selectedDate;
        $data['selected_day'] = $this->scheduleHelper->getDayName($selectedDate);

        // Siapkan data untuk view
        $data['labs'] = $this->scheduleHelper->getLabsData($this->ruanganModel);
        $data['jadwal_tetap'] = $this->scheduleHelper->getFilteredSchedules($this->jadwalModel, $selectedDate);
        $data['peminjaman'] = $this->scheduleHelper->getBookingsInRange($this->peminjamanModel, $selectedDate);

        // Ambil data user yang sedang login untuk auto-fill form
        $userId = $_SESSION['user_id'] ?? null;
        if ($userId) {
            $userModel = $this->model('UserModel');
            $data['current_user'] = $userModel->getUserById($userId);
        } else {
            $data['current_user'] = null;
        }

        // Render views
        $this->view('components/internal_head', $data);
        $this->view('components/internal_navbar', $data);
        $this->view('components/internal_sidebar', $data);
        $this->view('/internal/booking/index', $data);
        $this->view('components/internal_footer');
    }

    /**
     * Halaman Dashboard Internal (Jadwal Laboratorium)
     */
    public function jadwal()
    {
        $data = $this->getCommonScheduleData('dashboard');
        $data['judul'] = 'Dashboard Internal';

        $this->view('components/internal_head', $data);
        $this->view('components/internal_navbar', $data);
        $this->view('components/internal_sidebar', $data);
        $this->view('/internal/jadwal/index', $data);
        $this->view('components/internal_footer');
    }

    /**
     * Helper untuk mengambil data jadwal yang umum digunakan di beberapa halaman
     */
    private function getCommonScheduleData($activePage)
    {
        $selectedDate = $_GET['date'] ?? date('Y-m-d');

        return [
            'active_page' => $activePage,
            'selected_date' => $selectedDate,
            'selected_day' => $this->scheduleHelper->getDayName($selectedDate),
            'labs' => $this->scheduleHelper->getLabsData($this->ruanganModel),
            'jadwal_tetap' => $this->scheduleHelper->getFilteredSchedules($this->jadwalModel, $selectedDate),
            'peminjaman' => $this->scheduleHelper->getBookingsInRange($this->peminjamanModel, $selectedDate)
        ];
    }

    /**
     * Halaman Data Peminjaman - History peminjaman user yang login
     */
    public function history()
    {
        $data['judul'] = 'Data Peminjaman Saya';
        $data['active_page'] = 'history';

        // Ambil peminjaman milik user yang login (internal)
        $peminjamanModel = $this->model('PeminjamanModel');
        $userId = $_SESSION['user_id'] ?? null;

        if ($userId) {
            $data['peminjaman'] = $peminjamanModel->getByUserId($userId);
        } else {
            $data['peminjaman'] = [];
        }

        // Render views
        $this->view('components/internal_head', $data);
        $this->view('components/internal_navbar', $data);
        $this->view('components/internal_sidebar', $data);
        $this->view('/internal/history/index', $data);
        $this->view('components/internal_footer');
    }

    /**
     * Update peminjaman milik user
     */
    public function updatePeminjaman()
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $peminjamanModel = $this->model('PeminjamanModel');

        // Verify ownership
        $peminjaman = $peminjamanModel->getById($input['id']);
        if (!$peminjaman || $peminjaman['user_id'] != $userId) {
            echo json_encode(['success' => false, 'message' => 'Tidak diizinkan']);
            return;
        }

        $result = $peminjamanModel->update($input['id'], [
            'lab_id' => $peminjaman['lab_id'],
            'tanggal' => $input['tanggal'],
            'jam_mulai' => $input['jam_mulai'],
            'jam_selesai' => $input['jam_selesai'],
            'nama_peminjam' => $peminjaman['nama_peminjam'],
            'kegiatan' => $input['keterangan'],
            'catatan' => $peminjaman['catatan'] ?? ''
        ]);

        echo json_encode(['success' => $result]);
    }

    /**
     * Hapus peminjaman milik user
     */
    public function deletePeminjaman()
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $peminjamanModel = $this->model('PeminjamanModel');

        // Verify ownership
        $peminjaman = $peminjamanModel->getById($input['id']);
        if (!$peminjaman || $peminjaman['user_id'] != $userId) {
            echo json_encode(['success' => false, 'message' => 'Tidak diizinkan']);
            return;
        }

        $result = $peminjamanModel->delete($input['id']);
        echo json_encode(['success' => $result]);
    }


    /**
     * Submit booking (AJAX endpoint)
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

        // Validasi input via Helper
        $validation = $this->scheduleHelper->validateBookingInput($formData);
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

        // Cek konflik dengan jadwal tetap via Helper
        $scheduleConflict = $this->scheduleHelper->checkScheduleConflict(
            $this->jadwalModel,
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
            // VALIDASI: Cek apakah waktu sudah lewat
            $currentDateTime = time();
            $requestedStart = strtotime($formData['tanggal'] . ' ' . $formData['jamMulai']);

            if ($requestedStart < $currentDateTime) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Tidak dapat melakukan booking untuk waktu yang sudah terlewati.'
                ]);
                return;
            }

            // VALIDASI: Jam Operasional (07:00 - 18:20)
            $startCheck = substr($formData['jamMulai'], 0, 5);
            $endCheck = substr($formData['jamSelesai'], 0, 5);

            if ($startCheck < '07:00' || $endCheck > '18:20') {
                echo json_encode([
                    'success' => false,
                    'message' => 'Peminjaman hanya diperbolehkan pada jam operasional lab (07:00 - 18:20).'
                ]);
                return;
            }

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
     * Cari ID lab berdasarkan nama lab
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
     * Endpoint AJAX untuk mengambil slot jadwal lab tertentu pada tanggal tertentu
     */
    public function getLabSlots()
    {
        // Parameter check
        $labId = $_GET['lab_id'] ?? null;
        $date = $_GET['date'] ?? date('Y-m-d');

        if (!$labId) {
            echo "Error: Lab ID required";
            return;
        }

        // Include helpers
        include_once __DIR__ . '/../views/internal/booking/helpers.php';

        // 1. Ambil data lab info (untuk nama lab di tombol booking)
        $ruangan = $this->ruanganModel->getAll();
        $labName = '';
        foreach ($ruangan as $r) {
            if ($r['id'] == $labId) {
                $labName = $r['nama_ruangan'];
                break;
            }
        }

        // 2. Ambil Jadwal Tetap via Helper
        $jadwalTetapRaw = $this->scheduleHelper->getFilteredSchedules($this->jadwalModel, $date);
        $jadwalLab = [];
        foreach ($jadwalTetapRaw as $j) {
            if ($j['lab_id'] == $labId) {
                $jadwalLab[] = $j;
            }
        }

        // 3. Ambil Peminjaman via Helper
        $peminjamanRaw = $this->scheduleHelper->getBookingsInRange($this->peminjamanModel, $date);
        $peminjamanLab = [];
        foreach ($peminjamanRaw as $p) {
            if ($p['lab_id'] == $labId && $p['tanggal'] == $date) {
                $peminjamanLab[] = $p;
            }
        }

        // 4. Hitung Slot Kosong & Sort (Masih pakai helper.php lama untuk logika gap)
        $slotKosong = getSlotKosong($jadwalLab, $peminjamanLab);
        $sortedSlots = getSortedSlots($jadwalLab, $peminjamanLab, $slotKosong);

        // 5. Render View Fragment
        $data = [
            'slots' => $sortedSlots,
            'labName' => $labName,
            'selected_date' => $date
        ];

        $this->view('internal/booking/ajax_slots', $data);
    }
}
