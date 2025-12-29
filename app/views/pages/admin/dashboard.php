<?php
// app/views/pages/admin/dashboard.php

$page = $_GET['page'] ?? 'ruangan';

// include sidebar (ini sudah buka <main class="main-content">)
$sidebar_path = __DIR__ . '/../../components/admin_sidebar.php';
if (!file_exists($sidebar_path)) {
    die('Sidebar tidak ditemukan: ' . htmlspecialchars($sidebar_path));
}
require $sidebar_path;

// mapping konten (SEMUA pakai __DIR__ biar konsisten)
switch ($page) {
    case 'ruangan':
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

// render konten
if ($content_file && file_exists($content_file)) {
    require $content_file;
} else {
    echo '<div style="padding:1rem;background:#fee2e2;color:#991b1b;border-radius:12px;">
            Halaman tidak ditemukan
          </div>';
}
?>

        </main>
    </div>
</body>
</html>