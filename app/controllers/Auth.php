<?php

class Auth extends Controller
{
    public function index()
    {
        // Jika user sudah login, lempar langsung ke halaman sesuai role
        if (isset($_SESSION['user_id'])) {
            $this->redirectBasedOnRole($_SESSION['role']);
            exit;
        }

        $data['judul'] = 'Login | Peminjaman Lab';
        $this->view('auth/login', $data);
    }

    // --- FUNGSI LOGIN (PENTING: Tadi Hilang) ---
    public function prosesLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // 1. Cari user berdasarkan email
            $user = $this->model('UserModel')->getUserByEmail($email);

            // 2. Jika user ada
            if ($user) {
                // 3. Cek Password
                if (password_verify($password, $user['password'])) {
                    // 4. Set Session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['nama'] = $user['nama'];
                    $_SESSION['role'] = $user['role'];

                    // 5. Redirect sesuai role
                    $this->redirectBasedOnRole($user['role']);
                } else {
                    Flasher::setFlash('Gagal', 'Password salah.', 'danger');
                    header('Location: ' . BASE_URL . '/auth');
                    exit;
                }
            } else {
                Flasher::setFlash('Gagal', 'Email tidak ditemukan.', 'danger');
                header('Location: ' . BASE_URL . '/auth');
                exit;
            }
        }
    }

    public function register()
    {
        $data['judul'] = 'Daftar Akun Baru';
        $this->view('auth/register', $data);
    }

    public function prosesRegister()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // 1. Ambil Data Input
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            // 2. Validasi: Cek apakah email sudah terdaftar
            if ($this->model('UserModel')->getUserByEmail($email)) {
                Flasher::setFlash('Gagal', 'Email sudah terdaftar. Silakan login.', 'danger');
                header('Location: ' . BASE_URL . '/auth/register');
                exit;
            }

            // 3. Validasi: Cek apakah password sama
            if ($password !== $confirm_password) {
                Flasher::setFlash('Gagal', 'Konfirmasi password tidak sesuai.', 'danger');
                header('Location: ' . BASE_URL . '/auth/register');
                exit;
            }

            // 4. Siapkan Data untuk Model
            $data = [
                'nama' => $_POST['nama'],
                'email' => $email,
                'telepon' => $_POST['telepon'] ?? '-',
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ];

            // 5. Simpan ke Database
            if ($this->model('UserModel')->tambahUser($data) > 0) {
                Flasher::setFlash('Berhasil', 'Akun berhasil dibuat. Silakan login.', 'success');
                header('Location: ' . BASE_URL . '/auth');
                exit;
            } else {
                Flasher::setFlash('Gagal', 'Terjadi kesalahan sistem.', 'danger');
                header('Location: ' . BASE_URL . '/auth/register');
                exit;
            }
        }
    }

    public function logout()
    {
        // Hapus semua session
        session_destroy();
        session_unset();

        // Kembalikan ke halaman login
        header('Location: ' . BASE_URL . '/auth');
        exit;
    }

    // Fungsi helper (Private) untuk mengarahkan user berdasarkan role-nya
    private function redirectBasedOnRole($role)
    {
        switch ($role) {
            case 'admin':
                header('Location: ' . BASE_URL . '/admin');
                break;
            case 'internal': // Dosen/Laboran
                header('Location: ' . BASE_URL . '/internal');
                break;
            case 'external': // Mahasiswa/Umum
                header('Location: ' . BASE_URL . '/external');
                break;
            default:
                header('Location: ' . BASE_URL . '/auth');
                break;
        }
        exit;
    }
}