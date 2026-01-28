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
        'reset',           // ← Tambahkan ini
        'processReset',    // ← Dan ini
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
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['nama'] = $user['nama'];
                    $_SESSION['role'] = $user['role'];

                    // Ambil Tahun Ajaran Aktif
                    $activeYear = $this->model('TahunAjaranModel')->getActive();
                    if ($activeYear) {
                        $_SESSION['tahun_ajaran'] = $activeYear['nama'];
                    } else {
                        $_SESSION['tahun_ajaran'] = '-'; // Default jika tidak ada
                    }

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

            try {
                $mail = new PHPMailer(true);

                // ✅ GMAIL SMTP CONFIGURATION (dari .env)
                $mail->isSMTP();
                $mail->Host = getenv('SMTP_HOST');
                $mail->SMTPAuth = true;
                $mail->Username = getenv('SMTP_USERNAME');
                $mail->Password = getenv('SMTP_PASSWORD');
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = getenv('SMTP_PORT');

                $mail->setFrom(getenv('SMTP_FROM_EMAIL'), getenv('SMTP_FROM_NAME'));
                $mail->addAddress($email, $user['nama']);

                $mail->isHTML(true);
                $mail->Subject = 'Reset Password - Sistem Peminjaman Lab';
                $mail->Body = $this->getResetEmailTemplate($user['nama'], $resetLink);
                $mail->AltBody = "Halo {$user['nama']},\n\nKlik link berikut untuk reset password Anda:\n$resetLink\n\nLink berlaku 1 jam.";

                $mail->send();

                Flasher::setFlash('Berhasil', 'Link reset telah dikirim ke email Anda.', 'success');
                header('Location: ' . BASE_URL . '/auth/emailSent');
                exit;

            } catch (Exception $e) {
                error_log("PHPMailer Error: {$mail->ErrorInfo}");
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

            if (empty($token) || empty($password) || $password !== $confirm) {
                Flasher::setFlash('Gagal', 'Data tidak valid atau password tidak cocok.', 'danger');
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

    private function getResetEmailTemplate($nama, $resetLink)
    {
        return "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
            .container { max-width: 600px; margin: 40px auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
            .header { background: linear-gradient(135deg, #1e3a8a, #3b82f6); color: white; padding: 30px; text-align: center; }
            .header h1 { margin: 0; font-size: 24px; }
            .content { padding: 40px 30px; color: #333; }
            .content p { line-height: 1.6; margin-bottom: 20px; }
            .btn { display: inline-block; background: linear-gradient(135deg, #1e3a8a, #3b82f6); color: white !important; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-weight: bold; margin: 20px 0; }
            .btn:hover { opacity: 0.9; }
            .footer { background: #0f172a; padding: 20px; text-align: center; color: rgba(255,255,255,0.7); font-size: 12px; }
            .warning { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 12px; margin: 20px 0; border-radius: 4px; color: #92400e; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>🔒 Reset Password</h1>
            </div>
            <div class='content'>
                <p>Halo <strong>$nama</strong>,</p>
                <p>Kami menerima permintaan untuk reset password akun Anda di <strong>ICLABS - Sistem Peminjaman Laboratorium</strong>.</p>
                <p>Klik tombol di bawah ini untuk membuat password baru:</p>
                <center>
                    <a href='$resetLink' class='btn' style='color: white;'>Reset Password Saya</a>
                </center>
                <div class='warning'>
                    ⚠️ <strong>Penting:</strong> Link ini hanya berlaku selama <strong>1 jam</strong> dan hanya bisa digunakan sekali.
                </div>
                <p>Jika Anda tidak meminta reset password, abaikan email ini. Akun Anda tetap aman.</p>
                <p>Terima kasih,<br><strong>Tim ICLABS</strong></p>
            </div>
            <div class='footer'>
                <p>Email ini dikirim otomatis oleh sistem. Mohon tidak membalas email ini.</p>
                <p>&copy; 2026 ICLABS. All Rights Reserved.</p>
            </div>
        </div>
    </body>
    </html>
    ";
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