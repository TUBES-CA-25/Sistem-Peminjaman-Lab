<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Register | Peminjaman Lab</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/public/storage/images/logo-iclabs.png">
    <style>
        :root {
            --primary: #3b82f6;
            --secondary: #22d3ee;
            --dark: #020617;
        }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, Segoe UI, Roboto;
            background: #020617;
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: linear-gradient(120deg, #020617, #0f172a, #1e3a8a);
        }

        .auth-box {
            max-width: 1100px;
            width: 100%;
            display: grid;
            grid-template-columns: 1.1fr 1fr;
            background: rgba(255, 255, 255, .05);
            backdrop-filter: blur(26px);
            border-radius: 32px;
            overflow: hidden;
            box-shadow: 0 40px 80px rgba(0, 0, 0, .7);
        }

        .auth-image {
            position: relative;
            overflow: hidden;
            perspective: 1400px;
        }

        .auth-image img.bg {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .auth-image::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(2, 6, 23, .72), rgba(2, 6, 23, .97));
        }

        .logo-3d {
            position: absolute;
            top: 50%;
            left: 50%;
            transform-style: preserve-3d;
            transform: translate(-50%, -50%) translateZ(140px);
            z-index: 5;
            animation: logoFloat 6s ease-in-out infinite;
        }

        .logo-3d img {
            width: 260px;
            max-width: 75%;
            filter: drop-shadow(0 50px 100px rgba(0, 0, 0, .95)) drop-shadow(0 0 60px rgba(59, 130, 246, .7)) drop-shadow(0 0 120px rgba(34, 211, 238, .45));
            animation: logoGlow 3s ease-in-out infinite;
        }

        @keyframes logoFloat {

            0%,
            100% {
                transform: translate(-50%, -50%) translateZ(140px) rotateX(0deg) rotateY(0deg);
            }

            50% {
                transform: translate(-50%, -50%) translateZ(200px) rotateX(20deg) rotateY(-20deg);
            }
        }

        @keyframes logoGlow {

            0%,
            100% {
                filter: drop-shadow(0 50px 100px rgba(0, 0, 0, .95)) drop-shadow(0 0 50px rgba(59, 130, 246, .65)) drop-shadow(0 0 100px rgba(34, 211, 238, .45));
            }

            50% {
                filter: drop-shadow(0 70px 140px rgba(0, 0, 0, 1)) drop-shadow(0 0 80px rgba(59, 130, 246, .9)) drop-shadow(0 0 150px rgba(34, 211, 238, .6));
            }
        }

        .image-caption {
            position: absolute;
            bottom: 36px;
            left: 36px;
            z-index: 3;
            color: white;
        }

        .image-caption h3 {
            font-weight: 900;
        }

        .image-caption p {
            opacity: .85;
        }

        .auth-form {
            padding: 70px 60px;
            color: white;
        }

        .auth-form h2 {
            font-weight: 900;
        }

        .form-control {
            background: rgba(255, 255, 255, .12) !important;
            border: 1px solid rgba(255, 255, 255, .18) !important;
            color: white !important;
            border-radius: 16px;
            padding: 14px 18px;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, .4);
        }

        .form-control:focus {
            background: rgba(255, 255, 255, .18) !important;
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, .25) !important;
        }

        .btn-register {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            color: white;
            font-weight: 800;
            border-radius: 16px;
            padding: 14px;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 40px rgba(59, 130, 246, .45);
        }

        /* Tambahan Style untuk Alert Flasher */
        .alert {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.5);
            color: #ffadad;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: rgba(25, 135, 84, 0.2);
            border: 1px solid rgba(25, 135, 84, 0.5);
            color: #9be6b6;
        }

        @media(max-width:900px) {
            .auth-box {
                grid-template-columns: 1fr;
            }

            .auth-image {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="auth-wrapper">
        <div class="auth-box">

            <div class="auth-image">
                <img src="<?= BASE_URL ?>/public/storage/images/CV.jpg" class="bg" alt="Laboratorium">
                <div class="logo-3d">
                    <img src="<?= BASE_URL ?>/public/storage/images/logo-iclabs.png" alt="Logo">
                </div>
                <div class="image-caption">
                    <h3>Peminjaman Laboratorium</h3>
                    <p>Sistem reservasi & manajemen ruang praktikum</p>
                </div>
            </div>

            <div class="auth-form">
                <h2>Daftar Akun Baru</h2>
                <p class="text-white-50 mb-4">
                    Lengkapi data diri Anda untuk memulai
                </p>

                <div class="row">
                    <div class="col-12">
                        <?php Flasher::flash(); ?>
                    </div>
                </div>

                <form method="post" action="<?= BASE_URL ?>/auth/prosesRegister">
                    <div class="mb-3">
                        <label class="small text-white-50">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama lengkap Anda" required>
                    </div>

                    <div class="mb-3">
                        <label class="small text-white-50">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
                    </div>

                    <div class="mb-3">
                        <label class="small text-white-50">Nomor Telepon</label>
                        <input type="text" name="telepon" class="form-control" placeholder="08xxxxxxxxxx" 
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="13" required>
                    </div>

                    <div class="mb-3">
                        <label class="small text-white-50">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Buat password"
                            minlength="6" title="Password minimal 6 karakter" required>
                        <small class="text-white-50 opacity-75">Minimal 6 karakter</small>
                    </div>

                    <div class="mb-4">
                        <label class="small text-white-50">Konfirmasi Password</label>
                        <input type="password" name="confirm_password" class="form-control"
                            placeholder="Ulangi password" minlength="6" required>
                    </div>

                    <button type="submit" class="btn btn-register w-100" id="btnRegister">
                        <span class="spinner-border spinner-border-sm d-none me-2" role="status"
                            aria-hidden="true"></span>
                        <span class="btn-text">Daftar</span>
                    </button>
                </form>

                <p class="small mt-4 text-white-50">
                    Sudah punya akun?
                    <a href="<?= BASE_URL ?>/auth" class="text-white fw-bold">
                        Masuk di sini
                    </a>
                </p>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        document.querySelector('form').addEventListener('submit', function () {
            const btn = document.getElementById('btnRegister');
            const spinner = btn.querySelector('.spinner-border');
            const btnText = btn.querySelector('.btn-text');

            btn.disabled = true;
            spinner.classList.remove('d-none');
            btnText.textContent = 'Memproses...';
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>