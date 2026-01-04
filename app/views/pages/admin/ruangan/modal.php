<?php
// app/views/pages/admin/ruangan/modal.php
?>
<div class="modal fade" id="labModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="modalTitle">Informasi Ruangan</h5>
                    <p class="text-muted small mb-0">Lengkapi informasi ruangan laboratorium</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 p-lg-5">
                <form id="labForm" action="dashboard.php?page=ruangan" method="POST">
                    <input type="hidden" name="action" id="formAction" value="create">
                    <input type="hidden" name="id" id="editIndex" value="">

                    <!-- Upload Area -->
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Foto Ruangan <span
                                class="text-danger">*</span></label>
                        <div id="uploadArea"
                            class="upload-area p-5 text-center rounded-3 position-relative cursor-pointer"
                            onclick="document.getElementById('labImageFile').click()">

                            <div id="imagePreview" style="display: none;">
                                <img id="previewImg" src="" alt="preview" class="img-fluid rounded mb-3"
                                    style="max-height: 250px;">
                                <p id="fileName" class="small text-muted mb-0"></p>
                            </div>

                            <div id="uploadPlaceholder">
                                <i class="fas fa-cloud-upload-alt display-4 text-secondary mb-3 opacity-25"></i>
                                <p class="fw-medium text-dark mb-1">Klik untuk upload atau drag & drop</p>
                                <p class="small text-muted mb-0">PNG, JPG, JPEG (Max. 5MB)</p>
                            </div>

                            <input type="file" id="labImageFile" accept="image/*" class="d-none"
                                onchange="handleFileSelect(event)">
                            <input type="hidden" name="gambar_base64" id="labImage">

                            <button type="button" id="changePhotoBtn"
                                class="btn btn-sm btn-dark position-absolute top-0 end-0 m-3" style="display:none;"
                                onclick="event.stopPropagation(); document.getElementById('labImageFile').click();">
                                <i class="fas fa-edit me-1"></i> Ubah
                            </button>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Nama Ruangan <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="nama_ruangan" id="labName" class="form-control"
                                placeholder="Laboratorium Startup" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Kapasitas <span class="text-danger">*</span></label>
                            <input type="number" name="kapasitas" id="labCapacity" class="form-control" placeholder="36"
                                required min="1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Lokasi <span class="text-danger">*</span></label>
                            <input type="text" name="lokasi" id="labLocation" class="form-control"
                                placeholder="Gedung F Lantai 3" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Koordinator Lab <span
                                    class="text-danger">*</span></label>
                            <select name="pic" id="labPIC" class="form-select" required
                                onchange="updateCoordinatorEmail(this)">
                                <option value="">Pilih Koordinator</option>
                                <?php foreach ($assistants as $ast): ?>
                                    <option value="<?= htmlspecialchars($ast['nama']) ?>"
                                        data-email="<?= htmlspecialchars($ast['email']) ?>">
                                        <?= htmlspecialchars($ast['nama']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small">Email Koordinator <span
                                class="text-danger">*</span></label>
                        <input type="email" name="email_pic" id="labEmail" class="form-control bg-light" readonly
                            placeholder="Otomatis terisi..." required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small">Fasilitas</label>
                        <textarea name="fasilitas" id="labFacilities" rows="3" class="form-control"
                            placeholder="Proyektor, AC, dll"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small">Deskripsi</label>
                        <textarea name="deskripsi" id="labDescription" rows="3" class="form-control"
                            placeholder="Deskripsi singkat..."></textarea>
                    </div>

                    <div class="mb-4 p-3 bg-light rounded border">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="status" id="labStatus" value="1"
                                checked>
                            <label class="form-check-label fw-bold ms-2" for="labStatus">Ruangan tersedia untuk
                                dipinjam</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <button type="button" class="btn btn-outline-secondary fw-bold px-4"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary fw-bold px-4" id="submitBtnText">Simpan
                            Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>