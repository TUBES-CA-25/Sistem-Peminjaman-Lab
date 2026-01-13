<div class="admin-container">
    <aside class="sidebar">
        <div class="sidebar-header">DATA</div>

        <ul class="sidebar-menu">
            <li class="sidebar-item">
                <a href="<?= BASE_URL ?>ruangan"
                    class="sidebar-link <?= ($active_page === 'ruangan') ? 'active' : ''; ?>">
                    <i class="fas fa-door-open sidebar-icon"></i>
                    <span>Data Ruangan</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a href="<?= BASE_URL ?>pengguna"
                    class="sidebar-link <?= ($active_page === 'pengguna') ? 'active' : ''; ?>">
                    <i class="fas fa-users sidebar-icon"></i>
                    <span>Data Pengguna</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a href="<?= BASE_URL ?>peminjaman"
                    class="sidebar-link <?= ($active_page === 'peminjaman') ? 'active' : ''; ?>">
                    <i class="fas fa-clipboard-list sidebar-icon"></i>
                    <span>Data Peminjaman</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-header" style="margin-top: 2rem;">MENU LAINNYA</div>

        <ul class="sidebar-menu">
            <li class="sidebar-item">
                <a href="<?= BASE_URL ?>jadwal"
                    class="sidebar-link <?= ($active_page === 'jadwal') ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-plus sidebar-icon"></i>
                    <span>Tambah Jadwal</span>
                </a>
            </li>
        </ul>
    </aside>

    <main class="main-content">