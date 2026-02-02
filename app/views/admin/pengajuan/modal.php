<!-- MODAL DETAIL -->
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- Header -->
            <div class="modal-body p-0">
                <div class="d-flex align-items-center justify-content-between px-4 py-3 border-bottom-0">
                    <h5 class="modal-title fw-bold text-dark mb-0">
                        <i class="fas fa-info-circle me-2 text-primary"></i>
                        Detail Pengajuan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
            </div>

            <!-- Body -->
            <div class="modal-body p-4">

                <div id="status_alert_container" class="mb-4" style="display:none;">
                    <div class="alert alert-info d-flex align-items-center gap-3">
                        <i class="bi bi-info-circle-fill fs-4"></i>
                        <div>Status Saat Ini: <strong id="text_status_header"></strong></div>
                    </div>
                </div>

                <h6 class="fw-bold mb-3 text-secondary">
                    <i class="fas fa-list-ul me-2"></i>Informasi Peminjam
                </h6>

                <div class="p-4 rounded-4 border-0">
                    <div class="row mb-3 border-bottom border-secondary-subtle pb-2 info-row">
                        <div class="col-4 fw-bold text-secondary">Nama Pemohon</div>
                        <div class="col-8 fw-bold text-dark" id="view_nama"></div>
                    </div>

                    <div class="row mb-3 border-bottom border-secondary-subtle pb-2 info-row">
                        <div class="col-4 fw-bold text-secondary">Email</div>
                        <div class="col-8 text-dark" id="view_email"></div>
                    </div>

                    <div class="row mb-3 border-bottom border-secondary-subtle pb-2 info-row">
                        <div class="col-4 fw-bold text-secondary">No. Telepon</div>
                        <div class="col-8 text-dark font-monospace" id="view_telepon"></div>
                    </div>

                    <div class="row mb-3 border-bottom border-secondary-subtle pb-2 info-row">
                        <div class="col-4 fw-bold text-secondary">Nama Kegiatan</div>
                        <div class="col-8 fw-bold text-primary" id="view_kegiatan"></div>
                    </div>

                    <div class="row mb-3 border-bottom border-secondary-subtle pb-2 info-row">
                        <div class="col-4 fw-bold text-secondary">Jumlah Peserta</div>
                        <div class="col-8 text-dark" id="view_peserta"></div>
                    </div>

                    <div class="row mb-3 border-bottom border-secondary-subtle pb-2 info-row">
                        <div class="col-4 fw-bold text-secondary">Waktu Pelaksanaan</div>
                        <div class="col-8">
                            <span class="badge bg-primary me-2">
                                Mulai: <span id="view_mulai"></span>
                            </span>
                            <span class="badge bg-danger">
                                Selesai: <span id="view_selesai"></span>
                            </span>
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-4 fw-bold text-secondary">Status Terkini</div>
                        <div class="col-8 fw-bold" id="view_status"></div>
                    </div>

                    <div id="row_view_alasan" class="alert alert-danger mt-3" style="display:none;">
                        <strong class="d-block mb-1">Alasan Penolakan:</strong>
                        <span id="view_alasan"></span>
                    </div>

                    <div class="mt-4">
                        <a href="#" id="view_proposal" target="_blank"
                            class="btn btn-dark w-100 py-2 rounded-pill shadow-sm">
                            <i class="fas fa-file-pdf me-2"></i> Download Proposal
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<!-- MODAL EDIT -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <!-- Header -->
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-pencil-square me-2"></i>Proses Pengajuan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Body -->
            <div class="modal-body p-4">
                <form action="<?= BASE_URL ?>/pengajuan/updateAdmin" method="POST">

                    <input type="hidden" name="id" id="edit_id">


                    <!-- Update Status -->
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase text-dark mb-2">
                            <i class="bi bi-patch-check-fill text-primary me-1"></i> UPDATE STATUS
                        </label>

                        <select name="status" id="edit_status_select" class="form-select shadow-sm fw-bold text-dark"
                            onchange="toggleAlasanAdmin()" style="cursor: pointer;">
                            <option value="Menunggu Konfirmasi">Menunggu Konfirmasi</option>
                            <option value="Menunggu Interview">Menunggu Interview</option>
                            <option value="Disetujui">Disetujui</option>
                            <option value="Ditolak">Ditolak</option>
                        </select>

                        <div class="form-text small text-muted mt-2 ps-1">
                            <i class="bi bi-info-circle me-1"></i> Pilih status terbaru untuk memproses pengajuan ini.
                        </div>
                    </div>

                    <!-- Alasan Penolakan -->
                    <div id="box_alasan_admin" class="mb-4" style="display:none;">
                        <label class="form-label fw-bold small text-danger">
                            Alasan Penolakan
                        </label>
                        <textarea name="alasan_penolakan" id="edit_alasan" rows="3" class="form-control border-danger"
                            placeholder="Wajib diisi jika pengajuan ditolak..."></textarea>
                    </div>

                    <hr class="border-secondary-subtle mb-4">

                    <!-- Jadwal -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase">
                                Tanggal Mulai
                            </label>
                            <input type="date" class="form-control" name="tgl_mulai" id="edit_mulai" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase">
                                Tanggal Selesai
                            </label>
                            <input type="date" class="form-control" name="tgl_selesai" id="edit_selesai" required>
                        </div>
                    </div>

                    <!-- Action -->
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary fw-bold px-4">
                            <i class="bi bi-check-circle-fill me-1"></i>
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
