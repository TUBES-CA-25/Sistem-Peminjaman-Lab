<?php
session_start();

// Load config
if (file_exists('config/Config.php')) {
    require_once 'config/Config.php';
} elseif (file_exists('config/config.php')) {
    require_once 'config/config.php';
} else {
    define('DB_HOST', 'localhost');
    define('DB_PORT', '3306');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'tubes_ca_db');
    define('BASE_URL', 'http://localhost/Sistem-Peminjaman-Lab');
}

require_once 'app/core/Database.php';

if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/Sistem-Peminjaman-Lab');
}

$token = $_GET['token'] ?? '';
$error = '';
$user = null;

// Jika ada token, validasi
if (!empty($token)) {
    try {
        $db = new Database();
        $query = "SELECT id, nama, email, reset_token_expire 
                  FROM users 
                  WHERE reset_token = :token";
        
        $db->query($query);
        $db->bind('token', $token);
        $user = $db->single();

        if (!$user) {
            $error = 'Token tidak valid atau tidak ditemukan.';
        } elseif (strtotime($user['reset_token_expire']) < time()) {
            $error = 'Token sudah kadaluarsa. Silakan request reset password lagi.';
        }
    } catch (Exception $e) {
        $error = 'Terjadi kesalahan sistem: ' . $e->getMessage();
    }
} else {
    $error = 'Token tidak ditemukan di URL.';
}

// Proses form submit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$error && $user) {
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['password_confirm'] ?? '';
    
    if ($password !== $confirm) {
        $error = 'Password dan konfirmasi tidak cocok.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } else {
        try {
            $db = new Database();
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $updateQuery = "UPDATE users 
                            SET password = :password, 
                                reset_token = NULL, 
                                reset_token_expire = NULL 
                            WHERE id = :id";
            
            $db->query($updateQuery);
            $db->bind('password', $hashedPassword);
            $db->bind('id', $user['id']);
            $db->execute();
            
            // Redirect ke login dengan pesan sukses
            session_start();
            $_SESSION['flash'] = [
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => 'Password berhasil direset. Silakan login dengan password baru.'
            ];
            header('Location: ' . BASE_URL . '/auth');
            exit;
            
        } catch (Exception $e) {
            $error = 'Gagal update password: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - ICLABS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            min-height: 100vh;
            background: radial-gradient(circle at top right, #1e3a8a, #0f172a);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .auth-card {
            width: 100%;
            max-width: 450px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 50px 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .form-control {
            background: rgba(255, 255, 255, 0.1) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: white !important;
            padding: 12px 15px !important;
            border-radius: 12px !important;
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.3) !important;
        }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.15) !important;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.05) !important;
            border-color: rgba(255, 255, 255, 0.3) !important;
        }
        .btn-light {
            background: white;
            color: #1e3a8a;
            font-weight: bold;
            border: none;
            padding: 12px;
            border-radius: 12px;
            transition: all 0.3s;
        }
        .btn-light:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body>
    <div class="auth-card text-center">
        <h2 class="fw-bold mb-1 text-white">🔒 Reset Password</h2>
        <p class="mb-1 text-white-50">Peminjaman Lab</p>
        <p class="small mb-4 text-white-50 opacity-75">Buat password baru untuk akun Anda</p>

        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?= htmlspecialchars($error) ?>
            </div>
            <a href="<?= BASE_URL ?>/auth/forgot" class="btn btn-light w-100 mb-3">
                Request Reset Lagi
            </a>
            <a href="<?= BASE_URL ?>/auth" class="text-white text-decoration-none small">
                ← Kembali ke Login
            </a>
        <?php else: ?>
            <div class="alert alert-success mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong>Token Valid!</strong><br>
                <small>Akun: <?= htmlspecialchars($user['email']) ?></small>
            </div>

            <form method="POST" id="resetForm">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                
                <div class="mb-3 text-start">
                    <label class="form-label small fw-semibold text-white-50">Password Baru</label>
                    <input 
                        type="password" 
                        class="form-control" 
                        name="password" 
                        id="password"
                        placeholder="Minimal 6 karakter" 
                        required 
                        minlength="6"
                    >
                </div>

                <div class="mb-4 text-start">
                    <label class="form-label small fw-semibold text-white-50">Konfirmasi Password</label>
                    <input 
                        type="password" 
                        class="form-control" 
                        name="password_confirm" 
                        id="password_confirm"
                        placeholder="Ulangi password" 
                        required 
                        minlength="6"
                    >
                </div>

                <div id="error-message" class="alert alert-danger d-none mb-3"></div>

                <button type="submit" class="btn btn-light w-100 fw-bold py-2 mb-3 rounded-3">
                    Reset Password
                </button>
            </form>

            <div class="position-relative my-4">
                <hr class="border-white opacity-25">
                <span class="position-absolute top-50 start-50 translate-middle bg-transparent px-2 small text-white-50">ATAU</span>
            </div>

            <p class="small mb-0 text-white-50">
                Kembali ke halaman
                <a href="<?= BASE_URL ?>/auth" class="text-white fw-bold text-decoration-none border-bottom border-white">Masuk</a>
            </p>
        <?php endif; ?>
    </div>

    <script>
        document.getElementById('resetForm')?.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirm').value;
            const errorDiv = document.getElementById('error-message');

            if (password !== confirm) {
                e.preventDefault();
                errorDiv.textContent = '❌ Password dan konfirmasi tidak cocok!';
                errorDiv.classList.remove('d-none');
                return false;
            }

            if (password.length < 6) {
                e.preventDefault();
                errorDiv.textContent = '❌ Password minimal 6 karakter!';
                errorDiv.classList.remove('d-none');
                return false;
            }
        });
    </script>
</body>
</html>