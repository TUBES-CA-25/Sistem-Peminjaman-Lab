<?php

class External extends Controller
{
    public function __construct()
    {
        // Pastikan session aktif & user sudah login
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/auth');
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'Dashboard Peminjaman';
        $data['active_menu'] = 'dashboard'; // Untuk sidebar active state
        
        // 1. PERBAIKAN: Ambil Data User untuk Auto-fill Modal Tambah
        $data['user'] = $this->model('User_model')->getUserById($_SESSION['user_id']);
        
        // 2. Ambil Riwayat Pengajuan (Filter berdasarkan User ID di Model)
        // Pastikan di Pengajuan_model, query-nya pakai WHERE user_id = ...
        $data['riwayat'] = $this->model('Pengajuan_model')->getRiwayat($_SESSION['user_id']); 
        
        $data['active_page'] = 'external';

        $this->view('components/header', $data);
        $this->view('components/external_navbar', $data);
        $this->view('external/index', $data);
    }

    public function detail($id = null)
    {
        if(is_null($id)) {
            header('Location: ' . BASE_URL . '/external');
            exit;
        }

        $data['judul'] = 'Detail Pengajuan';
        $data['peminjaman'] = $this->model('Pengajuan_model')->getById($id);

        $this->view('components/header', $data);
        $this->view('components/external_navbar', $data);
        $this->view('external/detail', $data);
        $this->view('components/footer');
    }

    public function ajukan()
    {
        $data['judul'] = 'Form Pengajuan Baru';
        // Tambahkan data user juga disini jika halaman ini dipakai terpisah
        $data['user'] = $this->model('User_model')->getUserById($_SESSION['user_id']);
        
        $this->view('components/header', $data);
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
                // PERBAIKAN: Masukkan User ID agar data terhubung ke akun
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
            if ($this->model('Pengajuan_model')->tambahPengajuan($data) > 0) {
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
                'id'             => $_POST['id'],
                'nama_kegiatan'  => $_POST['nama_kegiatan'],
                'jumlah_peserta' => $_POST['jumlah_peserta'],
                'tgl_mulai'      => $_POST['tgl_mulai'],
                'tgl_selesai'    => $_POST['tgl_selesai']
            ];

            if ($this->model('Pengajuan_model')->updatePengajuan($data) > 0) {
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
        // Validasi: Pastikan yang dihapus adalah milik user yang sedang login (opsional tapi disarankan di Model)
        if ($this->model('Pengajuan_model')->hapusPengajuan($id) > 0) {
            Flasher::setFlash('Berhasil', 'Pengajuan telah dihapus.', 'success');
        } else {
            Flasher::setFlash('Gagal', 'Gagal menghapus data.', 'danger');
        }
        header('Location: ' . BASE_URL . '/external');
        exit;
    }

    // --- HELPER UPLOAD ---
    private function uploadFile($file)
    {
        $namaFile   = $file['name'];
        $ukuranFile = $file['size'];
        $error      = $file['error'];
        $tmpName    = $file['tmp_name'];

        if ($error === 4) {
            echo "<script>alert('Pilih file proposal terlebih dahulu!');</script>";
            return false;
        }

        $ekstensiValid = ['pdf', 'doc', 'docx']; // Bolehkan doc/docx sesuai view
        $ekstensiFile  = explode('.', $namaFile);
        $ekstensiFile  = strtolower(end($ekstensiFile));

        if (!in_array($ekstensiFile, $ekstensiValid)) {
            echo "<script>alert('Format file tidak valid! Gunakan PDF/DOC/DOCX');</script>";
            return false;
        }

        if ($ukuranFile > 5000000) { // Update ke 5MB biar aman
            echo "<script>alert('Ukuran file terlalu besar (Max 5MB).');</script>";
            return false;
        }

        $namaFileBaru = uniqid() . '.' . $ekstensiFile;
        $targetDir = 'public/uploads/';
        
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $tujuan = $targetDir . $namaFileBaru;

        if(move_uploaded_file($tmpName, $tujuan)) {
            return $namaFileBaru;
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
        $data['user'] = $this->model('User_model')->getUserById($_SESSION['user_id']);

        $this->view('components/header', $data);
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

            if ($this->model('User_model')->updateUserProfile($data) > 0) {
                $_SESSION['nama'] = $data['nama'];
                Flasher::setFlash('Berhasil', 'Profil berhasil diperbarui.', 'success');
            } else {
                Flasher::setFlash('Info', 'Tidak ada perubahan data.', 'warning');
            }

            header('Location: ' . BASE_URL . '/external/profile');
            exit;
        }
    }
}