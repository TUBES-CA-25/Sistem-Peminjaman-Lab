<?php
// views/admin/peminjaman/index.php
?>

<!-- HERO SECTION -->
<div style="background: linear-gradient(135deg, #122E4F 0%, #1F45AC 100%); padding: 2.5rem; border-radius: 12px; margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1); gap: 1.5rem; flex-wrap: wrap;">
  <div style="flex: 1; min-width: 250px;">
    <h1 style="color: white; font-size: 2rem; font-weight: 800; margin: 0 0 0.5rem 0;">Data Peminjaman</h1>
    <p style="color: rgba(255,255,255,0.9); font-size: 1rem; margin:0;">Kelola dan monitoring peminjaman laboratorium</p>
  </div>

  <div style="display:flex; gap:10px; flex-wrap:wrap; align-items: center;">
    <button type="button" class="p-add-btn" onclick="openBookingModal()">
      <i class="fas fa-plus"></i>
      Tambah Peminjaman
    </button>
    <button type="button" class="p-export-btn" onclick="exportReport()">
      <i class="fas fa-download"></i>
      Export Laporan
    </button>
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