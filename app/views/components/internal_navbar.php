<nav class="navbar">
    <a href="<?= BASE_URL ?>/internal/booking" class="navbar-brand">
        ICLABS <span class="admin-badge" style="background: #dcfce7; color: #059669;">Internal</span>
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