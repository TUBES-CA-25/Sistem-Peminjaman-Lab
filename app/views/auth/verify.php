<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= $data['judul']; ?> | Peminjaman Lab</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            max-width: 1050px;
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
            width: 240px;
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

        .otp-inputs {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 30px;
        }

        .otp-field {
            width: 45px;
            height: 55px;
            background: rgba(255, 255, 255, .12) !important;
            border: 1px solid rgba(255, 255, 255, .18) !important;
            color: white !important;
            border-radius: 12px;
            text-align: center;
            font-size: 20px;
            font-weight: 700;
            transition: all 0.2s;
        }

        .otp-field:focus {
            outline: none;
            background: rgba(255, 255, 255, .18) !important;
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, .25) !important;
        }

        .btn-verify {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            color: white;
            font-weight: 800;
            border-radius: 16px;
            padding: 14px;
            transition: all 0.3s;
        }

        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 40px rgba(59, 130, 246, .45);
        }

        .resend-text {
            font-size: 14px;
            color: rgba(255, 255, 255, .5);
        }

        .resend-link {
            color: white;
            text-decoration: none;
            font-weight: 700;
        }

        .resend-link:hover {
            text-decoration: underline;
        }

        .resend-link.disabled {
            color: rgba(255, 255, 255, .3);
            pointer-events: none;
        }

        .alert {
            background: rgba(25, 135, 84, 0.2);
            border: 1px solid rgba(25, 135, 84, 0.5);
            color: #9be6b6;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.5);
            color: #ffadad;
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

            <div class="auth-form text-center text-md-start">
                <div class="mb-4">
                    <i class="fas fa-envelope-open-text fa-3x text-info mb-3"></i>
                    <h2>Verifikasi Akun</h2>
                    <p class="text-white-50">Kami telah mengirimkan 6 digit kode OTP ke email
                        <br><strong><?= $data['email']; ?></strong>
                    </p>
                </div>

                <?php Flasher::flash(); ?>

                <form action="<?= BASE_URL; ?>/auth/prosesVerify" method="POST" id="otpForm">
                    <input type="hidden" name="email" value="<?= $data['email']; ?>">
                    <input type="hidden" name="otp" id="otpFull">

                    <div class="otp-inputs">
                        <input type="text" class="otp-field" maxlength="1" pattern="\d*" inputmode="numeric">
                        <input type="text" class="otp-field" maxlength="1" pattern="\d*" inputmode="numeric">
                        <input type="text" class="otp-field" maxlength="1" pattern="\d*" inputmode="numeric">
                        <input type="text" class="otp-field" maxlength="1" pattern="\d*" inputmode="numeric">
                        <input type="text" class="otp-field" maxlength="1" pattern="\d*" inputmode="numeric">
                        <input type="text" class="otp-field" maxlength="1" pattern="\d*" inputmode="numeric">
                    </div>

                    <button type="submit" class="btn btn-verify w-100 mb-3" id="btnVerify">
                        <span class="spinner-border spinner-border-sm d-none me-2" role="status"
                            aria-hidden="true"></span>
                        <span class="btn-text">Verifikasi</span>
                    </button>
                </form>

                <div class="resend-text text-center mt-3">
                    Tidak menerima kode?
                    <span id="timerText">Kirim ulang dalam <span id="timer" class="fw-bold">60</span> detik</span>
                    <a href="<?= BASE_URL; ?>/auth/resendOTP" id="resendBtn" class="resend-link d-none">Kirim Ulang</a>
                </div>

                <div class="text-center mt-4 border-top border-white-10 pt-4">
                    <a href="<?= BASE_URL; ?>/auth" class="small text-white-50 text-decoration-none hover-white">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Login
                    </a>
                </div>
            </div>

        </div>
    </div>

    <script>
        const otpFields = document.querySelectorAll('.otp-field');
        const otpFullInput = document.getElementById('otpFull');
        const otpForm = document.getElementById('otpForm');

        otpFields.forEach((field, index) => {
            field.addEventListener('input', (e) => {
                if (e.target.value.length > 0 && index < otpFields.length - 1) {
                    otpFields[index + 1].focus();
                }
                updateFullOTP();
            });

            field.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && e.target.value.length === 0 && index > 0) {
                    otpFields[index - 1].focus();
                }
            });

            field.addEventListener('paste', (e) => {
                e.preventDefault();
                const data = e.clipboardData.getData('text').slice(0, 6);
                if (/^\d+$/.test(data)) {
                    data.split('').forEach((char, i) => {
                        if (otpFields[i]) otpFields[i].value = char;
                    });
                    otpFields[Math.min(data.length, 5)].focus();
                    updateFullOTP();
                }
            });
        });

        function updateFullOTP() {
            let code = "";
            otpFields.forEach(field => code += field.value);
            otpFullInput.value = code;
        }

        otpForm.addEventListener('submit', (e) => {
            if (otpFullInput.value.length !== 6) {
                e.preventDefault();
                alert('Silakan masukkan 6 digit kode OTP yang lengkap.');
            } else {
                const btn = document.getElementById('btnVerify');
                const spinner = btn.querySelector('.spinner-border');
                const btnText = btn.querySelector('.btn-text');

                btn.disabled = true;
                spinner.classList.remove('d-none');
                btnText.textContent = 'Memverifikasi...';
            }
        });

        let timeLeft = 60;
        const timerDisplay = document.getElementById('timer');
        const timerText = document.getElementById('timerText');
        const resendBtn = document.getElementById('resendBtn');

        const countdown = setInterval(() => {
            timeLeft--;
            timerDisplay.textContent = timeLeft;
            if (timeLeft <= 0) {
                clearInterval(countdown);
                timerText.classList.add('d-none');
                resendBtn.classList.remove('d-none');
            }
        }, 1000);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>