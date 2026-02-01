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
        'verify',
        'prosesVerify',
        'resendOTP',
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
                    // Cek Verifikasi Email
                    if ($user['is_verified'] == 0) {
                        $_SESSION['temp_email'] = $email;
                        Flasher::setFlash('Info', 'Akun Anda belum diverifikasi. Silakan masukkan kode OTP.', 'warning');
                        header('Location: ' . BASE_URL . '/auth/verify');
                        exit;
                    }

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

            $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $data = [
                'nama' => $_POST['nama'],
                'email' => $email,
                'telepon' => $_POST['telepon'] ?? '-',
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'verification_code' => $otp
            ];

            if ($this->model('UserModel')->tambahUser($data) > 0) {
                // Kirim OTP via Email
                if ($this->sendOTPEmail($email, $_POST['nama'], $otp)) {
                    $_SESSION['temp_email'] = $email;
                    Flasher::setFlash('Berhasil', 'Akun berhasil dibuat. Silakan cek email Anda untuk kode verifikasi.', 'success');
                    header('Location: ' . BASE_URL . '/auth/verify');
                    exit;
                } else {
                    Flasher::setFlash('Peringatan', 'Akun dibuat, tapi gagal mengirim email. Gunakan fitur kirim ulang di halaman verifikasi.', 'warning');
                    $_SESSION['temp_email'] = $email;
                    header('Location: ' . BASE_URL . '/auth/verify');
                    exit;
                }
            } else {
                Flasher::setFlash('Gagal', 'Terjadi kesalahan sistem.', 'danger');
                header('Location: ' . BASE_URL . '/auth/register');
                exit;
            }
        }
    }

    public function verify()
    {
        if (!isset($_SESSION['temp_email'])) {
            header('Location: ' . BASE_URL . '/auth');
            exit;
        }

        $data['judul'] = 'Verifikasi Akun';
        $data['email'] = $_SESSION['temp_email'];
        $this->view('auth/verify', $data);
    }

    public function prosesVerify()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $otp = $_POST['otp'];

            if ($this->model('UserModel')->verifyUser($email, $otp)) {
                unset($_SESSION['temp_email']);
                Flasher::setFlash('Berhasil', 'Akun Anda telah diverifikasi. Silakan login.', 'success');
                header('Location: ' . BASE_URL . '/auth');
                exit;
            } else {
                Flasher::setFlash('Gagal', 'Kode OTP tidak valid.', 'danger');
                header('Location: ' . BASE_URL . '/auth/verify');
                exit;
            }
        }
    }

    public function resendOTP()
    {
        if (isset($_SESSION['temp_email'])) {
            $email = $_SESSION['temp_email'];
            $user = $this->model('UserModel')->getUserByEmail($email);
            
            if ($user) {
                $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
                $this->model('UserModel')->updateVerificationCode($email, $otp);
                
                if ($this->sendOTPEmail($email, $user['nama'], $otp)) {
                    Flasher::setFlash('Berhasil', 'Kode OTP baru telah dikirim ke email Anda.', 'success');
                } else {
                    Flasher::setFlash('Gagal', 'Gagal mengirim email.', 'danger');
                }
            }
        }
        header('Location: ' . BASE_URL . '/auth/verify');
        exit;
    }

    private function sendOTPEmail($email, $nama, $otp)
    {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = getenv('SMTP_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = getenv('SMTP_USERNAME');
            $mail->Password = getenv('SMTP_PASSWORD');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = getenv('SMTP_PORT');

            $mail->setFrom(getenv('SMTP_FROM_EMAIL'), getenv('SMTP_FROM_NAME'));
            $mail->addAddress($email, $nama);

            $mail->isHTML(true);
            $mail->Subject = 'Kode Verifikasi Akun - ICLABS';
            $mail->Body = $this->getOTPEmailTemplate($nama, $otp);
            $mail->AltBody = "Halo $nama, kode verifikasi Anda adalah: $otp";

            return $mail->send();
        } catch (Exception $e) {
            error_log("OTP Mail Error: " . $e->getMessage());
            return false;
        }
    }

    private function getOTPEmailTemplate($nama, $otp)
    {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: 'Inter', sans-serif; background-color: #f8fafc; margin: 0; padding: 0; }
                .container { max-width: 600px; margin: 40px auto; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
                .header { background: linear-gradient(135deg, #1e3a8a, #1F45AC); color: white; padding: 40px 20px; text-align: center; }
                .content { padding: 40px; text-align: center; color: #334155; }
                .otp-box { background: #f1f5f9; padding: 20px; border-radius: 12px; font-size: 32px; font-weight: bold; letter-spacing: 12px; color: #1e3a8a; margin: 30px 0; border: 2px dashed #cbd5e1; }
                .footer { background: #f8fafc; padding: 20px; text-align: center; color: #64748b; font-size: 12px; border-top: 1px solid #e2e8f0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1 style='margin:0;'>🔐 Verifikasi Akun</h1>
                </div>
                <div class='content'>
                    <p>Halo <strong>$nama</strong>,</p>
                    <p>Terima kasih telah mendaftar di <strong>ICLABS</strong>. Silakan masukkan kode verifikasi berikut untuk mengaktifkan akun Anda:</p>
                    <div class='otp-box'>$otp</div>
                    <p style='font-size: 14px; color: #64748b;'>Kode ini berlaku selama 15 menit. Jika Anda tidak merasa mendaftar, abaikan email ini.</p>
                </div>
                <div class='footer'>
                    <p>&copy; 2026 Tim ICLABS. All Rights Reserved.</p>
                </div>
            </div>
        </body>
        </html>";
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