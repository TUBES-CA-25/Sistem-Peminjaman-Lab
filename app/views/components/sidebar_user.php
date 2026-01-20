<div class="sidebar-user bg-white shadow-sm d-flex flex-column p-3">
    <div class="text-center mb-4 mt-2">
        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 60px; height: 60px; font-size: 24px;">
            <i class="bi bi-person-fill"></i>
        </div>
        <h6 class="fw-bold mb-0 text-dark">
            <?= isset($data['user']['nama_lengkap']) ? htmlspecialchars(explode(' ', $data['user']['nama_lengkap'])[0]) : 'User'; ?>
        </h6>
        <small class="text-muted" style="font-size: 0.75rem;">Peminjam Eksternal</small>
    </div>

    <hr class="text-secondary opacity-25">

    <ul class="nav nav-pills flex-column gap-2 mb-auto">
        <li class="nav-item">
            <a href="<?= BASE_URL; ?>/external" class="nav-link <?= ($data['judul'] == 'Dashboard Peminjaman') ? 'active fw-bold' : 'text-dark'; ?>">
                <i class="bi bi-grid-fill me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= BASE_URL; ?>/external/profile" class="nav-link <?= ($data['judul'] == 'Profil Saya') ? 'active fw-bold' : 'text-dark'; ?>">
                <i class="bi bi-person-lines-fill me-2"></i> Profil Saya
            </a>
        </li>
    </ul>

    <hr class="text-secondary opacity-25">

    <a href="<?= BASE_URL; ?>/auth/logout" class="btn btn-danger w-100 fw-bold">
        <i class="bi bi-box-arrow-right me-2"></i> Logout
    </a>
</div>