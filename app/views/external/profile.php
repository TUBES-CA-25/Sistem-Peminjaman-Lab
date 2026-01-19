<link rel="stylesheet" href="<?= BASE_URL; ?>/public/css/style.css">

<div class="container-fluid hero-bg">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold mb-1">Profil Saya</h2>
            <p class="opacity-75 mb-0">Kelola informasi akun dan keamanan Anda.</p>
        </div>
        <a href="<?= BASE_URL; ?>/external" class="btn btn-outline-light fw-bold px-4 py-2">
            <i class="bi bi-arrow-left me-2"></i> Kembali ke Dashboard
        </a>
    </div>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="dashboard-card">
                
                <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-person-fill fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Informasi Pribadi</h5>
                        <small class="text-muted">Update data diri Anda di sini.</small>
                    </div>
                </div>

                <form action="<?= BASE_URL; ?>/external/prosesUpdateProfile" method="POST">
                    
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

                    <hr class="my-4 text-muted opacity-25">

                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-shield-lock me-2"></i>Keamanan Akun</h6>
                        <div class="alert alert-light border small text-muted">
                            Kosongkan kolom password jika Anda tidak ingin mengubah password saat ini.
                        </div>
                        <label class="form-label fw-bold small text-secondary">PASSWORD BARU</label>
                        <input type="password" class="form-control" name="password_baru" placeholder="Masukkan password baru (Opsional)">
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary fw-bold py-2" style="background-color: #0d3b66;">
                            <i class="bi bi-check-circle-fill me-2"></i> Simpan Perubahan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>