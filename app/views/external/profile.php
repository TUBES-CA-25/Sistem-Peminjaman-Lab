<link rel="stylesheet" href="<?= BASE_URL; ?>/public/css/external.css">

<?php include __DIR__ . '/../components/sidebar_user.php'; ?>

<div class="main-content-user">
    
    <div class="container-fluid px-4">
        <div class="hero-bg rounded-3 mt-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-white">
                    <h2 class="fw-bold mb-1">Profil Saya</h2>
                    <p class="opacity-75 mb-0">Kelola informasi akun dan keamanan Anda.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-4">
        <div class="row justify-content-center">
            <div class="col-lg-12"> 
                <div class="dashboard-card mt-4">
                    
                    <form action="<?= BASE_URL; ?>/external/prosesUpdateProfile" method="POST">
                        
                        <h5 class="fw-bold mb-4 text-dark border-bottom pb-2">Informasi Pribadi</h5>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold small text-secondary">NAMA LENGKAP</label>
                                <input type="text" class="form-control" name="nama" value="<?= $data['user']['nama_lengkap']; ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-secondary">ALAMAT EMAIL</label>
                                <input type="email" class="form-control" name="email" value="<?= $data['user']['email']; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-secondary">NOMOR TELEPON</label>
                                <input type="text" class="form-control" name="telepon" value="<?= $data['user']['telepon']; ?>" required>
                            </div>
                        </div>

                        <div class="mt-4 pt-2 border-top">
                            <h6 class="fw-bold text-dark mt-3 mb-2">Keamanan Akun</h6>
                            <div class="alert alert-light border small text-muted mb-2">
                                <i class="bi bi-info-circle me-1"></i> Biarkan kosong jika tidak ingin mengganti password.
                            </div>
                            <label class="form-label fw-bold small text-secondary">PASSWORD BARU</label>
                            <input type="password" class="form-control" name="password_baru" placeholder="Masukkan password baru (Opsional)">
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary fw-bold py-2" style="background-color: #0d3b66;">
                                <i class="bi bi-check-circle-fill me-2"></i> Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<?php include 'modal.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>