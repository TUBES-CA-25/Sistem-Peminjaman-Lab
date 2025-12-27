<?php 
    include '../../components/header.php'; 
?>

<div class="auth-page-container">
    <div class="auth-card text-center">
        <h2 class="fw-bold mb-1 text-white">Daftar Akun Baru</h2>
        <p class="mb-1 text-white-50">Peminjaman Lab</p>
        <p class="small mb-4 text-white-50 opacity-75">Lengkapi data diri Anda untuk memulai</p>

        <form method="post" action="#">
            <div class="mb-3 text-start">
                <label class="form-label small fw-semibold text-white-50">Nama Lengkap</label>
                <input type="text" class="form-control custom-input" placeholder="Nama Lengkap Anda">
            </div>

            <div class="mb-3 text-start">
                <label class="form-label small fw-semibold text-white-50">Email</label>
                <input type="email" class="form-control custom-input" placeholder="nama@email.com">
            </div>

            <div class="mb-3 text-start">
                <label class="form-label small fw-semibold text-white-50">Nomor Telepon</label>
                <input type="text" class="form-control custom-input" placeholder="08xxxxxxxxxx">
            </div>

            <div class="mb-3 text-start">
                <label class="form-label small fw-semibold text-white-50">Password</label>
                <input type="password" class="form-control custom-input" placeholder="Buat password">
            </div>

            <div class="mb-4 text-start">
                <label class="form-label small fw-semibold text-white-50">Konfirmasi Password</label>
                <input type="password" class="form-control custom-input" placeholder="Ulangi password">
            </div>

            <button type="submit" class="btn btn-light w-100 fw-bold py-2 mb-3 rounded-3 shadow-sm">Daftar</button>
        </form>

        <div class="position-relative my-4">
            <hr class="border-white opacity-25">
            <span class="position-absolute top-50 start-50 translate-middle bg-transparent px-2 small text-white-50">ATAS</span>
        </div>

        <p class="small mb-0 text-white-50">
            Sudah punya akun? 
            <a href="login.php" class="text-white fw-bold text-decoration-none border-bottom border-white">Masuk di sini</a>
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
        padding: 120px 20px 80px 20px; 
        margin-top: -80px; 
    }

    .auth-card {
        width: 100%;
        max-width: 480px; 
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    .custom-input {
        background: rgba(255, 255, 255, 0.1) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: white !important;
        padding: 12px 15px !important;
        border-radius: 12px !important;
    }

    .custom-input::placeholder {
        color: rgba(255, 255, 255, 0.3) !important;
    }

    .custom-input:focus {
        background: rgba(255, 255, 255, 0.15) !important;
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.05) !important;
        border-color: rgba(255, 255, 255, 0.3) !important;
    }

    body {
        background-color: #0f172a !important;
    }
</style>

<?php 
    include '../../components/footer.php'; 
?>