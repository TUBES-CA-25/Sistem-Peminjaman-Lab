<!-- HERO SECTION -->
<div class="card border-0 shadow-sm mb-4 bg-gradient-primary-custom text-white overflow-hidden">
    <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h1 class="h2 fw-bold mb-1 text-white">Pengajuan Peminjaman</h1>
            <p class="mb-0 opacity-75">Kelola dan verifikasi pengajuan kegiatan dari pihak eksternal.</p>
        </div>
        <div>
            <a href="<?= BASE_URL; ?>/pengajuan/export" target="_blank"
                class="btn btn-outline-light fw-bold d-flex align-items-center gap-2 shadow-sm">
                <i class="fas fa-download"></i>
                Export Laporan
            </a>
        </div>
    </div>
</div>

<!-- Flash Message -->
<div class="mb-3">
    <?php Flasher::flash(); ?>
</div>

<?php include 'list.php'; ?>

<?php include 'modal.php'; ?>
<?php include 'script.php'; ?>
