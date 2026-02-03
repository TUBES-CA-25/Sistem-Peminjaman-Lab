<?php

/**
 * Internal Controller
 * 
 * Mengelola semua fitur untuk pengguna internal (Dosen, Tendik, Mahasiswa Internal).
 * Fitur utama meliputi:
 * 1. Booking Laboratorium (tanpa approval admin).
 * 2. Melihat Jadwal Lab (Dashboard).
 * 3. History Peminjaman.
 * 4. Manajemen Profil User (termasuk upload foto).
 * 
 * @author  System
 * @version 2.0 (Refactored to use Services)
 */
class Internal extends Controller
{
    /** @var string Jam buka laboratorium */
    private const JAM_BUKA_LAB = '07:00';

    /** @var string Jam tutup laboratorium */
    private const JAM_TUTUP_LAB = '18:20';

    /** @var BookingService Service untuk logika peminjaman */
    private $bookingService;

    /** @var WhatsAppService Service untuk notifikasi WA */
    private $waService;

    /** @var UserService Service untuk manajemen user */
    private $userService;

    /**
     * Constructor
     * 
     * Menginisialisasi controller, mengecek sesi login, dan memuat service yang dibutuhkan.
     * Jika user belum login atau bukan role 'internal', akan diredirect ke halaman error.
     */
    public function __construct()
    {
        // 1. Proteksi Halaman: Pastikan user sudah login
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            require_once __DIR__ . '/../views/errors/401.php';
            exit;
        }

        // 2. Proteksi Halaman: Pastikan role user adalah 'internal'
        if ($_SESSION['role'] !== 'internal') {
            http_response_code(403);
            require_once __DIR__ . '/../views/errors/403.php';
            exit;
        }

        // 3. Load Services Dependency Injection
        $this->bookingService = $this->service('BookingService');
        $this->waService = $this->service('WhatsAppService');
        $this->userService = $this->service('UserService');
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
     * Halaman Booking Utama
     * 
     * Menampilkan form booking laboratorium.
     */
    public function booking()
    {
        $data = $this->bookingService->getCommonScheduleData('booking');
        $data['judul'] = 'Booking Laboratorium';

        // Ambil data user saat ini untuk auto-fill form booking
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
     * Halaman Jadwal (Dashboard)
     * 
     * Menampilkan overview jadwal semua laboratorium.
     */
    public function jadwal()
    {
        $data = $this->bookingService->getCommonScheduleData('dashboard');
        $data['judul'] = 'Dashboard Internal';

        $this->view('components/internal_head', $data);
        $this->view('components/internal_navbar', $data);
        $this->view('components/internal_sidebar', $data);
        $this->view('/internal/jadwal/index', $data);
        $this->view('components/internal_footer');
    }

    /**
     * Halaman History Peminjaman
     * 
     * Menampilkan daftar riwayat peminjaman user yang sedang login.
     */
    public function history()
    {
        $data['judul'] = 'Data Peminjaman Saya';
        $data['active_page'] = 'history';

        $userId = $_SESSION['user_id'] ?? null;

        if ($userId) {
            $data['peminjaman'] = $this->bookingService->getBookingByUserId($userId);
        } else {
            $data['peminjaman'] = [];
        }

        $this->view('components/internal_head', $data);
        $this->view('components/internal_navbar', $data);
        $this->view('components/internal_sidebar', $data);
        $this->view('/internal/history/index', $data);
        $this->view('components/internal_footer');
    }

    /**
     * Update Peminjaman (API Endpoint)
     * 
     * Menerima request JSON untuk memperbarui data booking milik user sendiri.
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
     * Hapus Peminjaman (API Endpoint)
     * 
     * Menerima request JSON untuk membatalkan/menghapus booking milik user sendiri.
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
     * Submit Booking Baru (API Endpoint)
     * 
     * Memproses form booking via AJAX POST.
     * Melakukan validasi input, pengecekan jadwal bentrok, dan menyimpan data.
     */
    public function submitBooking()
    {
        header('Content-Type: application/json');

        // 1. Validasi Method Request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        // 2. Ambil Input Data
        $formData = [
            'tanggal' => $_POST['tanggal'] ?? '',
            'labName' => $_POST['lab'] ?? '',
            'jamMulai' => $_POST['jamMulai'] ?? '',
            'jamSelesai' => $_POST['jamSelesai'] ?? '',
            'namaPeminjam' => $_POST['namaPeminjam'] ?? '',
            'namaKegiatan' => $_POST['namaKegiatan'] ?? ''
        ];

        // 3. Validasi Kelengkapan Data via Service
        $validation = $this->bookingService->validateBookingInput($formData);
        if (!$validation['valid']) {
            echo json_encode(['success' => false, 'message' => $validation['message']]);
            return;
        }

        // 4. Cari ID Lab
        $labId = $this->bookingService->getLabIdByName($formData['labName']);
        if (!$labId) {
            echo json_encode(['success' => false, 'message' => 'Laboratorium tidak ditemukan']);
            return;
        }

        // 5. Cek Konflik dengan Jadwal Tetap (Praktikum)
        $scheduleConflict = $this->bookingService->checkScheduleConflict(
            $labId,
            $formData['tanggal'],
            $formData['jamMulai'],
            $formData['jamSelesai']
        );

        if ($scheduleConflict) {
            echo json_encode(['success' => false, 'message' => $scheduleConflict]);
            return;
        }

        try {
            // 6. Validasi Waktu

            // a. Cek apakah waktu sudah lewat (Backdate protection)
            $currentDateTime = time();
            $requestedStart = strtotime($formData['tanggal'] . ' ' . $formData['jamMulai']);

            if ($requestedStart < $currentDateTime) {
                echo json_encode(['success' => false, 'message' => 'Tidak dapat melakukan booking untuk waktu yang sudah berlalu.']);
                return;
            }

            // b. Cek Jam Operasional Lab
            $startCheck = substr($formData['jamMulai'], 0, 5);
            $endCheck = substr($formData['jamSelesai'], 0, 5);

            if ($startCheck < self::JAM_BUKA_LAB || $endCheck > self::JAM_TUTUP_LAB) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Peminjaman hanya diperbolehkan pada jam operasional (' . self::JAM_BUKA_LAB . ' - ' . self::JAM_TUTUP_LAB . ').'
                ]);
                return;
            }

            // c. Cek Konflik dengan Peminjaman Lain (Ad-hoc)
            if ($this->bookingService->checkBookingConflict($labId, $formData['tanggal'], $formData['jamMulai'], $formData['jamSelesai'])) {
                echo json_encode(['success' => false, 'message' => 'Waktu yang dipilih sudah dibooking oleh user lain']);
                return;
            }

            // 7. Persiapan Data Insert
            $bookingData = [
                'user_id' => $_SESSION['user_id'] ?? null,
                'lab_id' => $labId,
                'tanggal_peminjaman' => $formData['tanggal'],
                'jam_mulai' => $formData['jamMulai'],
                'jam_selesai' => $formData['jamSelesai'],
                'nama_peminjam' => $formData['namaPeminjam'],
                'kegiatan' => $formData['namaKegiatan'],
                'tipe' => 'internal',
                'status' => 'disetujui',  // AUTO-APPROVE untuk Internal
                'catatan' => ''
            ];

            // 8. Simpan ke Database
            if ($this->bookingService->createBooking($bookingData)) {

                // 9. Kirim Notifikasi WhatsApp (Jika user adalah Dosen)
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
     * Get Lab Slots (AJAX Endpoint)
     * 
     * Mengambil data slot ketersediaan lab tertentu pada tanggal tertentu.
     * Digunakan oleh frontend untuk menampilkan visualisasi slot waktu.
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
     * Halaman Profil User
     * 
     * Menampilkan profil user dan form untuk edit data/foto.
     */
    public function profile()
    {
        $data['judul'] = 'Profil Saya';
        $data['active_page'] = 'profile';

        $userModel = $this->model('UserModel');
        $data['user'] = $userModel->getUserById($_SESSION['user_id']);

        $this->view('components/internal_head', $data);
        $this->view('components/internal_navbar', $data);
        $this->view('components/internal_sidebar', $data);
        $this->view('internal/profile/index', $data);
        $this->view('components/internal_footer');
    }



    /**
     * Proses Update Profil
     * 
     * Menerima submisi form profil, menangani upload foto (base64/file),
     * dan update database via UserService.
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

        // Update session & flash if success
        if ($result['success']) {
            if (isset($result['data']['nama'])) {
                $_SESSION['nama'] = $result['data']['nama']; // Update session nama
            }
            Flasher::setFlash('Berhasil', $result['message'], 'success');
        } else if (empty($result['requires_verification'])) {
            Flasher::setFlash('Gagal', $result['message'], 'danger');
        }

        // Jika dipanggil via AJAX
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            header('Content-Type: application/json');
            echo json_encode($result);
            exit;
        }

        header('Location: ' . BASE_URL . '/internal/profile');
        exit;
    }

    public function requestEmailVerification()
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit;
        }

        $newEmail = $_POST['email'] ?? '';
        $userId = $_SESSION['user_id'];
        $user = $this->model('UserModel')->getUserById($userId);

        if (empty($newEmail)) {
            echo json_encode(['success' => false, 'message' => 'Email baru wajib diisi']);
            exit;
        }

        $verificationService = $this->service('EmailVerificationService');
        if ($verificationService->sendVerificationCode($userId, $newEmail, $user['nama'])) {
            echo json_encode(['success' => true, 'message' => 'Kode verifikasi telah dikirim ke ' . $newEmail]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal mengirim email verifikasi']);
        }
        exit;
    }

    public function verifyEmailChange()
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit;
        }

        $code = $_POST['otp'] ?? '';
        $userId = $_SESSION['user_id'];

        $verificationService = $this->service('EmailVerificationService');
        $newEmail = $verificationService->verifyCode($userId, $code);

        if ($newEmail) {
            // Ambil data user lama dari model untuk melengkapi input updateProfile
            $userModel = $this->model('UserModel');
            $user = $userModel->getUserById($userId);

            // Setelah kode valid, panggil update profil dengan flag verified
            $input = $_POST;
            $input['email'] = $newEmail;
            $input['email_verified'] = true;
            
            // Pastikan field wajib ada agar tidak error di UserService
            if (!isset($input['nama'])) $input['nama'] = $user['nama'];
            if (!isset($input['telepon'])) $input['telepon'] = $user['telepon'];

            $result = $this->userService->updateProfile($userId, $input, []);
            
            // Hapus kode verifikasi HANYA JIKA update berhasil
            if ($result['success']) {
                $verificationService->clearVerificationCode($userId);
                Flasher::setFlash('Berhasil', 'Email Anda telah berhasil diperbarui.', 'success');
            } else {
                Flasher::setFlash('Gagal', $result['message'], 'danger');
            }

            echo json_encode($result);
        } else {
            echo json_encode(['success' => false, 'message' => 'Kode verifikasi salah atau sudah kadaluarsa']);
        }
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
