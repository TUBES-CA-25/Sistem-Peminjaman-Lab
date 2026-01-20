<link rel="stylesheet" href="<?= BASE_URL; ?>/public/css/external.css">

<?php include __DIR__ . '/../components/sidebar_user.php'; ?>

<div class="main-content-user">
    
    <div class="container-fluid px-4">
        <div class="hero-bg rounded-3 mt-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="text-white">
                    <h2 class="fw-bold mb-1">Dashboard Peminjaman</h2>
                    <p class="opacity-75 mb-0">Pantau status pengajuan peminjaman ruangan Anda di sini.</p>
                </div>
                <button type="button" class="btn btn-light text-primary fw-bold px-4 py-2 shadow-sm" onclick="openAddModal()">
                    <i class="bi bi-plus-lg me-2"></i> Ajukan Baru
                </button>
            </div>
        </div>
    </div>

    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                <div class="dashboard-card mt-4">
                    <?php include 'list.php'; ?>
                </div>
            </div>
        </div>
    </div>
    
</div>

<?php include 'modal.php'; ?>
<?php include 'script.php'; ?>