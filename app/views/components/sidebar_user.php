<?php 
// Ambil nama dari session atau data user
$userName = 'User'; // Default
if (isset($_SESSION['nama'])) {
    $userName = explode(' ', $_SESSION['nama'])[0]; 
} elseif (isset($data['user']['nama'])) {
    $userName = explode(' ', $data['user']['nama'])[0];
}

// Tentukan active page
$currentPage = $data['active_menu'] ?? '';
?>

<!-- Overlay untuk mobile (klik di luar sidebar untuk menutup) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar -->
<div class="sidebar-user bg-white shadow-sm d-flex flex-column p-3" id="sidebarUser">
    
    <!-- Tombol Close untuk Mobile -->
    <button class="btn-close-sidebar d-lg-none" id="closeSidebar">
        <i class="bi bi-x-lg"></i>
    </button>

    <div class="text-center mb-4 mt-2">
        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 60px; height: 60px; font-size: 24px;">
            <i class="bi bi-person-fill"></i>
        </div>
        <h6 class="fw-bold mb-0 text-dark">
            <?= htmlspecialchars($userName); ?>
        </h6>
        <small class="text-muted" style="font-size: 0.75rem;">Peminjam Eksternal</small>
    </div>

    <hr class="text-secondary opacity-25">

    <ul class="nav nav-pills flex-column gap-2 mb-auto">
        <li class="nav-item">
            <a href="<?= BASE_URL; ?>/external" class="nav-link <?= ($currentPage == 'dashboard') ? 'active fw-bold' : 'text-dark'; ?>">
                <i class="bi bi-grid-fill me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= BASE_URL; ?>/external/profile" class="nav-link <?= ($currentPage == 'profile') ? 'active fw-bold' : 'text-dark'; ?>">
                <i class="bi bi-person-lines-fill me-2"></i> Profil Saya
            </a>
        </li>
    </ul>

    <hr class="text-secondary opacity-25">

    <a href="<?= BASE_URL; ?>/auth/logout" class="btn btn-danger w-100 fw-bold">
        <i class="bi bi-box-arrow-right me-2"></i> Logout
    </a>
</div>

<!-- JavaScript untuk Toggle Sidebar -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebarUser');
    const overlay = document.getElementById('sidebarOverlay');
    const closeSidebar = document.getElementById('closeSidebar');
    const toggleBtn = document.getElementById('sidebarToggle');

    // Fungsi untuk membuka sidebar
    function openSidebar() {
        sidebar.classList.add('active');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden'; // Prevent scroll
    }

    // Fungsi untuk menutup sidebar
    function closeSidebarFunc() {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = ''; // Enable scroll
    }

    // Event listeners
    if (toggleBtn) {
        toggleBtn.addEventListener('click', openSidebar);
    }
    
    if (closeSidebar) {
        closeSidebar.addEventListener('click', closeSidebarFunc);
    }
    
    if (overlay) {
        overlay.addEventListener('click', closeSidebarFunc);
    }

    // Close sidebar saat klik link menu (untuk mobile)
    const navLinks = sidebar.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 992) {
                closeSidebarFunc();
            }
        });
    });
});
</script>