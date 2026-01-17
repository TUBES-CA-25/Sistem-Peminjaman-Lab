<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- HEADER -->
            <div class="d-flex align-items-center justify-content-between px-4 py-3 bg-light border-bottom">
                <h5 class="modal-title fw-bold text-dark mb-0">
                    <i class="fas fa-info-circle me-2 text-primary"></i>
                    Detail Pengajuan
                </h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body p-0">
                <table class="table table-bordered mb-0 align-middle">
                    <tr>
                        <td width="30%" class="bg-light fw-bold">Nama Pemohon</td>
                        <td id="view_nama"></td>
                    </tr>
                    <tr>
                        <td class="bg-light fw-bold">Email</td>
                        <td id="view_email"></td>
                    </tr>
                    <tr>
                        <td class="bg-light fw-bold">No. Telepon</td>
                        <td id="view_telepon"></td>
                    </tr>
                    <tr>
                        <td class="bg-light fw-bold">Nama Kegiatan</td>
                        <td id="view_kegiatan"></td>
                    </tr>
                    <tr>
                        <td class="bg-light fw-bold">Jumlah Peserta</td>
                        <td id="view_peserta"></td>
                    </tr>
                    <tr>
                        <td class="bg-light fw-bold">Waktu Pelaksanaan</td>
                        <td>
                            <span class="badge bg-primary me-2">
                                Mulai: <span id="view_mulai"></span>
                            </span>
                            <span class="badge bg-danger">
                                Selesai: <span id="view_selesai"></span>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="bg-light fw-bold">Status</td>
                        <td id="view_status" class="fw-bold"></td>
                    </tr>

                    <tr id="row_view_alasan" style="display:none;">
                        <td class="bg-light fw-bold text-danger">Alasan Penolakan</td>
                        <td id="view_alasan" class="text-danger bg-danger-subtle"></td>
                    </tr>

                    <tr>
                        <td class="bg-light fw-bold">Proposal</td>
                        <td>
                            <a href="#"
                               id="view_proposal"
                               target="_blank"
                               class="btn btn-sm btn-danger w-100">
                                <i class="fas fa-file-pdf me-2"></i>
                                Download Proposal
                            </a>
                        </td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</div>



<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-head-admin bg-warning-subtle">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-edit me-2"></i>Proses Pengajuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="<?= BASE_URL ?>/pengajuan/updateAdmin" method="POST" class="modal-body p-4">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="mb-4">
                    <label class="form-label fw-bold small text-uppercase text-muted">Update Status</label>
                    <select name="status" id="edit_status_select" class="form-select border-warning fw-bold" onchange="toggleAlasanAdmin()">
                        <option value="Menunggu Konfirmasi">⏳ Menunggu Konfirmasi</option>
                        <option value="Menunggu Interview">📋 Menunggu Interview</option>
                        <option value="Disetujui">✅ Disetujui</option>
                        <option value="Ditolak">❌ Ditolak</option>
                    </select>
                </div>

                <div id="box_alasan_admin" style="display:none;" class="mb-4">
                    <label class="form-label fw-bold small text-danger">Alasan Penolakan</label>
                    <textarea name="alasan_penolakan" id="edit_alasan" rows="3" class="form-control border-danger" placeholder="Wajib diisi jika ditolak..."></textarea>
                </div>

                <hr class="border-secondary-subtle">

                <div class="alert alert-info small d-flex align-items-center gap-2">
                    <i class="fas fa-info-circle"></i>
                    <div>Anda dapat menyesuaikan jadwal jika diperlukan.</div>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label fw-bold small">Tanggal Mulai</label>
                        <input type="date" class="form-control" name="tgl_mulai" id="edit_mulai" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold small">Tanggal Selesai</label>
                        <input type="date" class="form-control" name="tgl_selesai" id="edit_selesai" required>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>