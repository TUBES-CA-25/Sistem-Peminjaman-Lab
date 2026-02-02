<?php
// app/views/pages/admin/jurusan/modal.php
?>
<div class="modal fade" id="jurusanModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Tambah Jurusan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="jurusanForm" action="<?= BASE_URL ?>/jurusan" method="POST">
                    <input type="hidden" name="action" id="formAction" value="create">
                    <input type="hidden" name="id" id="editId" value="">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Jurusan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_jurusan" id="nama_jurusan" class="form-control"
                            placeholder="Contoh: Teknik Informatika" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Singkatan <span class="text-danger">*</span></label>
                        <input type="text" name="singkatan" id="singkatan" class="form-control" placeholder="Contoh: TI"
                            required maxlength="20">
                    </div>

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
