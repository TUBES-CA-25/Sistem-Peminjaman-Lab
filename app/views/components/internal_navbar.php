<nav class="navbar">
    <a href="<?= BASE_URL ?>/internal/booking" class="navbar-brand">
        <img src="<?= BASE_URL ?>/public/storage/images/logo-iclabs.png" alt="Logo ICLABS" height="40"
            style="vertical-align: middle; margin-right: 8px;">

        ICLABS <span class="admin-badge-internal">Internal</span>
    </a>

    <div class="navbar-menu d-flex align-items-center gap-3">
        <a href="<?= BASE_URL ?>/auth/logout" class="btn-signout mt-0 d-none d-md-block">Sign Out</a>

        <!-- Hamburger Button: Mobile only -->
        <button id="mobileNavbarToggle" class="d-md-none navbar-hamburger">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>