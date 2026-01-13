<div class="auth-page-container">
    <div class="auth-card text-center">
        <h2 class="fw-bold mb-1 text-white">Reset Password</h2>
        <p class="mb-1 text-white-50">Peminjaman Lab</p>
        <p class="small mb-4 text-white-50 opacity-75">Buat password baru untuk akun Anda</p>

        <form method="post" action="<?= BASE_URL ?>/auth/processReset">
            
            <input type="hidden" name="user_id" value="<?= $user_id ?? '' ?>">
            <input type="hidden" name="token" value="<?= $token ?? '' ?>">

            <div class="mb-3 text-start">
                <label class="form-label small fw-semibold text-white-50">Password Baru</label>
                <div class="input-group-custom">
                    <input type="password" class="form-control" name="password" placeholder="Buat password" required minlength="6">
                </div>
                <small class="text-white-50 opacity-50">Minimal 6 karakter</small>
            </div>

            <div class="mb-4 text-start">
                <label class="form-label small fw-semibold text-white-50">Konfirmasi Password Baru</label>
                <div class="input-group-custom">
                    <input type="password" class="form-control" name="password_confirm" placeholder="Ulangi password" required minlength="6">
                </div>
            </div>

            <button type="submit" class="btn btn-light w-100 fw-bold py-2 mb-3 rounded-3">Reset Password</button>
        </form>

        <div class="position-relative my-4">
            <hr class="border-white opacity-25">
            <span class="position-absolute top-50 start-50 translate-middle bg-transparent px-2 small text-white-50">ATAU</span>
        </div>

        <p class="small mb-0 text-white-50">
            Kembali ke halaman
            <a href="<?= BASE_URL ?>/auth/login" class="text-white fw-bold text-decoration-none border-bottom border-white">Masuk</a>
        </p>
    </div>
</div>

<style>
    
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

    small {
        display: block;
        margin-top: 4px;
        font-size: 0.75rem;
    }
</style>