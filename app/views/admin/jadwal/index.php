<?php
// views/admin/jadwal/index.php
?>

<!-- HERO SECTION -->
<div style="background: linear-gradient(135deg, #122E4F 0%, #1F45AC 100%); padding: 2.5rem; border-radius: 12px; margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1); gap: 1.5rem; flex-wrap: wrap;">
  <div style="flex: 1; min-width: 250px;">
    <h1 style="color: white; font-size: 2rem; font-weight: 800; margin: 0 0 0.5rem 0;">Tambah Jadwal Praktikum</h1>
    <p style="color: rgba(255,255,255,0.9); font-size: 1rem; margin:0;">Kelola jadwal praktikum tetap untuk setiap laboratorium</p>
  </div>
  
  <div style="display:flex; gap:10px; flex-wrap:wrap; align-items: center;">
    <button type="button" class="p-add-btn" onclick="openScheduleModal()">
      <i class="fas fa-calendar-plus"></i>
      Tambah Jadwal Baru
    </button>
    <button type="button" class="p-export-btn" onclick="exportJadwalReport()">
      <i class="fas fa-download"></i>
      Export Jadwal
    </button>
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