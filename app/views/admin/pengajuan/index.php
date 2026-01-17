<style>
    /* CSS Admin Specific */
    .hero-bg { background: linear-gradient(135deg, #122E4F 0%, #1F45AC 100%); color: white; padding: 2.5rem; border-radius: 12px; margin-bottom: 2rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .table-container { background: white; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 2rem; }
    
    /* Table & Badge */
    .admin-table th { background: #f8fafc; padding: 15px; font-size: 0.85rem; font-weight: 700; color: #64748b; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; }
    .admin-table td { padding: 15px; border-bottom: 1px solid #e2e8f0; vertical-align: middle; font-size: 0.9rem; }
    
    .badge-status { padding: 6px 12px; border-radius: 50px; font-weight: 700; font-size: 0.75rem; text-transform: uppercase; display: inline-block; }
    .bs-success { background: #d1e7dd; color: #0f5132; }
    .bs-warning { background: #fff3cd; color: #856404; }
    .bs-danger { background: #f8d7da; color: #842029; }
    .bs-info { background: #cff4fc; color: #055160; }

    /* Buttons */
    .btn-icon { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; border: none; cursor: pointer; transition: 0.2s; margin-left: 4px; }
    .btn-view { background: #e0f2fe; color: #0284c7; } .btn-view:hover { background: #0284c7; color: white; }
    .btn-edit { background: #fef3c7; color: #d97706; } .btn-edit:hover { background: #d97706; color: white; }
    
    /* Modal Header */
    .modal-head-admin { background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 20px; display: flex; justify-content: space-between; align-items: center; }
</style>

<div class="container-fluid">
    <div class="hero-bg d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div style="flex: 1; min-width: 250px;">
            <h1 style="font-size: 2rem; font-weight: 800; margin: 0 0 0.5rem 0;">Pengajuan Peminjaman</h1>
            <p style="opacity: 0.9; font-size: 1rem; margin:0;">Kelola dan verifikasi pengajuan kegiatan dari pihak eksternal</p>
        </div>
        <div>
            <a href="<?= BASE_URL; ?>/pengajuan/export" target="_blank" class="btn btn-success fw-bold shadow-sm text-white">
                <i class="fas fa-file-excel me-2"></i> Export ke Excel
            </a>
        </div>
    </div>

    <div class="table-container">
        <?php include 'list.php'; ?>
    </div>
</div>

<?php include 'modal.php'; ?>
<?php include 'script.php'; ?>