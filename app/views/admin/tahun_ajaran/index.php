<?php
// app/views/pages/admin/tahun_ajaran/index.php
$tahun_ajaran = $data['tahun_ajaran'] ?? [];
?>

<!-- HEADER SECTION -->
<div class="card border-0 shadow-sm mb-4 bg-gradient-primary-custom text-white overflow-hidden">
    <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h1 class="h2 fw-bold mb-1 text-white">Data Tahun Ajaran</h1>
            <p class="mb-0 opacity-75">Kelola tahun ajaran akademik.</p>
        </div>
        <button type="button" class="btn btn-light fw-bold d-flex align-items-center gap-2 shadow-sm"
            data-bs-toggle="modal" data-bs-target="#taModal" onclick="prepareModal('add')">
            <i class="fas fa-plus text-primary"></i>
            Tambah Tahun Ajaran
        </button>
    </div>
</div>

<!-- NOTIFICATION -->
<?php if (isset($_GET['status']) && isset($_GET['msg'])): ?>
    <?php
    $alertClass = $_GET['status'] == 'success' ? 'alert-success' : 'alert-danger';
    $icon = $_GET['status'] == 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    ?>
    <div class="alert <?= $alertClass ?> alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
        <i class="fas <?= $icon ?> me-2"></i> <strong>
            <?= htmlspecialchars($_GET['msg']) ?>
        </strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- LIST & MODAL -->
<?php include __DIR__ . '/list.php'; ?>
<?php include __DIR__ . '/modal.php'; ?>
<?php include __DIR__ . '/script.php'; ?>