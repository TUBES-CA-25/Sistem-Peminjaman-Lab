<?php
// app/views/pages/admin/tahun_ajaran/modal.php
?>
<div class="modal fade" id="taModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Tambah Tahun Ajaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="taForm" action="<?= BASE_URL ?>/tahun_ajaran" method="POST">
                    <input type="hidden" name="action" id="formAction" value="create">
                    <input type="hidden" name="id" id="editId" value="">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tahun Ajaran <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control" placeholder="Contoh: 2025/2026"
                            required>
                    </div>

                    <!-- Hidden Status Default -->
                    <input type="hidden" name="status" value="Tidak Aktif">

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-outline-secondary fw-semibold"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary fw-semibold" id="submitBtn">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>