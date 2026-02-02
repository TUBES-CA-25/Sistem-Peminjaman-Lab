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
                <form id="uUserForm" action="<?= BASE_URL ?>/user" method="POST" autocomplete="off">
                    <input type="hidden" name="action" id="formAction" value="create">
                    <input type="hidden" name="id" id="editId" value="">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control"
                            placeholder="email@domain.ac.id" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nomor HP <span class="text-danger" id="telReq"
                                style="display:none;">*</span></label>
                        <input type="text" name="telepon" id="telepon" class="form-control" placeholder="08xxxxxxxxxx">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Role <span class="text-danger">*</span></label>
                        <select name="role" id="role" class="form-select" required>
                            <option value="">Pilih Role</option>
                            <option value="internal">Internal (Dosen/Asisten)</option>
                            <option value="external">External (Umum/Mahasiswa Luar)</option>
                        </select>
                    </div>

                    <div class="mb-3" id="statusGroup">
                        <label class="form-label fw-bold">Status (Posisi) <span class="text-danger">*</span></label>
                        <select name="posisi" id="posisi" class="form-select">
                            <option value="">Pilih Status</option>
                            <option value="Dosen">Dosen</option>
                            <option value="Asisten">Asisten</option>
                        </select>
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

<!-- Modal Detail User -->
<div class="modal fade" id="detailUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Detail Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="mb-4">
                    <img id="dFoto" src="<?= BASE_URL ?>/public/storage/images/default-profile.svg" alt="Foto Profil"
                        class="rounded-circle shadow-sm"
                        style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #f8f9fa;">
                </div>

                <h4 class="fw-bold mb-1" id="dNama">-</h4>
                <p class="text-muted mb-3" id="dEmail">-</p>

                <div class="row g-2 justify-content-center mb-3">
                    <div class="col-auto">
                        <span class="badge rounded-pill bg-light text-dark border px-3 py-2" id="dRole">Role</span>
                    </div>
                    <div class="col-auto">
                        <span class="badge rounded-pill bg-light text-dark border px-3 py-2" id="dStatus">Status</span>
                    </div>
                </div>

                <div class="bg-light rounded p-3 text-start">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-phone-alt text-secondary me-3" style="width: 20px;"></i>
                        <span id="dTelepon">-</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-calendar text-secondary me-3" style="width: 20px;"></i>
                        <span class="text-muted small">Terdaftar sejak: <span id="dJoined">-</span></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-secondary w-100 fw-semibold" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>