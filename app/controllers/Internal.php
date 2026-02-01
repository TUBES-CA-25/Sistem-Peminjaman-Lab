<?php

/**
 * Internal Controller
 * 
 * Menghandle fitur booking laboratorium untuk user internal.
 * User internal bisa booking lab langsung tanpa perlu approval admin.
 * 
 * @author System
 * @version 2.0 (Refactored to use Services)
 */
class Internal extends Controller
{
    /** @var string Jam buka laboratorium */
    private const JAM_BUKA_LAB = '07:00';
    
    /** @var string Jam tutup laboratorium */
    private const JAM_TUTUP_LAB = '18:20';

    private $bookingService;
    private $waService;

    public function __construct()
    {
        // Proteksi Halaman Internal
        if (!isset($_SESSION['user_id'])) {
            // Belum login → Error 401
            http_response_code(401);
            require_once __DIR__ . '/../views/errors/401.php';
            exit;
        }

        if ($_SESSION['role'] !== 'internal') {
            // Sudah login tapi bukan internal → Error 403
            http_response_code(403);
            require_once __DIR__ . '/../views/errors/403.php';
            exit;
        }

        // Load Services
        $this->bookingService = $this->service('BookingService');
        $this->waService = $this->service('WhatsAppService');
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
        $data = $this->bookingService->getCommonScheduleData('booking');
        $data['judul'] = 'Booking Laboratorium';
        
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
        $data = $this->bookingService->getCommonScheduleData('dashboard');
        $data['judul'] = 'Dashboard Internal';

        $this->view('components/internal_head', $data);
        $this->view('components/internal_navbar', $data);
        $this->view('components/internal_sidebar', $data);
        $this->view('/internal/jadwal/index', $data);
        $this->view('components/internal_footer');
    }

    /**
     * Halaman Data Peminjaman - History peminjaman user yang login
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

        // Verify ownership
        $peminjaman = $this->bookingService->getBookingById($input['id']);
        if (!$peminjaman || $peminjaman['user_id'] != $userId) {
            echo json_encode(['success' => false, 'message' => 'Tidak diizinkan']);
            return;
        }

        $result = $this->bookingService->updateBooking($input['id'], [
            'lab_id'         => $peminjaman['lab_id'],
            'tanggal'        => $input['tanggal'],
            'jam_mulai'      => $input['jam_mulai'],
            'jam_selesai'    => $input['jam_selesai'],
            'nama_peminjam'  => $peminjaman['nama_peminjam'],
            'kegiatan'       => $input['keterangan'],
            'catatan'        => $peminjaman['catatan'] ?? ''
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

        // Verify ownership
        $peminjaman = $this->bookingService->getBookingById($input['id']);
        if (!$peminjaman || $peminjaman['user_id'] != $userId) {
            echo json_encode(['success' => false, 'message' => 'Tidak diizinkan']);
            return;
        }

        $result = $this->bookingService->deleteBooking($input['id']);
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

        // Validasi input via Service
        $validation = $this->bookingService->validateBookingInput($formData);
        if (!$validation['valid']) {
            echo json_encode(['success' => false, 'message' => $validation['message']]);
            return;
        }

        // Cari ID lab berdasarkan nama
        $labId = $this->bookingService->getLabIdByName($formData['labName']);
        if (!$labId) {
            echo json_encode(['success' => false, 'message' => 'Laboratorium tidak ditemukan']);
            return;
        }

        // Cek konflik dengan jadwal tetap (recurring schedules)
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

        // Cek konflik dengan booking yang sudah ada dan validasi waktu
        try {
            // VALIDASI: Cek apakah waktu sudah lewat
            $currentDateTime = time();
            $requestedStart = strtotime($formData['tanggal'] . ' ' . $formData['jamMulai']);

            if ($requestedStart < $currentDateTime) {
                echo json_encode(['success' => false, 'message' => 'Tidak dapat melakukan booking untuk waktu yang sudah terlewati.']);
                return;
            }

            // VALIDASI: Jam Operasional
            $startCheck = substr($formData['jamMulai'], 0, 5);
            $endCheck = substr($formData['jamSelesai'], 0, 5);

            if ($startCheck < self::JAM_BUKA_LAB || $endCheck > self::JAM_TUTUP_LAB) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Peminjaman hanya diperbolehkan pada jam operasional lab (' . self::JAM_BUKA_LAB . ' - ' . self::JAM_TUTUP_LAB . ').'
                ]);
                return;
            }

            if ($this->bookingService->checkBookingConflict($labId, $formData['tanggal'], $formData['jamMulai'], $formData['jamSelesai'])) {
                echo json_encode(['success' => false, 'message' => 'Waktu yang dipilih sudah dibooking oleh user lain']);
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
            if ($this->bookingService->createBooking($bookingData)) {
                
                // ========== FITUR BARU: NOTIFIKASI WHATSAPP UNTUK DOSEN ==========
                try {
                    // Cek user Dosen
                    $userModel = $this->model('UserModel');
                    $currentUser = $userModel->getUserById($_SESSION['user_id']);
                    
                    if (isset($currentUser['status']) && $currentUser['status'] === 'Dosen') {
                        // Logic Get Target Number
                        $labInfo = $this->bookingService->getLabById($labId);
                        $targetNumber = null;
                        
                        if (!empty($labInfo['email_pic'])) {
                            $koordinator = $userModel->getUserByEmail($labInfo['email_pic']);
                            if ($koordinator && !empty($koordinator['telepon'])) {
                                $targetNumber = $koordinator['telepon'];
                            }
                        }
                        
                        if (empty($targetNumber)) {
                            $targetNumber = getenv('WA_ADMIN') ?: ($_ENV['WA_ADMIN'] ?? '');
                        }
                        
                        if (!empty($targetNumber)) {
                            $tanggal = date('d F Y', strtotime($formData['tanggal']));
                            $pesan  = "📢 *NOTIFIKASI BOOKING LABORATORIUM*\n\n" .
                                      "Seorang dosen telah melakukan booking:\n\n" .
                                      "👤 *Nama:* " . $formData['namaPeminjam'] . "\n" .
                                      "🏛️ *Laboratorium:* " . $formData['labName'] . "\n" .
                                      "📅 *Tanggal:* " . $tanggal . "\n" .
                                      "🕐 *Waktu:* " . $formData['jamMulai'] . " - " . $formData['jamSelesai'] . " WIB\n" .
                                      "📝 *Kegiatan:* " . $formData['namaKegiatan'] . "\n\n" .
                                      "✅ *Status:* Disetujui Otomatis\n\n" .
                                      "_Silakan Segera Persiapkan Ruangan yang di pinjam._\n" .
                                      "_Sistem Peminjaman Lab ICLABS_";
                            
                            // Send via Service
                            $this->waService->kirimPesanFonnte($targetNumber, $pesan);
                        }
                    }
                } catch (Exception $e) {
                    // Ignore errors
                }
                
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

        // Include helpers (Existing legacy helper, we might want to refactor this later or move to service)
        // Since `getLabSlots` seems to rely on global functions from `helpers.php`, I will keep this include 
        // BUT I must ensure `helpers.php` doesn't conflict. 
        // The previous code had: include_once __DIR__ . '/../views/internal/booking/helpers.php';
        // This file likely contains `getSlotKosong` and `getSortedSlots`.
        include_once __DIR__ . '/../views/internal/booking/helpers.php';

        // 1. Ambil data lab info (untuk nama lab di tombol booking)
        // Using Service now
        $labInfo = $this->bookingService->getLabById($labId);
        $labName = $labInfo['nama_ruangan'] ?? '';

        // 2. Ambil Jadwal Tetap
        $jadwalTetapRaw = $this->bookingService->getFilteredSchedules($date);
        $jadwalLab = [];
        foreach ($jadwalTetapRaw as $j) {
            if ($j['lab_id'] == $labId) {
                $jadwalLab[] = $j;
            }
        }

        // 3. Ambil Peminjaman
        $peminjamanRaw = $this->bookingService->getBookingsInRange($date);
        $peminjamanLab = [];
        foreach ($peminjamanRaw as $p) {
            if ($p['lab_id'] == $labId && $p['tanggal'] == $date) {
                $peminjamanLab[] = $p;
            }
        }

        // 4. Hitung Slot Kosong & Sort (Using helpers included above)
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

    /**
     * Halaman Profil User
     */
    public function profile()
    {
        $data['judul'] = 'Profil Saya';
        $data['active_page'] = 'profile'; 

        // Ambil data user
        $userModel = $this->model('UserModel');
        $data['user'] = $userModel->getUserById($_SESSION['user_id']);

        $this->view('components/internal_head', $data);
        $this->view('components/internal_navbar', $data);
        $this->view('components/internal_sidebar', $data);
        $this->view('internal/profile/index', $data);
        $this->view('components/internal_footer');
    }

    /**
     * Proses Update Profil User
     */
    public function prosesUpdateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $data = [
                'id' => $_SESSION['user_id'],
                'nama' => $_POST['nama'],
                'email' => $_POST['email'],
                'telepon' => $_POST['telepon'],
                'password' => $_POST['password_baru']
            ];

            $userModel = $this->model('UserModel');
            
            if ($userModel->updateUserProfile($data) > 0) {
                // Update session nama jika berubah
                $_SESSION['nama'] = $data['nama'];
                Flasher::setFlash('Berhasil', 'Profil berhasil diperbarui.', 'success');
            } else {
                Flasher::setFlash('Info', 'Tidak ada perubahan data.', 'warning');
            }

            header('Location: ' . BASE_URL . '/internal/profile');
            exit;
        }
    }
}
