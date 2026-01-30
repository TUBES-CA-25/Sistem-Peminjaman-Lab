<?php

class External extends Controller
{
    public function __construct()
    {
        // Proteksi dengan Error 401 & 403
        if (!isset($_SESSION['user_id'])) {
            // Belum login → Error 401
            http_response_code(401);
            require_once __DIR__ . '/../views/errors/401.php';
            exit;
        }

        if ($_SESSION['role'] !== 'external') {
            // Sudah login tapi bukan external → Error 403
            http_response_code(403);
            require_once __DIR__ . '/../views/errors/403.php';
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'Dashboard Peminjaman';
        $data['active_menu'] = 'dashboard'; // Untuk sidebar active state

        // 1. PERBAIKAN: Ambil Data User untuk Auto-fill Modal Tambah
        $data['user'] = $this->model('UserModel')->getUserById($_SESSION['user_id']);

        // 2. Ambil Riwayat Pengajuan (Filter berdasarkan User ID di Model)
        // FIXED: Pass user_id untuk filter
        $data['riwayat'] = $this->model('PengajuanModel')->getRiwayat($_SESSION['user_id']);

        $data['active_page'] = 'external';

        $this->view('components/external_header', $data);
        $this->view('components/external_navbar', $data);
        $this->view('external/index', $data);
    }

    public function detail($id = null)
    {
        if (is_null($id)) {
            header('Location: ' . BASE_URL . '/external');
            exit;
        }

        $data['judul'] = 'Detail Pengajuan';
        $data['peminjaman'] = $this->model('PengajuanModel')->getById($id);

        $this->view('components/external_header', $data);
        $this->view('components/external_navbar', $data);
        $this->view('external/detail', $data);
        $this->view('components/footer');
    }

    public function ajukan()
    {
        $data['judul'] = 'Form Pengajuan Baru';
        // Tambahkan data user juga disini jika halaman ini dipakai terpisah
        $data['user'] = $this->model('UserModel')->getUserById($_SESSION['user_id']);

        $this->view('components/external_header', $data);
        $this->view('components/external_navbar', $data);
        $this->view('external/form_pengajuan', $data);
        $this->view('components/footer');
    }

    // --- PROSES TAMBAH (UPLOAD FILE & SIMPAN DB) ---
    public function prosesPinjam()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // 1. Handle File Upload
            $file_proposal = $this->uploadFile($_FILES['proposal']);

            if (!$file_proposal) {
                echo "<script>window.history.back();</script>";
                return;
            }

            // 2. Susun Data
            $data = [
                'user_id'        => $_SESSION['user_id'],
                'nama_lengkap'   => $_POST['nama'],
                'email'          => $_POST['email'],
                'telepon'        => $_POST['telepon'],
                'jumlah_peserta' => $_POST['jumlah_peserta'],
                'nama_kegiatan'  => $_POST['nama_kegiatan'],
                'tgl_mulai'      => $_POST['tgl_mulai'],
                'tgl_selesai'    => $_POST['tgl_selesai'],
                'file_proposal'  => $file_proposal
            ];

            // 3. Kirim ke Model
            if ($this->model('PengajuanModel')->tambahPengajuan($data) > 0) {
                $nomorAdmin = WA_ADMIN_UTAMA;
                // Susun Pesan
                $pesan  = "*🔔 PENGAJUAN BARU MASUK*\n\n";
                $pesan .= "Halo Admin, ada pengajuan peminjaman baru:\n";
                $pesan .= "👤 Nama: " . $_POST['nama'] . "\n";
                $pesan .= "📞 WA: " . $_POST['telepon'] . "\n";
                $pesan .= "📅 Tgl: " . $_POST['tgl_mulai'] . " s/d " . $_POST['tgl_selesai'] . "\n";
                $pesan .= "📝 Kegiatan: " . $_POST['nama_kegiatan'] . "\n\n";
                $pesan .= "Mohon cek dashboard untuk verifikasi.";

                // Eksekusi kirim pesan
                $this->kirimPesanFonnte($nomorAdmin, $pesan);
                
                Flasher::setFlash('Berhasil', 'Pengajuan berhasil dikirim.', 'success');
                header('Location: ' . BASE_URL . '/external');
                exit;
            } else {
                Flasher::setFlash('Gagal', 'Terjadi kesalahan saat menyimpan data.', 'danger');
                header('Location: ' . BASE_URL . '/external');
                exit;
            }
        }
    }

    // --- PROSES UPDATE ---
    public function updatePinjam()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id' => $_POST['id'],
                'nama_kegiatan' => $_POST['nama_kegiatan'],
                'jumlah_peserta' => $_POST['jumlah_peserta'],
                'tgl_mulai' => $_POST['tgl_mulai'],
                'tgl_selesai' => $_POST['tgl_selesai']
            ];

            if ($this->model('PengajuanModel')->updatePengajuan($data) > 0) {
                Flasher::setFlash('Berhasil', 'Data pengajuan diperbarui.', 'success');
                header('Location: ' . BASE_URL . '/external');
                exit;
            } else {
                // Jika tidak ada perubahan, tetap redirect tapi tanpa pesan sukses heboh
                header('Location: ' . BASE_URL . '/external');
                exit;
            }
        }
    }

    // --- PROSES HAPUS ---
    public function hapus($id)
    {
        // FIXED: Pass user_id untuk ownership check
        if ($this->model('PengajuanModel')->hapusPengajuan($id, $_SESSION['user_id']) > 0) {
            Flasher::setFlash('Berhasil', 'Pengajuan telah dihapus.', 'success');
        } else {
            Flasher::setFlash('Gagal', 'Gagal menghapus data (mungkin bukan milik Anda).', 'danger');
        }
        header('Location: ' . BASE_URL . '/external');
        exit;
    }

    // --- HELPER UPLOAD ---
    private function uploadFile($file)
    {
        $namaFile = $file['name'];
        $ukuranFile = $file['size'];
        $error = $file['error'];
        $tmpName = $file['tmp_name'];

        if ($error === 4) {
            echo "<script>alert('Pilih file proposal terlebih dahulu!');</script>";
            return false;
        }

        $ekstensiValid = ['pdf', 'doc', 'docx'];
        $ekstensiFile = explode('.', $namaFile);
        $ekstensiFile = strtolower(end($ekstensiFile));

        if (!in_array($ekstensiFile, $ekstensiValid)) {
            echo "<script>alert('Format file tidak valid! Gunakan PDF/DOC/DOCX');</script>";
            return false;
        }

        if ($ukuranFile > 5242880) { // 5MB
            echo "<script>alert('Ukuran file terlalu besar (Max 5MB).');</script>";
            return false;
        }

        // Generate nama unik berdasarkan user ID dan timestamp
        $userId = $_SESSION['user_id'];
        $namaFileBaru = 'proposal_' . $userId . '_' . time() . '.' . $ekstensiFile;

        // FIXED: PRIVATE folder (di luar public, akses via controller proxy) -> Moved to public/storage per user request
        $targetDir = __DIR__ . '/../../public/storage/uploads/proposals/';

        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $tujuan = $targetDir . $namaFileBaru;

        if (move_uploaded_file($tmpName, $tujuan)) {
            return $namaFileBaru; // Simpan filename saja ke DB
        } else {
            echo "<script>alert('Gagal mengupload file ke server.');</script>";
            return false;
        }
    }


    // Method untuk menampilkan halaman profile
    public function profile()
    {
        // Cek login
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/auth');
            exit;
        }

        $data['judul'] = 'Profil Saya';
        $data['active_menu'] = 'profile'; // Untuk sidebar active state
        $data['user'] = $this->model('UserModel')->getUserById($_SESSION['user_id']);

        $this->view('components/external_header', $data);
        $this->view('components/external_navbar', $data);
        $this->view('external/profile', $data);
    }

    // Method untuk memproses update profil
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

            if ($this->model('UserModel')->updateUserProfile($data) > 0) {
                $_SESSION['nama'] = $data['nama'];
                Flasher::setFlash('Berhasil', 'Profil berhasil diperbarui.', 'success');
            } else {
                Flasher::setFlash('Info', 'Tidak ada perubahan data.', 'warning');
            }

            header('Location: ' . BASE_URL . '/external/profile');
            exit;
        }
    }

    // Method untuk logout
    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . '/auth');
        exit;
    }

    private function kirimPesanFonnte($target, $pesan)
    {
        $token = FONNTE_TOKEN; 

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.fonnte.com/send',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0, 
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array(
            'target' => $target,
            'message' => $pesan,
            'countryCode' => '62',
          ),
          CURLOPT_HTTPHEADER => array(
            "Authorization: $token"
          ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        
        return $response;
    }
}