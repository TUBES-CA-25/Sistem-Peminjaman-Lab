<?php
// app/views/pages/admin/pengguna/modal.php
?>
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Tambah Pengguna Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="uUserForm" action="<?= BASE_URL ?>pengguna" method="POST" autocomplete="off">
                    <input type="hidden" name="action" id="formAction" value="create">
                    <input type="hidden" name="id" id="editId" value="">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control"
                            placeholder="Contoh: Dr. Ahmad Rahman, M.Kom" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control"
                            placeholder="email@domain.ac.id" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Status (Posisi) <span class="text-danger">*</span></label>
                        <select name="posisi" id="posisi" class="form-select" required>
                            <option value="">Pilih Status</option>
                            <option value="Dosen">Dosen</option>
                            <option value="Asisten">Asisten</option>
                        </select>
                        <div class="form-text">Role otomatis diset sebagai Internal</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Password <span class="text-danger"
                                id="passReq">*</span></label>
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="••••••••">
                        <div class="form-text" id="passHelp">Min. 8 karakter (Wajib saat tambah)</div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary fw-semibold"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary fw-semibold" id="submitBtn">Simpan
                            Pengguna</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>