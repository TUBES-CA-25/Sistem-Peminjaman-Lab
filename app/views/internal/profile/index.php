<link rel="stylesheet" href="<?= BASE_URL; ?>/public/css/internal-profile.css?v=<?= time(); ?>">
<!-- Cropper.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">

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
                        <div class="avatar-large" id="profileAvatar">
                            <?php if (!empty($user['foto']) && file_exists('public/storage/uploads/profile/' . $user['foto'])): ?>
                                <img src="<?= BASE_URL; ?>/public/storage/uploads/profile/<?= $user['foto']; ?>" alt="Profile">
                            <?php else: ?>
                                <span><?= strtoupper(substr($user['nama'] ?? 'U', 0, 1)); ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Mini Edit Button for Avatar -->
                        <div class="avatar-edit-badge d-none" id="avatarEditBadge">
                            <label for="inputFoto" class="mb-0 cursor-pointer">
                                <i class="fas fa-camera"></i>
                            </label>
                            <input type="file" name="foto" id="inputFoto" class="d-none" accept="image/*">
                        </div>
                    </div>
                    <div class="profile-header-info">
                        <h3 class="profile-name"><?= $user['nama']; ?></h3>
                        <p class="profile-email"><i class="fas fa-envelope me-2"></i><?= $user['email']; ?></p>
                        <p class="profile-member-since"><i class="fas fa-calendar-alt me-2"></i>Member sejak <?= date('F Y', strtotime($user['created_at'] ?? 'now')); ?></p>
                    </div>
                </div>

                <!-- Form Section -->
                <form action="<?= BASE_URL; ?>/internal/prosesUpdateProfile" method="POST" id="formProfile" enctype="multipart/form-data">
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
                            <button type="submit" class="btn-modern btn-success-modern" id="btnSubmitProfile">
                                <i class="fas fa-check me-2 icon-default"></i>
                                <span class="spinner-border spinner-border-sm d-none me-2" role="status" aria-hidden="true"></span>
                                <span class="btn-text">Simpan Perubahan</span>
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
                    <input type="hidden" name="cropped_image" id="croppedImageInput">
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cropper -->
<div class="modal fade" id="cropperModal" tabindex="-1" aria-labelledby="cropperModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cropperModalLabel">Atur Foto Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <img id="imageToCrop" src="" alt="Picture" style="max-width: 100%;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnCrop">
                    <span class="spinner-border spinner-border-sm d-none me-2" role="status" aria-hidden="true"></span>
                    <span class="btn-text">Potong & Simpan</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Cropper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<!-- Custom Profile Logic -->
<?php include __DIR__ . '/script.php'; ?>
