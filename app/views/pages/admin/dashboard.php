<?php
// app/views/pages/admin/dashboard.php

$page = $_GET['page'] ?? 'ruangan';
$active_page = $page; // For sidebar highlighting

// 0. HANDLE LOGIC BEFORE OUTPUT
$data = []; // Data container passed to views
$content_file = null;

switch ($page) {
    case 'ruangan':
        require_once __DIR__ . '/../../../controllers/RuanganController.php';
        $controller = new RuanganController();
        $controller->handleRequest(); // Handle POST requests (redirects happen here)
        $data['ruangan'] = $controller->index(); // Fetch data

        $content_file = __DIR__ . '/data_ruangan_content.php';
        break;
    case 'pengguna':
        $content_file = __DIR__ . '/data_pengguna_content.php';
        break;
    case 'peminjaman':
        $content_file = __DIR__ . '/data_peminjaman_content.php';
        break;
    default:
        $content_file = null;
        break;
}

// --------------------------------------------------------------------------
// START OUTPUT
// --------------------------------------------------------------------------

// 1. Include Head
require_once __DIR__ . '/../../components/admin_head.php';

// 2. Include Navbar
require_once __DIR__ . '/../../components/admin_navbar.php';
?>

<!-- 3. Open Layout Container -->
<div class="admin-container">

    <?php
    // 4. Include Sidebar
    require_once __DIR__ . '/../../components/admin_sidebar.php';
    ?>

    <!-- 5. Open Main Content -->
    <main class="main-content">

        <?php
        // 7. Render Content
        if ($content_file && file_exists($content_file)) {
            require $content_file;
        } else {
            echo '<div style="padding:1rem;background:#fee2e2;color:#991b1b;border-radius:12px;">
                    Halaman tidak ditemukan
                  </div>';
        }
        ?>

        <?php
        // 8. Include Footer (Closes main, div, body, html)
        require_once __DIR__ . '/../../components/admin_footer.php';
        ?>