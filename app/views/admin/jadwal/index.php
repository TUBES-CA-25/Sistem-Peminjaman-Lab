<?php
// views/admin/jadwal/index.php
?>

<!-- HERO SECTION -->
<!-- HERO SECTION -->
<div class="card border-0 shadow-sm mb-4 bg-gradient-primary-custom text-white overflow-hidden">
  <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
      <h1 class="h2 fw-bold mb-1 text-white">Tambah Jadwal Praktikum</h1>
      <p class="mb-0 opacity-75">Kelola jadwal praktikum tetap untuk setiap laboratorium.</p>
    </div>

    <div class="d-flex gap-2 flex-wrap align-items-center">
      <button type="button" class="btn btn-light fw-bold d-flex align-items-center gap-2 shadow-sm text-primary"
        onclick="openScheduleModal()">
        <i class="fas fa-calendar-plus"></i>
        Tambah Jadwal Baru
      </button>
      <button type="button" class="btn btn-outline-light fw-bold d-flex align-items-center gap-2 shadow-sm"
        onclick="exportJadwalReport()">
        <i class="fas fa-download"></i>
        Export Jadwal
      </button>
    </div>
  </div>
</div>

<!-- INFO BOX -->
<div class="p-info">
  <i class="fas fa-info-circle"></i>
  <div>
    <div class="p-info-title">Tentang Jadwal Praktikum Tetap</div>
    <div class="p-info-text">
      Jadwal praktikum tetap adalah jadwal rutin yang berulang setiap minggu.
      Jadwal ini akan otomatis terblokir saat pembuatan peminjaman laboratorium.
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