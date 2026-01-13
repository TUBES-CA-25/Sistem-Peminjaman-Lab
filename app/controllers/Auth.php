<?php

class Auth extends Controller
{
    public function index()
    {
        $this->login();
    }

    public function login()
    {
        $data['title'] = 'Login';
        $this->view('components/header', $data);
        $this->view('auth/login', $data);
        $this->view('components/footer', $data);
    }

    public function register()
    {
        $data['title'] = 'Register';
        $this->view('components/header', $data);
        $this->view('auth/register', $data);
        $this->view('components/footer', $data);
    }

    /**
     * Menampilkan halaman forgot password
     */
    public function forgot()
    {
        $data['title'] = 'Lupa Password';
        
        $this->view('components/header', $data);
        $this->view('auth/forgot', $data);
        $this->view('components/footer', $data);
    }

    public function verifyForgot()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';

            // Untuk sementara (dummy), langsung redirect ke reset:
            $_SESSION['reset_email'] = $email;
            header("Location: " . BASE_URL . "auth/reset");
            exit;
        }
    }

    public function reset()
    {
        
        $data['title'] = 'Reset Password';
        $data['token'] = $_GET['token'] ?? '';
        $data['user_id'] = $_SESSION['reset_user_id'] ?? '';
        
        $this->view('components/header', $data);
        $this->view('auth/reset', $data);
        $this->view('components/footer', $data);
    }

    public function processReset()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';
            $user_id = $_POST['user_id'] ?? '';
            $token = $_POST['token'] ?? '';

            // Validasi password match
            if ($password !== $password_confirm) {
                $_SESSION['error_message'] = 'Password tidak cocok.';
                header("Location: " . BASE_URL . "auth/reset?token=" . $token);
                exit;
            }

            // Validasi panjang password
            if (strlen($password) < 6) {
                $_SESSION['error_message'] = 'Password minimal 6 karakter.';
                header("Location: " . BASE_URL . "auth/reset?token=" . $token);
                exit;
            }

            // Untuk sementara (dummy), langsung redirect ke login:
            $_SESSION['success_message'] = 'Password berhasil direset. Silakan login dengan password baru.';
            header("Location: " . BASE_URL . "auth/login");
            exit;
        }
    }
}