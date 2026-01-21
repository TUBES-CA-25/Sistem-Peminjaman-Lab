<?php
// app/views/internal/booking/index.php
// Main entry point untuk internal booking page

// Include helper functions
include __DIR__ . '/helpers.php';
?>

<!-- Hero Section - Blue Gradient -->
<div style="background: linear-gradient(135deg, #122E4F 0%, #1F45AC 100%); padding: 2.5rem; border-radius: 12px; margin-bottom: 0.5rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1); gap: 1.5rem; flex-wrap: wrap;">

  <div style="flex: 1; min-width: 250px;">
    <h1 style="color: white; font-size: 2rem; font-weight: 800; margin: 0 0 0.5rem 0;">Booking Laboratorium</h1>
    <p style="color: rgba(255,255,255,0.9); font-size: 1rem; margin:0;">Pilih laboratorium dan waktu yang tersedia</p>
  </div>
</div>



<!-- Lab Cards List -->
<?php include __DIR__ . '/list.php'; ?>

<!-- Modals -->
<?php include __DIR__ . '/modal.php'; ?>

<!-- Scripts -->
<?php include __DIR__ . '/script.php'; ?>
