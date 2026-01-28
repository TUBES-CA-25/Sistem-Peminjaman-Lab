<link rel="stylesheet" href="<?= BASE_URL; ?>/public/css/external.css">

<?php include __DIR__ . '/../components/external_sidebar.php'; ?>

    <div class="container-fluid px-4">
        <div class="hero-bg rounded-3" style="margin-top: 0px; margin-bottom: 20px;">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="text-white">
                    <h2 class="fw-bold mb-1">Profil Saya</h2>
                    <p class="opacity-75 mb-0">Kelola informasi akun dan keamanan Anda di sini.</p>
                </div>
            </div>
        </div>
    </div>

        <div class="container-fluid px-4">
            
            <div class="row mt-3">
                <div class="col-12">
                    <?php Flasher::flash(); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="dashboard-card mt-3 p-4 bg-white rounded shadow-sm">
                        
                        <form action="<?= BASE_URL; ?>/external/prosesUpdateProfile" method="POST" id="formProfile">
                            
                            <div class="row">
                                <div class="col-md-4 text-center border-end">
                                    <div class="py-3">
                                        <div class="avatar-profile">
                                            <?= strtoupper(substr($data['user']['nama'], 0, 1)); ?>
                                        </div>
                                        <h5 class="fw-bold text-dark"><?= $data['user']['nama']; ?></h5>
                                        <p class="text-muted small mb-4"><?= $data['user']['email']; ?></p>
                                        
                                        <button type="button" id="btnEdit" class="btn btn-primary w-75 fw-bold mb-2">
                                            <i class="bi bi-pencil-square me-2"></i>Edit Profil
                                        </button>

                                        <div id="actionButtons" class="d-none justify-content-center gap-2">
                                            <button type="submit" class="btn btn-success fw-bold">
                                                <i class="bi bi-check-lg"></i> Simpan
                                            </button>
                                            <button type="button" id="btnCancel" class="btn btn-outline-danger fw-bold">
                                                Batal
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8 ps-md-5">
                                    
                                    <h6 class="fw-bold text-primary mb-3">DATA DIRI</h6>
                                    
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-secondary">NAMA LENGKAP</label>
                                        <input type="text" class="form-control" name="nama" id="inputNama" 
                                               value="<?= $data['user']['nama']; ?>" readonly required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-secondary">NOMOR TELEPON / WA</label>
                                        <input type="text" class="form-control" name="telepon" id="inputTelepon" 
                                               value="<?= $data['user']['telepon']; ?>" readonly required>
                                    </div>

                                    <hr class="my-4">

                                    <h6 class="fw-bold text-primary mb-3">LOGIN & KEAMANAN</h6>

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-secondary">ALAMAT EMAIL</label>
                                        <input type="email" class="form-control" name="email" id="inputEmail" 
                                               value="<?= $data['user']['email']; ?>" readonly required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-secondary">PASSWORD BARU</label>
                                        <input type="password" class="form-control" name="password_baru" id="inputPassword" 
                                               placeholder="******" readonly>
                                        <div class="form-text fst-italic">
                                            *Biarkan kosong jika tidak ingin mengubah password.
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" id="originalNama" value="<?= $data['user']['nama']; ?>">
                                    <input type="hidden" id="originalTelepon" value="<?= $data['user']['telepon']; ?>">
                                    <input type="hidden" id="originalEmail" value="<?= $data['user']['email']; ?>">

                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>


    </main>
</div>



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
            inputs.forEach(input => input.removeAttribute('readonly'));
            inputs[0].focus();
            
            btnEdit.classList.add('d-none');
            actionButtons.classList.remove('d-none');
            actionButtons.classList.add('d-flex');
        });

        // MODE BATAL
        btnCancel.addEventListener('click', function() {
            inputs.forEach(input => input.setAttribute('readonly', true));
            
            // Reset ke data asli
            inputs[0].value = originalData.nama;
            inputs[1].value = originalData.telepon;
            inputs[2].value = originalData.email;
            inputs[3].value = ''; // Password kosong

            actionButtons.classList.add('d-none');
            actionButtons.classList.remove('d-flex');
            btnEdit.classList.remove('d-none');
        });
    });
</script>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>
    .avatar-profile {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #0d6efd, #0a58ca);
        color: white;
        font-size: 2.5rem;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin: 0 auto 15px auto;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
    }
    /* Style input saat mode baca (readonly) */
    .form-control:read-only {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        color: #6c757d;
    }
    /* Style input saat mode edit */
    .form-control:not(:read-only) {
        background-color: #fff;
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
    }
</style>