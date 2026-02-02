<?php $active_page = $data['active_page'] ?? ''; ?>
<!-- Sidebar Overlay -->
<div id="sidebarOverlay" class="sidebar-overlay"></div>

<div class="admin-container">
    <aside class="sidebar" id="internalSidebar">
        <!-- Close Button for Mobile -->
        <div class="sidebar-header-mobile">
            <span class="fw-bold">MENU</span>
            <button id="mobileSidebarClose" class="close-btn">&times;</button>
        </div>

        <div class="sidebar-header desktop-only">DASHBOARD</div>

        <ul class="sidebar-menu">
            <li class="sidebar-item">
                <a href="<?= BASE_URL ?>/internal/jadwal"
                    class="sidebar-link <?= ($active_page === 'dashboard') ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt sidebar-icon"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-header" style="margin-top: 2rem;">MENU</div>

        <ul class="sidebar-menu">
            <li class="sidebar-item">
                <a href="<?= BASE_URL ?>/internal/booking"
                    class="sidebar-link <?= ($active_page === 'booking') ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-check sidebar-icon"></i>
                    <span>Booking</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a href="<?= BASE_URL ?>/internal/history"
                    class="sidebar-link <?= ($active_page === 'history') ? 'active' : ''; ?>">
                    <i class="fas fa-clipboard-list sidebar-icon"></i>
                    <span>Data Peminjaman</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-header" style="margin-top: 2rem;">AKUN</div>

        <ul class="sidebar-menu">
            <li class="sidebar-item">
                <a href="<?= BASE_URL ?>/internal/profile"
                    class="sidebar-link <?= ($active_page === 'profile') ? 'active' : ''; ?>">
                    <i class="fas fa-user-circle sidebar-icon"></i>
                    <span>Profil Saya</span>
                </a>
            </li>
            
            <!-- Logout (Mobile only) -->
            <li class="sidebar-item d-md-none" style="margin-top: 2rem; border-top: 1px solid #e5e7eb; padding-top: 1rem;">
                <a href="<?= BASE_URL ?>/auth/logout"
                    class="sidebar-link" style="color: #ef4444;">
                    <i class="fas fa-sign-out-alt sidebar-icon" style="color: #ef4444;"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </aside>

    <main class="main-content">

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const toggleBtn = document.getElementById('mobileNavbarToggle');
                const closeBtn = document.getElementById('mobileSidebarClose');
                const overlay = document.getElementById('sidebarOverlay');
                const sidebar = document.getElementById('internalSidebar');

                function toggleSidebar() {
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('active');
                    if (sidebar.classList.contains('active')) {
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.body.style.overflow = '';
                    }
                }

                function closeSidebar() {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                    document.body.style.overflow = '';
                }

                if (toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);
                if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
                if (overlay) overlay.addEventListener('click', closeSidebar);
            });
        </script>
