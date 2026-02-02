<?php include __DIR__ . '/../components/external_sidebar.php'; ?>

<div class="container-fluid px-4 pt-4">
    <div class="card border-0 shadow-sm mb-4 bg-gradient-primary-custom text-white overflow-hidden">
        <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div style="position: relative; z-index: 1;">
                <h2 class="fw-bold mb-1 text-white">Dashboard Peminjaman</h2>
                <p class="mb-0 opacity-75">Pantau status pengajuan peminjaman ruangan Anda di sini.</p>
            </div>
            <button type="button" class="btn btn-light shadow-sm" style="position: relative; z-index: 1;"
                onclick="openAddModal()">
                <i class="bi bi-plus-circle-fill"></i>
                <span>Ajukan Baru</span>
            </button>
        </div>
    </div>
</div>

<div class="container-fluid px-4">
    <!-- Notification -->
    <?php Flasher::flash(); ?>

    <div class="row">
        <div class="col-12">
            <div class="dashboard-card mt-4">
                <?php include 'list.php'; ?>
            </div>
        </div>
    </div>
</div>

</main>
</div>

<?php include 'modal.php'; ?>
<?php include 'script.php'; ?>
