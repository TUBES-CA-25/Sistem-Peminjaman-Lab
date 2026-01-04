<?php
// app/views/pages/admin/pengguna/index.php
$users = $data['pengguna'] ?? [];
?>

<!-- Custom CSS -->
<style>
    .bg-gradient-primary-custom {
        background: linear-gradient(135deg, #122E4F 0%, #1F45AC 100%);
    }

    .table-hover tbody tr:hover {
        background-color: #f8fafc;
    }
</style>

<!-- HEADER SECTION -->
<div class="card border-0 shadow-sm mb-4 bg-gradient-primary-custom text-white overflow-hidden">
    <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h1 class="h2 fw-bold mb-1 text-white">Data Pengguna</h1>
            <p class="mb-0 opacity-75">Kelola pengguna berdasarkan kategori: Internal, Eksternal.</p>
        </div>
        <button type="button" class="btn btn-light fw-bold d-flex align-items-center gap-2 shadow-sm"
            data-bs-toggle="modal" data-bs-target="#userModal" onclick="prepareModal('add')">
            <i class="fas fa-user-plus text-primary"></i>
            Tambah Pengguna
        </button>
    </div>
</div>

<!-- NOTIFICATION -->
<?php if (isset($_GET['status']) && isset($_GET['msg'])): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
        <i class="fas fa-check-circle me-2"></i> <strong>
            <?= htmlspecialchars($_GET['msg']) ?>!
        </strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- TOOLS: FILTER & SEARCH -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <div class="row g-3 align-items-center">
            <!-- Filters -->
            <div class="col-md-auto">
                <div class="d-flex gap-2 flex-wrap" id="filterButtons">
                    <button class="btn btn-sm btn-dark rounded-pill px-3 active" data-filter="semua">Semua</button>
                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                        data-filter="eksternal">Eksternal</button>
                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                        data-filter="internal">Internal</button>
                </div>
            </div>

            <!-- Search -->
            <div class="col-md ms-auto">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" id="searchInput" class="form-control border-start-0 ps-0"
                        placeholder="Cari nama / email / posisi...">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- LIST & MODAL -->
<?php include __DIR__ . '/list.php'; ?>
<?php include __DIR__ . '/modal.php'; ?>
<?php include __DIR__ . '/script.php'; ?>