<link rel="stylesheet" href="<?= BASE_URL; ?>/css/external.css">

<div class="container-fluid hero-bg">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold mb-1">Dashboard Peminjaman</h2>
            <p class="opacity-75 mb-0">Pantau status pengajuan peminjaman ruangan Anda di sini.</p>
        </div>
        <button type="button" class="btn btn-light text-primary fw-bold px-4 py-2 shadow-sm" onclick="openAddModal()">
            <i class="bi bi-plus-lg me-2"></i> Ajukan Baru
        </button>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="dashboard-card">
                <?php include 'list.php'; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'modal.php'; ?>
<?php include 'script.php'; ?>