<!-- Sidebar Overlay -->
<div id="sidebarOverlay" class="sidebar-overlay"></div>

<div class="admin-container">
    <aside class="sidebar" id="externalSidebar">
        <!-- Close Button for Mobile -->
        <div class="sidebar-header-mobile">
            <span class="fw-bold">MENU</span>
            <button id="mobileSidebarClose" class="close-btn">&times;</button>
        </div>

        <div class="sidebar-header desktop-only">MENU</div>

        <ul class="sidebar-menu">
            <li class="sidebar-item">
                <a href="<?= BASE_URL ?>/external/profile"
                    class="sidebar-link <?= (isset($active_menu) && $active_menu === 'profile') ? 'active' : ''; ?>">
                    <i class="fas fa-user sidebar-icon"></i>
                    <span>Profil Saya</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a href="<?= BASE_URL ?>/external"
                    class="sidebar-link <?= (isset($active_menu) && $active_menu === 'dashboard') ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt sidebar-icon"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Mobile Only Logout -->
            <li class="sidebar-item mobile-only"
                style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 10px;">
                <a href="<?= BASE_URL ?>/auth/logout" class="sidebar-link" style="color: #ef4444;">
                    <i class="fas fa-sign-out-alt sidebar-icon"></i>
                    <span>Keluar</span>
                </a>
            </li>
        </ul>
    </aside>

    <main class="main-content">
        <!-- Content will be inserted here by the page -->

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                // Target the hamburger button that was added to external_navbar.php
                const toggleBtn = document.getElementById('mobileNavbarToggle');

                const closeBtn = document.getElementById('mobileSidebarClose');
                const overlay = document.getElementById('sidebarOverlay');
                const sidebar = document.getElementById('externalSidebar');

                function openSidebar() {
                    if (sidebar) sidebar.classList.add('active');
                    if (overlay) overlay.classList.add('active');
                    document.body.style.overflow = 'hidden'; // Prevent scroll body
                }

                function closeSidebar() {
                    if (sidebar) sidebar.classList.remove('active');
                    if (overlay) overlay.classList.remove('active');
                    document.body.style.overflow = '';
                }

                if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
                if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
                if (overlay) overlay.addEventListener('click', closeSidebar);
            });
        </script>
