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
    <title>503 - Maintenance Mode | ICLABS</title>
    
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

        /* Animated Background dengan Logo ICLABS */
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
            max-width: 550px;
            width: 90%;
        }

        /* 503 Number */
        .error-code {
            font-size: 100px;
            font-weight: 700;
            color: #8b5cf6;
            line-height: 1;
            margin-bottom: 15px;
            text-shadow: 0 0 20px rgba(139, 92, 246, 0.5);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                text-shadow: 0 0 20px rgba(139, 92, 246, 0.5);
            }
            50% {
                transform: scale(1.05);
                text-shadow: 0 0 40px rgba(139, 92, 246, 0.8), 0 0 60px rgba(139, 92, 246, 0.6);
            }
        }

        /* Icon */
        .error-icon {
            font-size: 70px;
            color: #8b5cf6;
            margin-bottom: 20px;
            animation: rotate 4s linear infinite;
        }

        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
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

        /* Warning Box */
        .warning-box {
            background: rgba(139, 92, 246, 0.1);
            border: 1px solid rgba(139, 92, 246, 0.3);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 25px;
            color: rgba(255, 255, 255, 0.9);
            font-size: 13px;
        }

        .warning-box i {
            color: #8b5cf6;
            margin-right: 5px;
        }

        /* Progress Bar */
        .progress-container {
            margin: 20px 0;
        }

        .progress-bar-custom {
            width: 100%;
            height: 8px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #8b5cf6, #6366f1);
            border-radius: 10px;
            animation: progress 3s ease-in-out infinite;
        }

        @keyframes progress {
            0% {
                width: 0%;
            }
            50% {
                width: 70%;
            }
            100% {
                width: 0%;
            }
        }

        .progress-text {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
        }

        /* Buttons */
        .button-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 100%;
        }

        .btn-reload, .btn-home {
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

        .btn-reload {
            background: linear-gradient(135deg, #8b5cf6, #6366f1);
            color: white;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.4);
        }

        .btn-reload:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(139, 92, 246, 0.6);
            color: white;
        }

        .btn-home {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-home:hover {
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

            .btn-reload, .btn-home {
                padding: 11px 20px;
                font-size: 13px;
            }

            .bg-animation .floating-logo {
                width: 40px !important;
                height: 40px !important;
            }
        }

        @media (min-width: 769px) {
            .button-group {
                flex-direction: row;
                gap: 12px;
            }

            .btn-reload, .btn-home {
                flex: 1;
            }
        }
    </style>
</head>
<body>
    <!-- Animated Background dengan Logo ICLABS -->
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
                <i class="bi bi-gear-fill"></i>
            </div>

            <!-- 503 Number -->
            <div class="error-code">503</div>

            <!-- Title -->
            <h1 class="error-title">Sedang Maintenance</h1>

            <!-- Message -->
            <p class="error-message">
                Website sedang dalam proses pemeliharaan untuk meningkatkan kualitas layanan. 
                Kami akan segera kembali. Terima kasih atas pengertian Anda.
            </p>

            <!-- Progress Bar -->
            <div class="progress-container">
                <div class="progress-bar-custom">
                    <div class="progress-fill"></div>
                </div>
                <div class="progress-text">
                    <i class="bi bi-hourglass-split"></i> Sedang dalam proses...
                </div>
            </div>

            <!-- Warning Box -->
            <div class="warning-box">
                <i class="bi bi-clock-fill"></i>
                <strong>Estimasi:</strong> Maintenance diperkirakan selesai dalam beberapa saat. Silakan coba kembali nanti.
            </div>

            <!-- Buttons -->
            <div class="button-group">
                <a href="javascript:location.reload()" class="btn-reload">
                    <i class="bi bi-arrow-clockwise"></i>
                    Muat Ulang Halaman
                </a>
                <a href="<?= BASE_URL; ?>" class="btn-home">
                    <i class="bi bi-house-door"></i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Auto Reload Script (Optional) -->
    <script>
        // Auto reload setiap 30 detik (opsional, bisa dihapus jika tidak perlu)
        // setTimeout(function() {
        //     location.reload();
        // }, 30000);
    </script>
</body>
</html>