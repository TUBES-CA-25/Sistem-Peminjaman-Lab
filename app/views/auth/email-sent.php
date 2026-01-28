<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cek Email Anda | Peminjaman Lab</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            background: linear-gradient(120deg, #020617, #0f172a, #1e3a8a);
        }

        .email-sent-card {
            width: 100%;
            max-width: 520px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 50px 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        /* Email Icon */
        .email-icon-wrapper {
            margin-bottom: 24px;
        }

        .email-icon {
            width: 90px;
            height: 90px;
            background: rgba(59, 130, 246, 0.15);
            border: 2px solid rgba(59, 130, 246, 0.3);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: #60a5fa;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 15px rgba(59, 130, 246, 0);
            }
        }

        /* Info Box */
        .info-box {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.25);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: left;
        }

        .info-box .step-list {
            margin: 12px 0 0 0;
            padding-left: 24px;
            line-height: 1.8;
        }

        .info-box .step-list li {
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 6px;
        }

        /* Warning Box */
        .warning-box {
            background: rgba(251, 191, 36, 0.1);
            border: 1px solid rgba(251, 191, 36, 0.25);
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            text-align: left;
        }

        .warning-box i {
            color: #fbbf24;
            font-size: 1.2rem;
            flex-shrink: 0;
            margin-top: 2px;
        }

        /* Buttons */
        .btn {
            border-radius: 16px;
            padding: 14px 24px;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 40px rgba(59, 130, 246, 0.4);
        }

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

        .text-email {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 8px 16px;
            display: inline-block;
            margin-top: 8px;
            font-family: 'Courier New', monospace;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <div class="email-sent-card">
        <!-- Email Icon -->
        <div class="email-icon-wrapper">
            <div class="email-icon">
                <i class="bi bi-envelope-check-fill"></i>
            </div>
        </div>

        <!-- Heading -->
        <h2 class="fw-bold mb-2 text-white">Cek Email Anda</h2>
        <p class="mb-1 text-white-50">Peminjaman Lab</p>
        <p class="small mb-3 text-white-50 opacity-75">
            Kami telah mengirimkan link reset password ke:
        </p>
        <div class="text-email text-white mb-4">
            <?= htmlspecialchars($_SESSION['reset_email'] ?? 'email@anda.com') ?>
        </div>

        <!-- Info Box -->
        <div class="info-box">
            <div class="d-flex align-items-start gap-3 mb-2">
                <i class="bi bi-info-circle-fill text-primary" style="font-size: 1.5rem;"></i>
                <div class="text-start flex-grow-1">
                    <p class="mb-2 fw-bold text-white">Langkah selanjutnya:</p>
                    <ol class="step-list small text-white-50 mb-0">
                        <li>Buka inbox email Anda</li>
                        <li>Cari email dari <strong class="text-white">ICLABS</strong></li>
                        <li>Klik button <strong class="text-white">"Reset Password Saya"</strong></li>
                        <li>Buat password baru Anda</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Warning Box -->
        <div class="warning-box">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <small class="text-white-50">
                Tidak menerima email? Cek folder <strong class="text-white">Spam/Junk</strong> Anda. 
                Link akan kadaluarsa dalam <strong class="text-white">1 jam</strong>.
            </small>
        </div>

        <!-- Action Buttons -->
        <div class="d-grid gap-3">
            <a href="<?= BASE_URL ?>/auth/forgot" class="btn btn-outline-light">
                <i class="bi bi-arrow-clockwise me-2"></i>Kirim Ulang Email
            </a>
            <a href="<?= BASE_URL ?>/auth" class="btn btn-primary">
                <i class="bi bi-box-arrow-in-left me-2"></i>Kembali ke Login
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>