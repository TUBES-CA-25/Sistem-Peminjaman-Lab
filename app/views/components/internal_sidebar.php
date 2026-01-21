<div class="admin-container">
    <aside class="sidebar">
        <div class="sidebar-header">MENU</div>

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
        </ul>
    </aside>

    <main class="main-content">
