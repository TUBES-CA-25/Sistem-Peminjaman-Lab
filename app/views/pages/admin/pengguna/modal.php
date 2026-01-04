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
                <form id="uUserForm" action="dashboard.php?page=pengguna" method="POST" autocomplete="off">
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
                        <label class="form-label fw-bold">Posisi <span class="text-danger">*</span></label>
                        <select name="posisi" id="posisi" class="form-select" required>
                            <option value="">Pilih Posisi</option>
                            <option value="Dosen">Dosen</option>
                            <option value="Asisten">Asisten</option>
                        </select>
                        <div class="form-text">Hanya tersedia dua pilihan: Dosen atau Asisten</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Role <span class="text-danger">*</span></label>
                        <select name="role" id="role" class="form-select" required>
                            <option value="">Pilih Role</option>
                            <option value="internal">Internal</option>
                            <option value="eksternal">Eksternal</option>
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="username"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Password <span class="text-danger"
                                    id="passReq">*</span></label>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="••••••••">
                            <div class="form-text" id="passHelp">Min. 8 karakter (Wajib saat tambah)</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="status" id="status" value="aktif"
                                checked>
                            <label class="form-check-label fw-semibold" for="status">Status Aktif</label>
                        </div>
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