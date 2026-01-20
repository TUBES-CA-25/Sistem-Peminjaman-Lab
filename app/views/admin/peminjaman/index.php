<?php
// views/admin/peminjaman/index.php
?>

<!-- HERO SECTION -->
<!-- HERO SECTION -->
<div class="card border-0 shadow-sm mb-4 bg-gradient-primary-custom text-white overflow-hidden">
  <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
      <h1 class="h2 fw-bold mb-1 text-white">Data Peminjaman</h1>
      <p class="mb-0 opacity-75">Kelola dan monitoring peminjaman laboratorium.</p>
    </div>

    <div class="d-flex gap-2 flex-wrap align-items-center">
      <button type="button" class="btn btn-light fw-bold d-flex align-items-center gap-2 shadow-sm text-primary"
        onclick="openBookingModal()">
        <i class="fas fa-plus"></i>
        Tambah Peminjaman
      </button>
      <button type="button" class="btn btn-outline-light fw-bold d-flex align-items-center gap-2 shadow-sm"
        onclick="exportReport()">
        <i class="fas fa-download"></i>
        Export Laporan
      </button>
    </div>
  </div>
</div>

<!-- TABLE SECTION -->
<?php include 'list.php'; ?>

<!-- MODALS -->
<?php include 'modal.php'; ?>

<!-- JAVASCRIPT -->
<?php include 'script.php'; ?>

<!-- External Library untuk Export Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>