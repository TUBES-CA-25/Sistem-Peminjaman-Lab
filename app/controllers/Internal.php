<?php

/**
 * Internal Controller - Kelola fitur pengguna internal (booking, jadwal, history, profil)
 */
class Internal extends Controller
{
    private const JAM_BUKA_LAB = '07:00';
    private const JAM_TUTUP_LAB = '18:20';
    private $bookingService;
    private $waService;

    public function __construct()
    {
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
        $this->bookingService = $this->service('BookingService');
        $this->waService = $this->service('WhatsAppService');
        $this->userService = $this->service('UserService');
    }

    private function renderPage($viewPath, $data = [])
    {
        $this->view('components/internal_head', $data);
        $this->view('components/internal_navbar', $data);
        $this->view('components/internal_sidebar', $data);
        $this->view($viewPath, $data);
        $this->view('components/internal_footer');
    }

    /**
     * Halaman Index
     * 
     * Redirect user langsung ke dashboard jadwal.
     */
    public function index()
    {
        header('Location: ' . BASE_URL . '/internal/jadwal');
        exit;
    }

    /**
     * Halaman booking
     */
    public function booking()
    {
        $data = $this->bookingService->getCommonScheduleData('booking');
        $data['judul'] = 'Booking Laboratorium';
        
        $userId = $_SESSION['user_id'] ?? null;
        if ($userId) {
            $userModel = $this->model('UserModel');
            $data['current_user'] = $userModel->getUserById($userId);
        }

        $this->renderPage('/internal/booking/index', $data);
    }

    /**
     * Dashboard jadwal
     */
    public function jadwal()
    {
        $data = $this->bookingService->getCommonScheduleData('dashboard');
        $data['judul'] = 'Dashboard Internal';
        $this->renderPage('/internal/jadwal/index', $data);
    }

    /**
     * History peminjaman
     */
    public function history()
    {
        $data['judul'] = 'Data Peminjaman Saya';
        $data['active_page'] = 'history';
        $userId = $_SESSION['user_id'] ?? null;
        $data['peminjaman'] = $userId ? $this->bookingService->getBookingByUserId($userId) : [];
        $this->renderPage('/internal/history/index', $data);
    }

    /**
     * Update booking
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

        // Verifikasi kepemilikan booking sebelum update
        $peminjaman = $this->bookingService->getBookingById($input['id']);
        if (!$peminjaman || $peminjaman['user_id'] != $userId) {
            echo json_encode(['success' => false, 'message' => 'Tidak diizinkan mengedit data ini']);
            return;
        }

        $result = $this->bookingService->updateBooking($input['id'], [
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
     * Delete booking
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

        // Verifikasi kepemilikan booking
        $peminjaman = $this->bookingService->getBookingById($input['id']);
        if (!$peminjaman || $peminjaman['user_id'] != $userId) {
            echo json_encode(['success' => false, 'message' => 'Tidak diizinkan menghapus data ini']);
            return;
        }

        $result = $this->bookingService->deleteBooking($input['id']);
        echo json_encode(['success' => $result]);
    }

    /**
     * Proses booking baru
     */
    public function submitBooking()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $formData = [
            'tanggal' => $_POST['tanggal'] ?? '',
            'labName' => $_POST['lab'] ?? '',
            'jamMulai' => $_POST['jamMulai'] ?? '',
            'jamSelesai' => $_POST['jamSelesai'] ?? '',
            'namaPeminjam' => $_POST['namaPeminjam'] ?? '',
            'namaKegiatan' => $_POST['namaKegiatan'] ?? ''
        ];

        $validation = $this->bookingService->validateBookingInput($formData);
        if (!$validation['valid']) {
            echo json_encode(['success' => false, 'message' => $validation['message']]);
            return;
        }

        $labId = $this->bookingService->getLabIdByName($formData['labName']);
        if (!$labId) {
            echo json_encode(['success' => false, 'message' => 'Laboratorium tidak ditemukan']);
            return;
        }

        $scheduleConflict = $this->bookingService->checkScheduleConflict(
            $labId, $formData['tanggal'], $formData['jamMulai'], $formData['jamSelesai']
        );
        if ($scheduleConflict) {
            echo json_encode(['success' => false, 'message' => $scheduleConflict]);
            return;
        }

        try {
            
            // a. Cek apakah waktu sudah lewat (Backdate protection)
            $currentDateTime = time();
            $requestedStart = strtotime($formData['tanggal'] . ' ' . $formData['jamMulai']);

            if ($requestedStart < $currentDateTime) {
                echo json_encode(['success' => false, 'message' => 'Tidak dapat melakukan booking untuk waktu yang sudah berlalu.']);
                return;
            }

            $startCheck = substr($formData['jamMulai'], 0, 5);
            $endCheck = substr($formData['jamSelesai'], 0, 5);

            if ($startCheck < self::JAM_BUKA_LAB || $endCheck > self::JAM_TUTUP_LAB) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Peminjaman hanya diperbolehkan pada jam operasional (' . self::JAM_BUKA_LAB . ' - ' . self::JAM_TUTUP_LAB . ').'
                ]);
                return;
            }

            if ($this->bookingService->checkBookingConflict($labId, $formData['tanggal'], $formData['jamMulai'], $formData['jamSelesai'])) {
                echo json_encode(['success' => false, 'message' => 'Waktu yang dipilih sudah dibooking oleh user lain']);
                return;
            }

            $bookingData = [
                'user_id' => $_SESSION['user_id'] ?? null,
                'lab_id' => $labId,
                'tanggal_peminjaman' => $formData['tanggal'],
                'jam_mulai'          => $formData['jamMulai'],
                'jam_selesai'        => $formData['jamSelesai'],
                'nama_peminjam'      => $formData['namaPeminjam'],
                'kegiatan'           => $formData['namaKegiatan'],
                'tipe'               => 'internal',
                'status'             => 'disetujui',
                'catatan'            => ''
            ];

            if ($this->bookingService->createBooking($bookingData)) {
                $this->sendWhatsappNotificationIfNeeded($labId, $formData);
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
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get lab slots
     */
    public function getLabSlots()
    {
        $labId = $_GET['lab_id'] ?? null;
        $date = $_GET['date'] ?? date('Y-m-d');

        if (!$labId) {
            echo "Error: Lab ID required";
            return;
        }

        // Load helpers untuk kalkulasi slot (Legacy helper)
        include_once __DIR__ . '/../views/internal/booking/helpers.php';

        // 1. Ambil Info Lab
        $labInfo = $this->bookingService->getLabById($labId);
        $labName = $labInfo['nama_ruangan'] ?? '';

        // 2. Ambil Jadwal Tetap (Praktikum)
        $jadwalTetapRaw = $this->bookingService->getFilteredSchedules($date);
        $jadwalLab = array_filter($jadwalTetapRaw, function ($j) use ($labId) {
            return $j['lab_id'] == $labId;
        });

        // 3. Ambil Booking Aktif
        $peminjamanRaw = $this->bookingService->getBookingsInRange($date);
        $peminjamanLab = array_filter($peminjamanRaw, function ($p) use ($labId, $date) {
            return $p['lab_id'] == $labId && $p['tanggal'] == $date;
        });

        // 4. Kalkulasi Slot Kosong
        $slotKosong = getSlotKosong($jadwalLab, $peminjamanLab);
        $sortedSlots = getSortedSlots($jadwalLab, $peminjamanLab, $slotKosong);

        // 5. Render Partial View
        $data = [
            'slots' => $sortedSlots,
            'labName' => $labName,
            'selected_date' => $date
        ];

        $this->view('internal/booking/ajax_slots', $data);
    }

    /**
     * Profile user
     */
    public function profile()
    {
        $data['judul'] = 'Profil Saya';
        $data['active_page'] = 'profile'; 
        $userModel = $this->model('UserModel');
        $data['user'] = $userModel->getUserById($_SESSION['user_id']);
        $this->renderPage('internal/profile/index', $data);
    }



    /**
     * Process profile update
     */
    public function prosesUpdateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/internal/profile');
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

        header('Location: ' . BASE_URL . '/internal/profile');
        exit;
    }

    /**
     * Private Helper: Kirim Notifikasi WA
     * 
     * Mengirim notifikasi ke admin/koordinator lab jika user adalah Dosen.
     */
    private function sendWhatsappNotificationIfNeeded($labId, $formData)
    {
        try {
            $userModel = $this->model('UserModel');
            $currentUser = $userModel->getUserById($_SESSION['user_id']);

            // Cek apakah user adalah Dosen
            if (isset($currentUser['status']) && $currentUser['status'] === 'Dosen') {
                $labInfo = $this->bookingService->getLabById($labId);
                $targetNumber = null;

                // Prioritas 1: PIC Lab
                if (!empty($labInfo['email_pic'])) {
                    $koordinator = $userModel->getUserByEmail($labInfo['email_pic']);
                    if ($koordinator && !empty($koordinator['telepon'])) {
                        $targetNumber = $koordinator['telepon'];
                    }
                }

                // Prioritas 2: Admin Utama (Environment Variable)
                if (empty($targetNumber)) {
                    $targetNumber = getenv('WA_ADMIN') ?: ($_ENV['WA_ADMIN'] ?? '');
                }

                if (!empty($targetNumber)) {
                    $tanggal = date('d F Y', strtotime($formData['tanggal']));
                    $pesan = "📢 *NOTIFIKASI BOOKING LABORATORIUM*\n\n" .
                        "Seorang dosen telah melakukan booking:\n\n" .
                        "👤 *Nama:* " . $formData['namaPeminjam'] . "\n" .
                        "🏛️ *Laboratorium:* " . $formData['labName'] . "\n" .
                        "📅 *Tanggal:* " . $tanggal . "\n" .
                        "🕐 *Waktu:* " . $formData['jamMulai'] . " - " . $formData['jamSelesai'] . " WIB\n" .
                        "📝 *Kegiatan:* " . $formData['namaKegiatan'] . "\n\n" .
                        "✅ *Status:* Disetujui Otomatis\n\n" .
                        "_Silakan Segera Persiapkan Ruangan yang dipinjam._\n" .
                        "_Sistem Peminjaman Lab ICLABS_";

                    $this->waService->kirimPesanFonnte($targetNumber, $pesan);
                }
            }
        } catch (Exception $e) {
            // Silently fail untuk notifikasi agar tidak mengganggu proses booking user
            error_log("WA Notification Error: " . $e->getMessage());
        }
    }
}
