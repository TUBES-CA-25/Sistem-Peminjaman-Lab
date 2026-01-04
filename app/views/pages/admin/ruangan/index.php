<?php
// app/views/pages/admin/ruangan/index.php
$labs = $data['ruangan'] ?? [];
$assistants = $data['asisten'] ?? [];
?>

<style>
    .bg-gradient-primary-custom {
        background: linear-gradient(135deg, #122E4F 0%, #1F45AC 100%);
    }

    .lab-card {
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .lab-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }

    .upload-area {
        border: 2px dashed #dee2e6;
        transition: all 0.3s;
    }

    .upload-area:hover,
    .upload-area.dragover {
        border-color: #0d6efd;
        background-color: #f8f9fa;
    }
</style>

<!-- HEADER SECTION -->
<div class="card border-0 shadow-sm mb-4 bg-gradient-primary-custom text-white">
    <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h1 class="h2 fw-bold mb-1 text-white">Data Ruangan Laboratorium</h1>
            <p class="mb-0 opacity-75">Kelola informasi ruangan, fasilitas, dan status laboratorium.</p>
        </div>
        <button type="button" class="btn btn-light fw-bold d-flex align-items-center gap-2 shadow-sm"
            onclick="openModal('add')">
            <i class="fas fa-plus text-primary"></i>
            Tambah Ruangan
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

<!-- LIST & MODAL -->
<?php include __DIR__ . '/list.php'; ?>
<?php include __DIR__ . '/modal.php'; ?>
<?php include __DIR__ . '/script.php'; ?>