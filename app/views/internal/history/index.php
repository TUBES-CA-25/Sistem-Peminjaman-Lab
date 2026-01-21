<?php
// views/internal/history/index.php
// Halaman Data Peminjaman - History peminjaman user yang login
?>

<!-- HERO SECTION -->
<div style="background: linear-gradient(135deg, #122E4F 0%, #1F45AC 100%); padding: 2.5rem; border-radius: 12px; margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1); gap: 1.5rem; flex-wrap: wrap;">
  <div style="flex: 1; min-width: 250px;">
    <h1 style="color: white; font-size: 2rem; font-weight: 800; margin: 0 0 0.5rem 0;">Data Peminjaman Saya</h1>
    <p style="color: rgba(255,255,255,0.9); font-size: 1rem; margin:0;">Riwayat peminjaman laboratorium yang Anda ajukan</p>
  </div>
</div>

<!-- TABLE SECTION -->
<?php include 'list.php'; ?>

<!-- MODALS -->
<?php include 'modal.php'; ?>

<!-- JAVASCRIPT -->
<?php include 'script.php'; ?>
