<?php
// views/admin/kelas/index.php
?>

<!-- HEADER SECTION -->
<div class="card border-0 shadow-sm mb-4 bg-gradient-primary-custom text-white overflow-hidden">
    <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h1 class="h2 fw-bold mb-1 text-white">Data Kelas</h1>
            <p class="mb-0 opacity-75">Kelola data kelas, jurusan, dan angkatan.</p>
        </div>
        <button type="button" class="btn btn-light fw-bold d-flex align-items-center gap-2 shadow-sm"
            data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus text-primary"></i>
            Tambah Kelas
        </button>
    </div>
</div>

<!-- TABLE SECTION -->
<?php include 'list.php'; ?>

<!-- MODALS -->
<?php include 'modal.php'; ?>

<!-- JAVASCRIPT -->
<?php include 'script.php'; ?>
