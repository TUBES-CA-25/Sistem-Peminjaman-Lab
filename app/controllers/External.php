<?php

class External extends Controller
{
    public function __construct()
    {
        // if (!isset($_SESSION['user_id'])) {
        //     header('Location: ' . BASE_URL . '/auth/login');
        //     exit;
        // }
    }

    public function index()
    {
        $data['judul'] = 'Dashboard Peminjaman';
        $data['riwayat'] = $this->model('Pengajuan_model')->getRiwayat();

        $this->view('components/header', $data);
        $this->view('components/external_navbar', $data);
        $this->view('external/index', $data);
        $this->view('components/footer');
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
            // Fungsi ini akan mengembalikan NAMA FILE jika sukses, atau FALSE jika gagal
            $file_proposal = $this->uploadFile($_FILES['proposal']);
            
            if (!$file_proposal) {
                // Pesan error sudah dihandle di dalam function uploadFile (alert js)
                echo "<script>window.history.back();</script>";
                return;
            }

            // 2. Susun Data
            $data = [
                'nama_lengkap'   => $_POST['nama'],
                'email'          => $_POST['email'],
                'telepon'        => $_POST['telepon'],
                'jumlah_peserta' => $_POST['jumlah_peserta'],
                'nama_kegiatan'  => $_POST['nama_kegiatan'],
                'tgl_mulai'      => $_POST['tgl_mulai'],
                'tgl_selesai'    => $_POST['tgl_selesai'],
                'file_proposal'  => $file_proposal // Yang disimpan di DB hanya string nama file (contoh: 65a8b.pdf)
            ];

            // 3. Kirim ke Model
            if ($this->model('Pengajuan_model')->tambahPengajuan($data) > 0) {
                echo "<script>alert('Berhasil! Pengajuan dikirim.'); window.location.href='" . BASE_URL . "/external';</script>";
            } else {
                echo "<script>alert('Gagal menyimpan ke database.'); window.history.back();</script>";
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
                echo "<script>alert('Data berhasil diperbarui!'); window.location.href='" . BASE_URL . "/external';</script>";
            } else {
                header('Location: ' . BASE_URL . '/external');
            }
        }
    }

    // --- PROSES HAPUS ---
    public function hapus($id)
    {
        if ($this->model('Pengajuan_model')->hapusPengajuan($id) > 0) {
            echo "<script>alert('Pengajuan dibatalkan/dihapus.'); window.location.href='" . BASE_URL . "/external';</script>";
        } else {
            echo "<script>alert('Gagal menghapus.'); window.location.href='" . BASE_URL . "/external';</script>";
        }
    }

    // --- HELPER UPLOAD (INTI PERBAIKAN) ---
    private function uploadFile($file)
    {
        $namaFile   = $file['name'];
        $ukuranFile = $file['size'];
        $error      = $file['error'];
        $tmpName    = $file['tmp_name'];

        // Cek error upload
        if ($error === 4) {
            echo "<script>alert('Pilih file proposal terlebih dahulu!');</script>";
            return false;
        }

        $ekstensiValid = ['pdf'];
        $ekstensiFile  = explode('.', $namaFile);
        $ekstensiFile  = strtolower(end($ekstensiFile));

        if (!in_array($ekstensiFile, $ekstensiValid)) {
            echo "<script>alert('Format file tidak valid! Gunakan PDF');</script>";
            return false;
        }

        if ($ukuranFile > 3000000) {
            echo "<script>alert('Ukuran file terlalu besar (Max 3MB).');</script>";
            return false;
        }

        // Generate nama file baru (agar tidak duplikat)
        $namaFileBaru = uniqid() . '.' . $ekstensiFile;

        // Tentukan folder tujuan (public/uploads/)
        $targetDir = 'public/uploads/';
        
        // PENTING: Cek apakah folder ada, jika tidak, buat foldernya!
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Gabungkan folder + nama file
        $tujuan = $targetDir . $namaFileBaru;

        // Pindahkan file dari folder sementara (tmp) ke folder tujuan
        if(move_uploaded_file($tmpName, $tujuan)) {
            // Kembalikan nama file baru untuk disimpan di database
            return $namaFileBaru;
        } else {
            echo "<script>alert('Gagal mengupload file ke server.');</script>";
            return false;
        }
    }


    public function profile()
    {
        // 1. Cek apakah user sudah login (Wajib)
        // if (!isset($_SESSION['user_id'])) { header('Location: ' . BASE_URL . '/login'); exit; }

        $data['judul'] = 'Profil Saya';
        
        // 2. Ambil ID dari Session
        $userId = $_SESSION['user_id']; // Pastikan session user_id sudah diset saat login
        
        // 3. Ambil data user terbaru dari database
        $data['user'] = $this->model('User_model')->getUserById($userId);

        $this->view('components/header', $data);
        $this->view('components/external_navbar', $data);
        $this->view('external/profile', $data); // Kita akan buat file ini
        $this->view('components/footer');
    }

    public function prosesUpdateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Ambil ID dari input hidden atau session (lebih aman session)
            $data = [
                'id' => $_SESSION['user_id'],
                'nama' => $_POST['nama'],
                'email' => $_POST['email'],
                'telepon' => $_POST['telepon']
            ];

            // 1. Update Data Diri
            if ($this->model('User_model')->updateProfile($data) > 0) {
                // Berhasil Update Data
                // (Anda bisa set Flash Message disini jika punya fitur Flasher)
            }

            // 2. Cek apakah user ingin ganti password
            if (!empty($_POST['password_baru'])) {
                $this->model('User_model')->updatePassword($data['id'], $_POST['password_baru']);
            }

            // Redirect kembali ke halaman profile
            header('Location: ' . BASE_URL . '/external/profile');
            exit;
        }
    }
}