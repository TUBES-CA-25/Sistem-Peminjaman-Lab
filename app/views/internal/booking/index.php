<?php
// app/views/internal/booking/index.php
// Main entry point untuk internal booking page

// Include helper functions
include __DIR__ . '/helpers.php';
?>

<!-- Hero Section - Blue Gradient -->
<section class="hero-section-internal">
    <div class="container text-center">
        <h1>Booking Laboratorium</h1>
        <p class="mb-0">Pilih laboratorium dan waktu yang tersedia</p>
    </div>
</section>

<!-- Lab Cards List -->
<?php include __DIR__ . '/list.php'; ?>

<!-- Modals -->
<?php include __DIR__ . '/modal.php'; ?>

<!-- Scripts -->
<?php include __DIR__ . '/script.php'; ?>
