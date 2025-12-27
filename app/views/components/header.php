<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>ICLABS - Sistem Peminjaman Lab</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet"/>

    <style>
        body { font-family: 'Inter', sans-serif; padding-top: 80px; background-color: #F8FAFC; }
        .navbar { background-color: rgba(255, 255, 255, 0.9) !important; backdrop-filter: blur(10px); border-bottom: 1px solid #e2e8f0; }
        .navbar-brand { display: flex; align-items: center; gap: 10px; font-family: 'Playfair Display', serif; font-weight: 700; color: #0F172A !important; }
        .navbar-brand img { height: 40px; width: auto; border-radius: 4px; }
        .nav-link { font-weight: 500; color: #475569 !important; }
        .nav-link:hover { color: #2563EB !important; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-md fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
            <img src="/public/img/logo-iclabs.png" alt="Logo ICLABS" style="height: 40px; width: auto;">
            <span class="font-display">ICLABS</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="/index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Laboratorium</a></li>
                <li class="nav-item"><a class="nav-link" href="/pages/kontak/kontak.php">Kontak</a></li>
                <li class="nav-item"><a class="nav-link" href="/path/ke/login/aan.php">Login</a></li>
            </ul>
        </div>
    </div>
</nav>