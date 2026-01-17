<?php
// app/views/internal/booking/index.php
// Main entry point untuk internal booking page

// Include helper functions
include __DIR__ . '/helpers.php';
?>

<!-- Hero Section - Blue Gradient -->
<section class="hero-section-internal">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8 text-center text-md-start">
                <h1>Booking Laboratorium</h1>
                <p class="mb-0">Pilih laboratorium dan waktu yang tersedia</p>
            </div>
            <div class="col-md-4 text-center text-md-end mt-3 mt-md-0">
                <button onclick="openViewScheduleModal()" 
                        class="btn btn-outline-light px-4 py-2 fw-bold" 
                        style="font-size: 0.95rem; border-radius: 8px; border-width: 2px;">
                    <i class="bi bi-calendar3"></i> Lihat Jadwal
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Lab Cards List -->
<?php include __DIR__ . '/list.php'; ?>

<!-- Modals -->
<?php include __DIR__ . '/modal.php'; ?>

<!-- Scripts -->
<?php include __DIR__ . '/script.php'; ?>
