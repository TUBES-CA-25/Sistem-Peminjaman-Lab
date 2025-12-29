<?php
// app/views/components/admin_sidebar.php

$active_page = $_GET['page'] ?? 'ruangan';


?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - ICLABS</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }

        .navbar{
            background:#fff; padding:1rem 2rem;
            box-shadow:0 2px 4px rgba(0,0,0,0.1);
            display:flex; justify-content:space-between; align-items:center;
            position:fixed; top:0; left:0; right:0; z-index:1000;
        }
        .navbar-brand{
            display:flex; align-items:center; gap:.5rem;
            font-size:1.5rem; font-weight:700; color:#122E4F; text-decoration:none;
        }
        .admin-badge{
            background:#e0e7ff; color:#1F45AC;
            padding:.25rem .75rem; border-radius:9999px;
            font-size:.875rem; font-weight:500;
        }
        .navbar-menu{ display:flex; align-items:center; gap:1rem; }

        .btn-signout{
            background:#EF4444; color:#fff;
            padding:.5rem 1.25rem; border-radius:9999px;
            text-decoration:none; font-weight:600;
        }
        .btn-signout:hover{ background:#dc2626; }

        .admin-container{ display:flex; margin-top:70px; min-height:calc(100vh - 70px); }

        .sidebar{
            width:280px; background:#fff; padding:2rem 0;
            box-shadow:2px 0 4px rgba(0,0,0,0.05);
            position:fixed; left:0; top:70px; bottom:0; overflow-y:auto;
        }
        .sidebar-header{
            padding:0 1.5rem 1rem;
            color:#6b7280; font-size:.75rem; font-weight:700;
            text-transform:uppercase; letter-spacing:.05em;
        }

        .sidebar-menu{ list-style:none; }
        .sidebar-item{ margin:.25rem 0; }

        .sidebar-link{
            display:flex; align-items:center; gap:.75rem;
            padding:.875rem 1.5rem;
            color:#4b5563; text-decoration:none;
            transition:all .2s;
            border-left:3px solid transparent;
        }
        .sidebar-link:hover{ background:#f3f4f6; color:#1F45AC; }
        .sidebar-link.active{
            background:#eff6ff; color:#1F45AC;
            border-left-color:#1F45AC; font-weight:700;
        }
        .sidebar-icon{ width:20px; text-align:center; font-size:1.125rem; }

        .main-content{ flex:1; margin-left:280px; padding:2rem; }

        @media (max-width: 768px){
            .sidebar{ transform:translateX(-100%); transition:transform .3s; }
            .sidebar.active{ transform:translateX(0); }
            .main-content{ margin-left:0; }
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <a href="dashboard.php?page=ruangan" class="navbar-brand">
            ICLABS <span class="admin-badge">Admin</span>
        </a>

        <div class="navbar-menu">
            <a href="dashboard.php?page=ruangan" class="btn-signout">Sign Out</a>
        </div>
    </nav>

    <div class="admin-container">
        <aside class="sidebar">
            <div class="sidebar-header">Dashboard Admin</div>

            <ul class="sidebar-menu">
                <li class="sidebar-item">
                    <a href="dashboard.php?page=ruangan"
                       class="sidebar-link <?= ($active_page === 'ruangan') ? 'active' : ''; ?>">
                        <i class="fas fa-door-open sidebar-icon"></i>
                        <span>Data Ruangan</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="dashboard.php?page=pengguna"
                       class="sidebar-link <?= ($active_page === 'pengguna') ? 'active' : ''; ?>">
                        <i class="fas fa-users sidebar-icon"></i>
                        <span>Data Pengguna</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="dashboard.php?page=peminjaman"
                       class="sidebar-link <?= ($active_page === 'peminjaman') ? 'active' : ''; ?>">
                        <i class="fas fa-clipboard-list sidebar-icon"></i>
                        <span>Data Peminjaman</span>
                    </a>
                </li>
            </ul>
        </aside>

        <main class="main-content">