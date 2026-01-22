<!-- Sidebar Overlay -->
<div id="sidebarOverlay" class="sidebar-overlay"></div>

<div class="admin-container">
    <aside class="sidebar" id="internalSidebar">
        <!-- Close Button for Mobile -->
        <div class="sidebar-header-mobile">
            <span class="fw-bold">MENU</span>
            <button id="mobileSidebarClose" class="close-btn">&times;</button>
        </div>
        
        <div class="sidebar-header desktop-only">MENU</div>

        <ul class="sidebar-menu">
            <li class="sidebar-item">
                <a href="<?= BASE_URL ?>/internal/booking"
                    class="sidebar-link <?= ($active_page === 'booking') ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-check sidebar-icon"></i>
                    <span>Booking</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a href="<?= BASE_URL ?>/internal/jadwal"
                    class="sidebar-link <?= ($active_page === 'jadwal') ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-alt sidebar-icon"></i>
                    <span>Jadwal</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a href="<?= BASE_URL ?>/internal/history"
                    class="sidebar-link <?= ($active_page === 'history') ? 'active' : ''; ?>">
                    <i class="fas fa-clipboard-list sidebar-icon"></i>
                    <span>Data Peminjaman</span>
                </a>
            </li>
            
            <!-- Mobile Only Logout -->
            <li class="sidebar-item mobile-only" style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 10px;">
                 <a href="<?= BASE_URL ?>/auth/logout" class="sidebar-link" style="color: #ef4444;">
                    <i class="fas fa-sign-out-alt sidebar-icon"></i>
                    <span>Keluar</span>
                </a>
            </li>
        </ul>
    </aside>

    <main class="main-content">



<script>
document.addEventListener("DOMContentLoaded", function() {
    // Target the hamburger button that was added to internal_navbar.php
    const toggleBtn = document.getElementById('mobileNavbarToggle'); 
    
    const closeBtn = document.getElementById('mobileSidebarClose');
    const overlay = document.getElementById('sidebarOverlay');
    const sidebar = document.getElementById('internalSidebar');

    function openSidebar() {
        if(sidebar) sidebar.classList.add('active');
        if(overlay) overlay.classList.add('active');
        document.body.style.overflow = 'hidden'; // Prevent scroll body
    }

    function closeSidebar() {
        if(sidebar) sidebar.classList.remove('active');
        if(overlay) overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    if(toggleBtn) toggleBtn.addEventListener('click', openSidebar);
    if(closeBtn) closeBtn.addEventListener('click', closeSidebar);
    if(overlay) overlay.addEventListener('click', closeSidebar);
});
</script>
