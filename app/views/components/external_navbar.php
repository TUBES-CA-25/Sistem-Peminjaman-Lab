<nav class="navbar">
    <a href="<?= BASE_URL ?>/external" class="navbar-brand">
        <img src="<?= BASE_URL ?>/public/storage/images/logo-iclabs.png" alt="Logo ICLABS" height="40" style="vertical-align: middle; margin-right: 8px;">
        
        ICLABS <span class="admin-badge" style="background: #dcfce7; color: #059669;">External</span>
    </a>

    <div class="navbar-menu">
        <!-- Sign Out Button: Visible on Desktop, Hidden on Mobile -->
        <a href="<?= BASE_URL ?>/auth/logout" class="btn-signout d-none d-md-block">Sign Out</a>
        
        <!-- Hamburger Button: Visible on Mobile, Hidden on Desktop -->
        <button id="mobileNavbarToggle" class="d-md-none navbar-hamburger">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>