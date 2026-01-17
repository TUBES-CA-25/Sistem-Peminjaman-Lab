<div class="auth-page-container">
    <div class="auth-card text-center">
        <!-- Icon Email -->
        <div class="email-icon-wrapper mb-4">
            <div class="email-icon">
                <i class="fas fa-envelope"></i>
            </div>
        </div>

        <h2 class="fw-bold mb-1 text-white">Cek Email Anda</h2>
        <p class="mb-1 text-white-50">Peminjaman Lab</p>
        <p class="small mb-4 text-white-50 opacity-75">
            Kami telah mengirimkan link reset password ke<br>
            <strong class="text-white"><?= htmlspecialchars($_SESSION['reset_email'] ?? 'email@anda.com') ?></strong>
        </p>

        <!-- Info Box -->
        <div class="info-box mb-4">
            <i class="fas fa-info-circle me-2"></i>
            <div class="text-start">
                <p class="mb-2 small"><strong>Langkah selanjutnya:</strong></p>
                <ol class="small mb-0 ps-3">
                    <li>Buka inbox email Anda</li>
                    <li>Cari email dari IC-LABS</li>
                    <li>Klik link "Reset Password"</li>
                    <li>Buat password baru Anda</li>
                </ol>
            </div>
        </div>

        <!-- Warning Box -->
        <div class="warning-box mb-4">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <small class="text-start d-block">
                Tidak menerima email? Cek folder <strong>Spam/Junk</strong> Anda. 
                Link akan kadaluarsa dalam <strong>1 jam</strong>.
            </small>
        </div>

        <!-- Actions -->
        <div class="d-grid gap-2">
            <a href="<?= BASE_URL ?>/auth/forgot" class="btn btn-outline-light fw-bold py-2 rounded-3">
                <i class="fas fa-redo me-2"></i>Kirim Ulang Email
            </a>
            <a href="<?= BASE_URL ?>/auth/login" class="btn btn-light fw-bold py-2 rounded-3">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Login
            </a>
        </div>
    </div>
</div>

<style>
    /* Menghindari tabrakan dengan header */
    .auth-page-container {
        min-height: 100vh;
        background: radial-gradient(circle at top right, #1e3a8a, #0f172a);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 100px 20px;
        margin-top: -80px;
    }

    .auth-card {
        width: 100%;
        max-width: 500px;
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        padding: 50px 40px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    /* Email Icon */
    .email-icon-wrapper {
        display: flex;
        justify-content: center;
    }

    .email-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4);
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 0 0 10px rgba(255, 255, 255, 0);
        }
    }

    /* Info Box */
    .info-box {
        background: rgba(59, 130, 246, 0.15);
        border: 1px solid rgba(59, 130, 246, 0.3);
        border-radius: 12px;
        padding: 16px;
        color: rgba(255, 255, 255, 0.9);
        display: flex;
        gap: 12px;
        text-align: left;
    }

    .info-box i {
        color: #60a5fa;
        font-size: 1.2rem;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .info-box ol {
        margin: 0;
        line-height: 1.6;
    }

    /* Warning Box */
    .warning-box {
        background: rgba(251, 191, 36, 0.15);
        border: 1px solid rgba(251, 191, 36, 0.3);
        border-radius: 12px;
        padding: 12px 16px;
        color: rgba(255, 255, 255, 0.9);
        display: flex;
        align-items: start;
        gap: 8px;
    }

    .warning-box i {
        color: #fbbf24;
        font-size: 1rem;
        flex-shrink: 0;
        margin-top: 2px;
    }

    /* Buttons */
    .btn-outline-light {
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
    }

    .btn-outline-light:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.5);
        color: white;
    }
</style>