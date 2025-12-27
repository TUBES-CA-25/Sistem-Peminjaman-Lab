<?php 
    // Jalur mundur dua kali karena sekarang file berada di /pages/auth/
    include '../../components/header.php'; 
?>

<div class="auth-page-container">
    <div class="auth-card text-center">
        <h2 class="fw-bold mb-1 text-white">Masuk ke Akun</h2>
        <p class="mb-1 text-white-50">Peminjaman Lab</p>
        <p class="small mb-4 text-white-50 opacity-75">Silakan masuk untuk melanjutkan akses laboratorium</p>

        <form method="post" action="#">
            <div class="mb-3 text-start">
                <label class="form-label small fw-semibold text-white-50">Email</label>
                <div class="input-group-custom">
                    <input type="email" class="form-control" placeholder="nama@email.com">
                </div>
            </div>

            <div class="mb-2 text-start">
                <label class="form-label small fw-semibold text-white-50">Password</label>
                <div class="input-group-custom">
                    <input type="password" class="form-control" placeholder="Masukkan password Anda">
                </div>
            </div>

            <div class="text-end mb-4">
                <a href="#" class="auth-link-alt small text-white-50 text-decoration-none">Lupa Password?</a>
            </div>

            <button type="submit" class="btn btn-light w-100 fw-bold py-2 mb-3 rounded-3">Masuk</button>
        </form>

        <div class="position-relative my-4">
            <hr class="border-white opacity-25">
            <span class="position-absolute top-50 start-50 translate-middle bg-transparent px-2 small text-white-50">ATAU</span>
        </div>

        <p class="small mb-0 text-white-50">
            Belum punya akun? 
            <a href="register.php" class="text-white fw-bold text-decoration-none border-bottom border-white">Daftar di sini</a>
        </p>
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
        margin-top: -80px; /* Menarik konten ke atas agar sejajar dengan header transparan */
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

    .auth-link-alt:hover {
        color: white !important;
    }
</style>

<?php 
    include '../../components/footer.php'; 
?>