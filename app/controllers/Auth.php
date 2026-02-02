<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Auth extends Controller
{
    // Whitelist: method yang boleh diakses tanpa login
    private $publicMethods = [
        'index',
        'prosesLogin',
        'register',
        'prosesRegister',
        'forgot',
        'sendResetLink',
        'reset',
        'processReset',
        'emailSent',
        'logout'
    ];

    public function __construct()
    {
        $requestUri = $_SERVER['REQUEST_URI'] ?? '';

        if (strpos($requestUri, '/auth/reset') !== false) {
            return;
        }

        $url = $_GET['url'] ?? '';
        $url = strtok($url, '?');
        $parts = explode('/', trim($url, '/'));
        $currentMethod = $parts[1] ?? 'index';

        // Sekarang logout akan masuk publicMethods, tidak di-redirect!
        if (isset($_SESSION['user_id']) && !in_array($currentMethod, $this->publicMethods)) {
            $this->redirectBasedOnRole($_SESSION['role']);
            exit;
        }
    }

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

            $user = $this->model('UserModel')->getUserByEmail($email);

            if ($user) {
                if (password_verify($password, $user['password'])) {
                    session_regenerate_id(true); // Session Fixation Protection
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['nama'] = $user['nama'];
                    $_SESSION['role'] = $user['role'];

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
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            if ($this->model('UserModel')->getUserByEmail($email)) {
                Flasher::setFlash('Gagal', 'Email sudah terdaftar. Silakan login.', 'danger');
                header('Location: ' . BASE_URL . '/auth/register');
                exit;
            }

            // Validasi panjang password minimal 6 karakter
            if (strlen($password) < 6) {
                Flasher::setFlash('Gagal', 'Password minimal 6 karakter.', 'danger');
                header('Location: ' . BASE_URL . '/auth/register');
                exit;
            }

            if ($password !== $confirm_password) {
                Flasher::setFlash('Gagal', 'Konfirmasi password tidak sesuai.', 'danger');
                header('Location: ' . BASE_URL . '/auth/register');
                exit;
            }

            $data = [
                'nama' => $_POST['nama'],
                'email' => $email,
                'telepon' => $_POST['telepon'] ?? '-',
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ];

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
        // Clear session
        session_unset();

        // Delete cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        // Destroy
        session_destroy();

        // Start new for flash
        session_start();
        Flasher::setFlash('Berhasil', 'Anda telah keluar dari sistem.', 'success');

        // Redirect
        header('Location: ' . BASE_URL . '/auth');
        exit;
    }

    public function forgot()
    {
        $data['judul'] = 'Lupa Password';
        $this->view('auth/forgot', $data);
    }

    public function sendResetLink()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'] ?? '';

            if (empty($email)) {
                Flasher::setFlash('Gagal', 'Email wajib diisi.', 'danger');
                header('Location: ' . BASE_URL . '/auth/forgot');
                exit;
            }

            $user = $this->model('UserModel')->getUserByEmail($email);

            if (!$user) {
                Flasher::setFlash('Gagal', 'Email tidak ditemukan.', 'danger');
                header('Location: ' . BASE_URL . '/auth/forgot');
                exit;
            }

            // Generate token
            $token = bin2hex(random_bytes(32));
            $expireTime = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Update token di database
            $this->model('UserModel')->updateResetToken($user['id'], $token, $expireTime);

            // Kirim email
            $resetLink = BASE_URL . '/reset.php?token=' . $token;

            $mailer = new Mailer();
            if ($mailer->sendResetEmail($email, $user['nama'], $resetLink)) {
                Flasher::setFlash('Berhasil', 'Link reset telah dikirim ke email Anda.', 'success');
                header('Location: ' . BASE_URL . '/auth/emailSent');
                exit;
            } else {
                Flasher::setFlash('Gagal', 'Gagal mengirim email. Coba lagi nanti.', 'danger');
                header('Location: ' . BASE_URL . '/auth/forgot');
                exit;
            }
        }

        header('Location: ' . BASE_URL . '/auth/forgot');
        exit;
    }

    public function emailSent()
    {
        $data['judul'] = 'Email Terkirim';
        $this->view('auth/email-sent', $data);
    }

    // ✅ METHOD RESET YANG DIPERBAIKI
    public function reset()
    {
        $token = $_GET['token'] ?? '';

        if (empty($token)) {
            Flasher::setFlash('Gagal', 'Token tidak valid.', 'danger');
            header('Location: ' . BASE_URL . '/auth');
            exit;
        }

        // Cek token di database
        $user = $this->model('UserModel')->getUserByResetToken($token);

        if (!$user || strtotime($user['reset_token_expire']) < time()) {
            Flasher::setFlash('Gagal', 'Token tidak valid atau sudah kadaluarsa.', 'danger');
            header('Location: ' . BASE_URL . '/auth');
            exit;
        }

        // Token valid, tampilkan form
        $data['judul'] = 'Reset Password';
        $data['token'] = $token;
        $data['user_id'] = $user['id'];
        $this->view('auth/reset', $data);
    }

    // ✅ METHOD BARU: PROSES RESET PASSWORD
    public function processReset()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $token = $_POST['token'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['password_confirm'] ?? '';

            // Validasi password kosong
            if (empty($token) || empty($password)) {
                Flasher::setFlash('Gagal', 'Token dan password wajib diisi.', 'danger');
                header('Location: ' . BASE_URL . '/auth/reset?token=' . $token);
                exit;
            }

            // Validasi panjang password minimal 6 karakter
            if (strlen($password) < 6) {
                Flasher::setFlash('Gagal', 'Password minimal 6 karakter.', 'danger');
                header('Location: ' . BASE_URL . '/auth/reset?token=' . $token);
                exit;
            }

            // Validasi konfirmasi password
            if ($password !== $confirm) {
                Flasher::setFlash('Gagal', 'Konfirmasi password tidak sesuai.', 'danger');
                header('Location: ' . BASE_URL . '/auth/reset?token=' . $token);
                exit;
            }

            // Cek token
            $user = $this->model('UserModel')->getUserByResetToken($token);
            if (!$user || strtotime($user['reset_token_expire']) < time()) {
                Flasher::setFlash('Gagal', 'Token tidak valid atau sudah kadaluarsa.', 'danger');
                header('Location: ' . BASE_URL . '/auth');
                exit;
            }

            // Update password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $this->model('UserModel')->updatePasswordAndClearToken($user['id'], $hashedPassword);

            Flasher::setFlash('Berhasil', 'Password berhasil direset. Silakan login.', 'success');
            header('Location: ' . BASE_URL . '/auth');
            exit;
        }

        header('Location: ' . BASE_URL . '/auth');
        exit;
    }

    private function redirectBasedOnRole($role)
    {
        switch ($role) {
            case 'admin':
                header('Location: ' . BASE_URL . '/admin');
                break;
            case 'internal':
                header('Location: ' . BASE_URL . '/internal');
                break;
            case 'external':
                header('Location: ' . BASE_URL . '/external');
                break;
            default:
                header('Location: ' . BASE_URL . '/auth');
                break;
        }
        exit;
    }
}