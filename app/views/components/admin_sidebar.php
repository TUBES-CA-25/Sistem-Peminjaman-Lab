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