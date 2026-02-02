<?php
$matakuliah = $data['matakuliah'] ?? [];
?>

<!-- HEADER SECTION -->
<div class="card border-0 shadow-sm mb-4 bg-gradient-primary-custom text-white overflow-hidden">
    <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h1 class="h2 fw-bold mb-1 text-white">Data Mata Kuliah</h1>
            <p class="mb-0 opacity-75">Kelola data mata kuliah, sks, dan semester.</p>
        </div>
        <button type="button" class="btn btn-light fw-bold d-flex align-items-center gap-2 shadow-sm"
            data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus text-primary"></i>
            Tambah Mata Kuliah
        </button>
    </div>
</div>

<!-- NOTIFICATION -->
<!-- NOTIFICATION (Swal) -->
<?php if (isset($_GET['status']) && isset($_GET['msg'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: '<?= $_GET['status'] == 'success' ? 'success' : 'error' ?>',
                title: '<?= $_GET['status'] == 'success' ? 'Berhasil!' : 'Gagal!' ?>',
                text: '<?= htmlspecialchars($_GET['msg']) ?>',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
            // Clean URL
            window.history.replaceState({}, document.title, window.location.pathname);
        });
    </script>
<?php endif; ?>

<!-- LIST TABLE -->
<?php require_once 'list.php'; ?>

<!-- MODALS -->
<?php require_once 'modal.php'; ?>

<!-- SCRIPTS -->
<?php require_once 'script.php'; ?>
