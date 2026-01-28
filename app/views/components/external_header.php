<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/public/storage/images/logo-iclabs.png">
    <title>ICLABS - Dashboard External</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Playfair+Display:wght@700&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet" />

    <!-- Global Custom CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">

    <!-- External Dashboard Specific CSS -->
    <link rel="stylesheet" href="<?= BASE_URL; ?>/public/css/external.css">
</head>

<body class="d-flex flex-column min-vh-100">

        <!-- Navbar for External - Often uses external_navbar.php but base header usually has empty body tag -->
        <!-- If external.css hides navbar, then standard navbar here is kept hidden -->
        <nav class="navbar navbar-expand fixed-top">
            <div class="container">
                <!-- Navbar Brand -->
                <a class="navbar-brand d-flex align-items-center gap-2" href="<?= BASE_URL ?>">
                    <img src="<?= BASE_URL ?>/public/storage/images/logo-iclabs.png" alt="Logo ICLABS">
                    <span class="font-display">ICLABS</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                    </ul>
                </div>
            </div>
        </nav>
