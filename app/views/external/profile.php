<link rel="stylesheet" href="<?= BASE_URL; ?>/public/css/external.css">

<?php include __DIR__ . '/../components/external_sidebar.php'; ?>

<div class="container-fluid px-4" style="margin-top: 20px;">
    <!-- Flash Message -->
    <div class="row">
        <div class="col-12">
            <?php Flasher::flash(); ?>
        </div>
    </div>

    <!-- Profile Card with Cover -->
    <div class="row">
        <div class="col-12">
            <div class="profile-card-modern">
                <!-- Cover Gradient -->
                <div class="profile-cover">
                    <div class="cover-gradient">
                        <div class="profile-cover-content">
                            <div class="profile-cover-icon">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <div class="profile-cover-text">
                                <h1 class="profile-cover-title">Profil Saya</h1>
                                <p class="profile-cover-subtitle">Kelola informasi akun dan preferensi Anda</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Avatar Overlap -->
                <div class="profile-avatar-section">
                    <div class="avatar-wrapper">
                        <div class="avatar-large">
                            <?= strtoupper(substr($user['nama'], 0, 1)); ?>
                        </div>
                    </div>
                    <div class="profile-header-info">
                        <h3 class="profile-name"><?= $user['nama']; ?></h3>
                        <p class="profile-email"><i class="fas fa-envelope me-2"></i><?= $user['email']; ?></p>
                        <p class="profile-member-since"><i class="fas fa-calendar-alt me-2"></i>Member sejak <?= date('F Y', strtotime($user['created_at'] ?? 'now')); ?></p>
                    </div>
                </div>

                <!-- Form Section -->
                <form action="<?= BASE_URL; ?>/external/prosesUpdateProfile" method="POST" id="formProfile">
                    <div class="row g-4 mt-3">
                        <!-- Data Diri Card -->
                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-card-header">
                                    <i class="fas fa-user-edit"></i>
                                    <h5>Data Diri</h5>
                                </div>
                                <div class="info-card-body">
                                    <div class="form-group-modern mb-3">
                                        <label class="form-label-modern">
                                            <i class="fas fa-user"></i> Nama Lengkap
                                        </label>
                                        <input type="text" class="form-control-modern" name="nama" id="inputNama" 
                                               value="<?= $user['nama']; ?>" readonly required>
                                    </div>

                                    <div class="form-group-modern mb-3">
                                        <label class="form-label-modern">
                                            <i class="fas fa-phone"></i> Nomor Telepon / WA
                                        </label>
                                        <input type="text" class="form-control-modern" name="telepon" id="inputTelepon" 
                                               value="<?= $user['telepon']; ?>" readonly required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Login & Keamanan Card -->
                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-card-header">
                                    <i class="fas fa-shield-alt"></i>
                                    <h5>Login & Keamanan</h5>
                                </div>
                                <div class="info-card-body">
                                    <div class="form-group-modern mb-3">
                                        <label class="form-label-modern">
                                            <i class="fas fa-envelope"></i> Alamat Email
                                        </label>
                                        <input type="email" class="form-control-modern" name="email" id="inputEmail" 
                                               value="<?= $user['email']; ?>" readonly required>
                                    </div>

                                    <div class="form-group-modern mb-3">
                                        <label class="form-label-modern">
                                            <i class="fas fa-lock"></i> Password Baru
                                        </label>
                                        <input type="password" class="form-control-modern" name="password_baru" id="inputPassword" 
                                               placeholder="Biarkan kosong jika tidak ingin mengubah" readonly>
                                        <div class="form-hint">
                                            <i class="fas fa-info-circle"></i> Kosongkan jika tidak ingin mengubah password
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="profile-actions mt-4">
                        <button type="button" id="btnEdit" class="btn-modern btn-primary-modern">
                            <i class="fas fa-edit me-2"></i>Edit Profil
                        </button>
                        <div id="actionButtons" class="d-none">
                            <button type="submit" class="btn-modern btn-success-modern">
                                <i class="fas fa-check me-2"></i>Simpan Perubahan
                            </button>
                            <button type="button" id="btnCancel" class="btn-modern btn-cancel-modern">
                                <i class="fas fa-times me-2"></i>Batal
                            </button>
                        </div>
                    </div>

                    <!-- Hidden Fields -->
                    <input type="hidden" id="originalNama" value="<?= $user['nama']; ?>">
                    <input type="hidden" id="originalTelepon" value="<?= $user['telepon']; ?>">
                    <input type="hidden" id="originalEmail" value="<?= $user['email']; ?>">
                </form>
            </div>
        </div>
    </div>
</div>

</main>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const btnEdit = document.getElementById('btnEdit');
        const btnCancel = document.getElementById('btnCancel');
        const actionButtons = document.getElementById('actionButtons');
        
        const inputs = [
            document.getElementById('inputNama'),
            document.getElementById('inputTelepon'),
            document.getElementById('inputEmail'),
            document.getElementById('inputPassword')
        ];

        const originalData = {
            nama: document.getElementById('originalNama').value,
            telepon: document.getElementById('originalTelepon').value,
            email: document.getElementById('originalEmail').value
        };

        // MODE EDIT
        btnEdit.addEventListener('click', function() {
            inputs.forEach(input => {
                input.removeAttribute('readonly');
                input.classList.add('editing');
            });
            inputs[0].focus();
            
            btnEdit.classList.add('d-none');
            actionButtons.classList.remove('d-none');
            actionButtons.classList.add('d-flex', 'gap-2');
        });

        // MODE BATAL
        btnCancel.addEventListener('click', function() {
            inputs.forEach(input => {
                input.setAttribute('readonly', true);
                input.classList.remove('editing');
            });
            
            // Reset ke data asli
            inputs[0].value = originalData.nama;
            inputs[1].value = originalData.telepon;
            inputs[2].value = originalData.email;
            inputs[3].value = '';

            actionButtons.classList.add('d-none');
            actionButtons.classList.remove('d-flex');
            btnEdit.classList.remove('d-none');
        });
    });
</script>