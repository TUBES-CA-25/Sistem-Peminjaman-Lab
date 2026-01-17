<!DOCTYPE html>
<html lang="id">

<body class="d-flex flex-column min-vh-100">
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>ICLABS - Sistem Peminjaman Lab</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Playfair+Display:wght@700&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet" />

    <!-- Global Custom CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
    </head>

    <body>

        <nav class="navbar navbar-expand fixed-top">
            <div class="container">
                <!-- Navbar Brand -->
                <a class="navbar-brand d-flex align-items-center gap-2" href="<?= BASE_URL ?>">
                    <img src="<?= BASE_URL ?>/public/img/logo-iclabs.png" alt="Logo ICLABS">
                    <span class="font-display">ICLABS</span>
                </a>
                <!-- <span class="font-display">ICLABS</span> -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <!-- Example Nav Links if needed -->
                        <!-- <li class="nav-item">
                        <a class="nav-link" href="#">Beranda</a>
                    </li> -->
                    </ul>
                </div>
            </div>
        </nav>