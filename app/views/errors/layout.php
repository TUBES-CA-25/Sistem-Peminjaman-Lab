<?php
// Default values if not set
$code = $code ?? 'Error';
$title = $title ?? 'Terjadi Kesalahan';
$message = $message ?? 'Silakan hubungi administrator.';
$icon = $icon ?? 'bi-exclamation-triangle';
$iconColor = $iconColor ?? '#fbbf24'; // Default yellow
$textColor = $textColor ?? '#fbbf24'; // Default yellow
$warning = $warning ?? '';
$buttons = $buttons ?? '';

// Define BASE_URL inside layout if not defined (safety fallback)
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/Sistem-Peminjaman-Lab');
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $code ?> - <?= $title ?> | ICLABS</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Custom Error CSS -->
    <link href="<?= BASE_URL ?>/public/css/error.css" rel="stylesheet">

    <style>
        :root {
            --text-color:
                <?= $textColor ?>
            ;
            --text-shadow-1:
                <?= $textColor ?>
                80;
            --text-shadow-2:
                <?= $textColor ?>
                CC;
            --text-shadow-3:
                <?= $textColor ?>
                99;

            --icon-color:
                <?= $iconColor ?>
            ;

            --warning-bg:
                <?= $iconColor ?>
                1A;
            --warning-border:
                <?= $iconColor ?>
                4D;
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
                <i class="bi <?= $icon ?>"></i>
            </div>

            <!-- Error Number -->
            <div class="error-code"><?= $code ?></div>

            <!-- Title -->
            <h1 class="error-title"><?= $title ?></h1>

            <!-- Message -->
            <p class="error-message">
                <?= $message ?>
            </p>

            <!-- Warning Box (Optional) -->
            <?php if (!empty($warning)): ?>
                <div class="warning-box">
                    <i class="bi bi-info-circle-fill"></i>
                    <?= $warning ?>
                </div>
            <?php endif; ?>

            <!-- Buttons -->
            <div class="button-group">
                <?= $buttons ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>