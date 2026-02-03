<nav class="navbar">
    <a href="<?= BASE_URL ?>/external" class="navbar-brand">
        <img src="<?= BASE_URL ?>/public/storage/images/logo-iclabs.png" alt="Logo ICLABS" height="40"
            style="vertical-align: middle; margin-right: 8px;">
        ICLABS
    </a>

    <div class="navbar-menu">
        <!-- Theme Toggle Dropdown -->
        <div class="dropdown me-3">
            <button class="btn btn-link nav-link p-0 dropdown-toggle no-caret" type="button" id="themeToggle"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-sun-fill theme-icon-active"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2" aria-labelledby="themeToggle">
                <li>
                    <button class="dropdown-item d-flex align-items-center gap-2" type="button"
                        data-theme-value="light">
                        <i class="bi bi-sun-fill text-warning"></i> Light
                    </button>
                </li>
                <li>
                    <button class="dropdown-item d-flex align-items-center gap-2" type="button" data-theme-value="dark">
                        <i class="bi bi-moon-stars-fill text-primary"></i> Dark
                    </button>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <button class="dropdown-item d-flex align-items-center gap-2" type="button" data-theme-value="auto">
                        <i class="bi bi-circle-half"></i> Auto
                    </button>
                </li>
            </ul>
        </div>

        <!-- Sign Out Button: Visible on Desktop, Hidden on Mobile -->
        <a href="<?= BASE_URL ?>/auth/logout" class="btn-signout d-none d-md-block">Sign Out</a>

        <!-- Hamburger Button: Visible on Mobile, Hidden on Desktop -->
        <button id="mobileNavbarToggle" class="d-md-none navbar-hamburger">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>