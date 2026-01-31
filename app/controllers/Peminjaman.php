<?php

class Peminjaman extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            require_once __DIR__ . '/../views/errors/401.php';
            exit;
        }

        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            require_once __DIR__ . '/../views/errors/403.php';
            exit;
        }
    }

    public function index()
    {
        $peminjamanModel = $this->model('PeminjamanModel');
        $ruanganModel = $this->model('RuanganModel');
        $jadwalModel = $this->model('JadwalModel');

        // Handle GET API Requests (e.g. Fetch for Edit)
        if (isset($_GET['action']) && $_GET['action'] == 'get' && isset($_GET['id'])) {
            $id = $_GET['id'];
            $data = $peminjamanModel->getById($id);
            header('Content-Type: application/json');
            echo json_encode($data);
            exit;
        }

        // Handle POST Requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost();
            return;
        }

        $data = [
            'active_page' => 'peminjaman',
            'bookings' => $peminjamanModel->getAll(),
            'labs' => $ruanganModel->getAll(),
            // We might need raw schedule data for JS to handle frontend conflict checks too
            'fixed_schedules' => $jadwalModel->getAll()
        ];

        $this->view('components/admin_head', $data);
        $this->view('components/admin_navbar', $data);
        $this->view('components/admin_sidebar', $data);
        $this->view('admin/peminjaman/index', $data);
        $this->view('components/admin_footer', $data);
    }

    private function handlePost()
    {
        $peminjamanModel = $this->model('PeminjamanModel');
        $jadwalModel = $this->model('JadwalModel');
        $action = $_POST['action'] ?? '';
        $isAjax = isset($_POST['ajax']) && $_POST['ajax'] == '1';

        if ($action === 'create' || $action === 'update') {
            // Mapping fields
            $tanggal = $_POST['tanggal'] ?? ''; // Format YYYY-MM-DD
            $labId = $_POST['lab'] ?? '';
            $jamMulai = $_POST['jamMulai'] ?? '';
            $jamSelesai = $_POST['jamSelesai'] ?? '';
            $tipe = $_POST['tipe'] ?? 'eksternal'; // internal / eksternal
            $id = $_POST['id'] ?? null;

            // Logic:
            // - Internal: Check conflict. If safe -> status = disetujui.
            // - External (Admin Input): No check needed (force) or simple check. Admin usually forces.
            //   But requirements say "admin adds schedule", implying admin has authority.
            //   Let's check conflict anyway to warn, or just allow.
            //   For NOW, we enforce conflict check for INTERNAL only or BOTH?
            //   User said: "internal... langsung acc selagi tidak bertabrakan... user external... admin yang akan menambahkan"
            //   So Admin adding External means it IS Approved.
            // Let's do conflict check for SAFETY for everyone.

            // ===== VALIDASI TAMBAHAN REQUESTED =====
            // Peminjaman Internal di Admin (atau semua via Admin) harus ikut aturan jam operasional dan tidak boleh backdate.
            // Requirement: "peminjaman internal di admin itu Hanya bisa booking pukul 07:00 - 18:20 dan jadwal tidak bisa booking jika jam nya sudah lewat"

            // 1. Cek Jam Operasional
            $startCheck = substr($jamMulai, 0, 5);
            $endCheck = substr($jamSelesai, 0, 5);
            if ($startCheck < '07:00' || $endCheck > '18:20') {
                if ($isAjax) {
                    echo json_encode(['success' => false, 'message' => 'Gagal: Jam operasional lab hanya dari 07:00 s/d 18:20.']);
                    exit;
                }
                header("Location: " . BASE_URL . "/peminjaman?status=error&msg=Gagal: Jam operasional lab hanya dari 07:00 s/d 18:20.");
                exit;
            }

            // 2. Cek Backdate (Waktu lampau)
            // Hanya cek jika ini CREATE baru atau UPDATE tanggal/jam
            $bookingTimestamp = strtotime($tanggal . ' ' . $jamMulai);
            if ($bookingTimestamp < time()) {
                if ($isAjax) {
                    echo json_encode(['success' => false, 'message' => 'Gagal: Waktu booking sudah terlewat. Mohon pilih waktu di masa depan.']);
                    exit;
                }
                header("Location: " . BASE_URL . "/peminjaman?status=error&msg=Gagal: Waktu booking sudah terlewat. Mohon pilih waktu di masa depan.");
                exit;
            }
            // =======================================

            // Check Conflict with Fixed Schedule
            $dayName = strtolower($this->getDayName($tanggal)); // senin, selasa...
            $isFixedConflict = $jadwalModel->checkConflict($labId, $dayName, $jamMulai, $jamSelesai);

            // Check Conflict with Other Bookings
            $isBookingConflict = $peminjamanModel->checkConflict($labId, $tanggal, $jamMulai, $jamSelesai, ($action == 'update' ? $id : null));

            // Only block if conflicting with another BOOKING.
            // Fixed Schedule conflict is allowed (Admin overrides -> "Jadwal Tergeser")
            if ($isBookingConflict) {
                // Check if Override is requested
                $override = $_POST['override'] ?? false;

                if ($override) {
                    // Shift conflicting bookings to 'tergeser'
                    $peminjamanModel->shiftConflictingBookings($labId, $tanggal, $jamMulai, $jamSelesai);
                    // Proceed to create the new booking (it will succeed now or overlap is gone)
                } else {
                    if ($isAjax) {
                        echo json_encode(['success' => false, 'message' => 'Jadwal bentrok dengan peminjaman lain!']);
                        exit;
                    }
                    header("Location: " . BASE_URL . "/peminjaman?status=error&msg=Jadwal bentrok dengan peminjaman lain!");
                    exit;
                }
            }

            // Status is Approved
            $status = 'disetujui'; // Default approved (Internal auto-acc, External by Admin)

            $data = [
                'user_id' => null, // Admin input, maybe current admin ID or null if only tracking names
                'lab_id' => $labId,
                'tanggal_peminjaman' => $tanggal,
                'jam_mulai' => $jamMulai,
                'jam_selesai' => $jamSelesai,
                'nama_peminjam' => $_POST['nama_peminjam'] ?? '-',
                'kegiatan' => $_POST['kegiatan'] ?? '-', // Instansi/Kegiatan
                'tipe' => $tipe,
                'status' => $status
            ];

            if ($action === 'create') {
                if ($peminjamanModel->create($data)) {
                    if ($isAjax) {
                        echo json_encode(['success' => true]);
                        exit;
                    }
                    header("Location: " . BASE_URL . "/peminjaman?status=success&msg=Peminjaman berhasil ditambahkan");
                    exit;
                } else {
                    if ($isAjax) {
                        echo json_encode(['success' => false, 'message' => 'Gagal menambahkan peminjaman (DB Error)']);
                        exit;
                    }
                    header("Location: " . BASE_URL . "/peminjaman?status=error&msg=Gagal menambahkan peminjaman");
                    exit;
                }
            } elseif ($action === 'update' && $id) {
                // Prepare data for update (keys match Model expectations)
                $updateData = [
                    'lab_id' => $labId,
                    'tanggal' => $tanggal,
                    'jam_mulai' => $jamMulai,
                    'jam_selesai' => $jamSelesai,
                    'nama_peminjam' => $_POST['nama_peminjam'] ?? '-',
                    'kegiatan' => $_POST['kegiatan'] ?? '-'
                ];

                if ($peminjamanModel->update($id, $updateData)) {
                    if ($isAjax) {
                        echo json_encode(['success' => true]);
                        exit;
                    }
                    header("Location: " . BASE_URL . "/peminjaman?status=success&msg=Peminjaman berhasil diupdate");
                    exit;
                } else {
                    if ($isAjax) {
                        echo json_encode(['success' => true, 'message' => 'Data disimpan (Tidak ada perubahan)']);
                        // Often rowCount is 0 if no changes, treat as success or warning
                        exit;
                    }
                    header("Location: " . BASE_URL . "/peminjaman?status=success&msg=Peminjaman diupdate (Mungkin tidak ada perubahan)");
                    exit;
                }
            }

        } elseif ($action === 'delete') {
            $id = $_POST['id'] ?? 0;
            if ($peminjamanModel->delete($id)) {
                if ($isAjax) {
                    echo json_encode(['success' => true]);
                    exit;
                }
                header("Location: " . BASE_URL . "/peminjaman?status=success&msg=Peminjaman dihapus");
                exit;
            } else {
                if ($isAjax) {
                    echo json_encode(['success' => false, 'message' => 'Gagal menghapus peminjaman']);
                    exit;
                }
                header("Location: " . BASE_URL . "/peminjaman?status=error&msg=Gagal menghapus peminjaman");
                exit;
            }
        } elseif ($action === 'approve') {
            // If we implement 'Request' feature later where users request manually
            $id = $_POST['id'] ?? 0;
            if ($peminjamanModel->updateStatus($id, 'disetujui')) {
                if ($isAjax) {
                    echo json_encode(['success' => true]);
                    exit;
                }
                header("Location: " . BASE_URL . "/peminjaman?status=success&msg=Peminjaman disetujui");
                exit;
            }
        }
    }

    private function getDayName($dateStr)
    {
        $timestamp = strtotime($dateStr);
        $days = ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
        return $days[date('w', $timestamp)];
    }
}