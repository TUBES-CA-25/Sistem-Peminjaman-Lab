<?php include __DIR__ . '/../components/external_sidebar.php'; ?>

<div class="container-fluid px-3 px-md-4 pt-2">
    <div class="card border-0 mb-5 bg-gradient-primary-custom text-white overflow-hidden rounded-4">
        <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-4">
            <div class="flex-grow-1">
                <h2 class="fw-bold mb-1 text-white">Pusat Pengajuan</h2>
                <p class="mb-0 opacity-75">Pantau status pengajuan peminjaman ruangan Anda di sini.</p>
            </div>
            <button type="button" class="btn btn-light shadow-sm px-4 py-2 fw-bold" onclick="openAddModal()">
                <i class="bi bi-plus-circle-fill me-2"></i>
                <span>Ajukan Baru</span>
            </button>
        </div>
    </div>
</div>

<div class="container-fluid px-3 px-md-4">
    <!-- Notification -->
    <?php Flasher::flash(); ?>

    <div class="row">
        <div class="col-12">
            <div class="dashboard-card">
                <?php include 'list.php'; ?>
            </div>
        </div>
    </div>
</div>

</main>
</div>

<?php include 'modal.php'; ?>
<?php include 'script.php'; ?>
