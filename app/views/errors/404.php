<?php
// Define BASE_URL jika belum terdefined
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/Sistem-Peminjaman-Lab');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan | ICLABS</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* ✅ BARU: Animated Background dengan Logo ICLABS */
        .bg-animation {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .bg-animation .floating-logo {
            position: absolute;
            display: block;
            background-image: url('<?= BASE_URL ?>/public/storage/images/logo-iclabs.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.1;
            animation: floatLogo 20s linear infinite;
            bottom: -200px;
        }

        /* Logo dengan ukuran berbeda */
        .bg-animation .floating-logo:nth-child(1) {
            left: 5%;
            width: 100px;
            height: 100px;
            animation-delay: 0s;
            animation-duration: 18s;
        }

        .bg-animation .floating-logo:nth-child(2) {
            left: 15%;
            width: 60px;
            height: 60px;
            animation-delay: 3s;
            animation-duration: 22s;
        }

        .bg-animation .floating-logo:nth-child(3) {
            left: 30%;
            width: 80px;
            height: 80px;
            animation-delay: 6s;
            animation-duration: 20s;
        }

        .bg-animation .floating-logo:nth-child(4) {
            left: 45%;
            width: 50px;
            height: 50px;
            animation-delay: 2s;
            animation-duration: 24s;
        }

        .bg-animation .floating-logo:nth-child(5) {
            left: 55%;
            width: 70px;
            height: 70px;
            animation-delay: 8s;
            animation-duration: 19s;
        }

        .bg-animation .floating-logo:nth-child(6) {
            left: 70%;
            width: 90px;
            height: 90px;
            animation-delay: 4s;
            animation-duration: 21s;
        }

        .bg-animation .floating-logo:nth-child(7) {
            left: 85%;
            width: 55px;
            height: 55px;
            animation-delay: 10s;
            animation-duration: 23s;
        }

        @keyframes floatLogo {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 0.1;
            }
            50% {
                opacity: 0.15;
            }
            100% {
                transform: translateY(-1100px) rotate(360deg);
                opacity: 0;
            }
        }

        /* Error Container */
        .error-container {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 20px;
            max-width: 500px;
            width: 90%;
        }

        /* 404 Number */
        .error-code {
            font-size: 100px;
            font-weight: 700;
            color: #3b82f6;
            line-height: 1;
            margin-bottom: 15px;
            text-shadow: 0 0 20px rgba(59, 130, 246, 0.5);
            animation: glow 2s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from {
                text-shadow: 0 0 15px rgba(59, 130, 246, 0.5);
            }
            to {
                text-shadow: 0 0 30px rgba(59, 130, 246, 0.8), 0 0 40px rgba(59, 130, 246, 0.6);
            }
        }

        /* Icon */
        .error-icon {
            font-size: 70px;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-15px);
            }
        }

        /* Card */
        .error-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }

        /* Text */
        .error-title {
            font-size: 24px;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 10px;
        }

        .error-message {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 25px;
            line-height: 1.5;
        }

        /* Buttons */
        .button-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 100%;
        }

        .btn-home, .btn-back {
            width: 100%;
            padding: 12px 24px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-home {
            background: linear-gradient(135deg, #3b82f6, #1e3a8a);
            color: white;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.6);
            color: white;
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .error-code {
                font-size: 80px;
            }

            .error-icon {
                font-size: 60px;
            }

            .error-title {
                font-size: 20px;
            }

            .error-message {
                font-size: 13px;
            }

            .error-card {
                padding: 30px 25px;
            }

            .btn-home, .btn-back {
                padding: 11px 20px;
                font-size: 13px;
            }

            /* Logo lebih kecil di mobile */
            .bg-animation .floating-logo {
                width: 40px !important;
                height: 40px !important;
            }
        }

        /* Desktop - 2 kolom untuk button */
        @media (min-width: 769px) {
            .button-group {
                flex-direction: row;
                gap: 12px;
            }

            .btn-home, .btn-back {
                flex: 1;
            }
        }
    </style>
</head>
<body>
    <!-- ✅ BARU: Animated Background dengan Logo ICLABS -->
    <div class="bg-animation">
        <div class="floating-logo"></div>
        <div class="floating-logo"></div>
        <div class="floating-logo"></div>
        <div class="floating-logo"></div>
        <div class="floating-logo"></div>
        <div class="floating-logo"></div>
        <div class="floating-logo"></div>
    </div>

    <!-- Error Container -->
    <div class="error-container">
        <div class="error-card">
            <!-- Icon -->
            <div class="error-icon">
                <i class="bi bi-exclamation-triangle"></i>
            </div>

            <!-- 404 Number -->
            <div class="error-code">404</div>

            <!-- Title -->
            <h1 class="error-title">Halaman Tidak Ditemukan</h1>

            <!-- Message -->
            <p class="error-message">
                Maaf, halaman yang Anda cari tidak dapat ditemukan. 
                Mungkin halaman telah dipindahkan atau URL yang Anda masukkan salah.
            </p>

            <!-- Buttons -->
            <div class="button-group">
                <a href="<?= BASE_URL; ?>" class="btn-home">
                    <i class="bi bi-house-door"></i>
                    Kembali ke Beranda
                </a>
                <a href="javascript:history.back()" class="btn-back">
                    <i class="bi bi-arrow-left"></i>
                    Halaman Sebelumnya
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>