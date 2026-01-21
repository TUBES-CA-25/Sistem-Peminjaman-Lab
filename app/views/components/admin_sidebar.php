<?php $active_page = $data['active_page'] ?? ''; ?>
<div class="admin-container">
    <aside class="sidebar">
        <div class="sidebar-header">DATA</div>

        <ul class="sidebar-menu">
            <li class="sidebar-item">
                <a href="<?= BASE_URL ?>/ruangan"
                    class="sidebar-link <?= ($active_page === 'ruangan') ? 'active' : ''; ?>">
                    <i class="fas fa-door-open sidebar-icon"></i>
                    <span>Data Ruangan</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a href="<?= BASE_URL ?>/user"
                    class="sidebar-link <?= ($active_page === 'users') ? 'active' : ''; ?>">
                    <i class="fas fa-users sidebar-icon"></i>
                    <span>Data Pengguna</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a href="<?= BASE_URL ?>/peminjaman"
                    class="sidebar-link <?= ($active_page === 'peminjaman') ? 'active' : ''; ?>">
                    <i class="fas fa-clipboard-list sidebar-icon"></i>
                    <span>Data Peminjaman</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a href="<?= BASE_URL ?>/pengajuan"
                    class="sidebar-link <?= ($active_page === 'pengajuan') ? 'active' : ''; ?>">
                    <i class="fas fa-file-signature sidebar-icon"></i>
                    <span>Pengajuan Peminjaman</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-header" style="margin-top: 2rem;">MENU LAINNYA</div>

        <ul class="sidebar-menu">
            <li class="sidebar-item">
                <a href="<?= BASE_URL ?>/tahun_ajaran"
                    class="sidebar-link <?= ($active_page === 'tahun_ajaran') ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-alt sidebar-icon"></i>
                    <span>Tahun Ajaran</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="<?= BASE_URL ?>/jurusan"
                    class="sidebar-link <?= ($active_page === 'jurusan') ? 'active' : ''; ?>">
                    <i class="fas fa-university sidebar-icon"></i>
                    <span>Data Jurusan</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="<?= BASE_URL ?>/kelas" class="sidebar-link <?= ($active_page === 'kelas') ? 'active' : ''; ?>">
                    <i class="fas fa-layer-group sidebar-icon"></i>
                    <span>Data Kelas</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="<?= BASE_URL ?>/matakuliah"
                    class="sidebar-link <?= ($active_page === 'matakuliah') ? 'active' : ''; ?>">
                    <i class="fas fa-book sidebar-icon"></i>
                    <span>Data Mata Kuliah</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="<?= BASE_URL ?>/jadwal"
                    class="sidebar-link <?= ($active_page === 'jadwal') ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-plus sidebar-icon"></i>
                    <span>Tambah Jadwal</span>
                </a>
            </li>
        </ul>
    </aside>

    <main class="main-content">