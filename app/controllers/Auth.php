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

    public function prosesLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // 1. Panggil Model untuk cari email
            $user = $this->model('User_model')->getUserByEmail($email);

            // 2. Jika user ada
            if ($user) {
                // 3. Cek Password
                if (password_verify($password, $user['password'])) {
                    
                    // 4. Set Session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['nama'] = $user['nama_lengkap'];
                    $_SESSION['role'] = $user['role']; // Pastikan kolom role ada di DB

                    // 5. Arahkan sesuai Role
                    $this->redirectBasedOnRole($user['role']);

                } else {
                    // Password Salah
                    Flasher::setFlash('Gagal', 'Password salah.', 'danger');
                    header('Location: ' . BASE_URL . '/auth');
                    exit;
                }
            } else {
                // Email tidak ditemukan
                Flasher::setFlash('Gagal', 'Email tidak terdaftar.', 'danger');
                header('Location: ' . BASE_URL . '/auth');
                exit;
            }
        }
    }

    // Fungsi helper untuk mengarahkan user
    private function redirectBasedOnRole($role)
    {
        switch ($role) {
            case 'admin':
                header('Location: ' . BASE_URL . '/admin');
                break;
            case 'internal': // Dosen/Laboran
                header('Location: ' . BASE_URL . '/internal');
                break;
            case 'external': // Peminjam Luar
                header('Location: ' . BASE_URL . '/external');
                break;
            default:
                header('Location: ' . BASE_URL . '/auth');
                break;
        }
        exit;
    }

    public function logout()
    {
        session_destroy();
        header('Location: ' . BASE_URL . '/auth');
        exit;
    }
}