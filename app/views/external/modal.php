<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header">
                <h5 class="modal-title fw-bold">Form Pengajuan Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <form action="<?= BASE_URL; ?>/external/prosesPinjam" method="POST" enctype="multipart/form-data">

                    <div class="alert alert-light border small text-muted mb-3">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        Data identitas diambil otomatis dari profil akun Anda.
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-secondary">Nama Lengkap</label>
                            <input type="text" class="form-control bg-light" name="nama"
                                value="<?= $data['user']['nama']; ?>" readonly required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-secondary">Email</label>
                            <input type="email" class="form-control bg-light" name="email"
                                value="<?= $data['user']['email']; ?>" readonly required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-secondary">No. Telepon / WA</label>
                            <input type="text" class="form-control bg-light" name="telepon"
                                value="<?= $data['user']['telepon']; ?>" readonly required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">Jumlah Peserta</label>
                            <input type="number" class="form-control" name="jumlah_peserta" placeholder="Contoh: 30"
                                min="0"
                                oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 1) this.value = this.value.replace(/^0+/, '0').replace(/^0([1-9])/, '$1');"
                                onkeydown="if(['e','E','+','-','.'].includes(event.key)) event.preventDefault();"
                                required>
                        </div>
                    </div>

                    <hr class="my-4 opacity-10">

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Nama Kegiatan</label>
                        <input type="text" class="form-control" name="nama_kegiatan"
                            placeholder="Contoh: Workshop Python Dasar" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">Tanggal Mulai</label>
                            <input type="date" class="form-control" name="tgl_mulai" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">Tanggal Selesai</label>
                            <input type="date" class="form-control" name="tgl_selesai" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Upload Proposal (PDF)</label>
                        <input type="file" class="form-control" name="proposal" accept=".pdf" required>
                        <div class="form-text small text-muted">Maksimal ukuran file 5MB. Format wajib PDF.</div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary fw-bold py-2" style="background-color: #0d3b66;">
                            <i class="bi bi-send-fill me-2"></i> Kirim Pengajuan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning bg-opacity-10">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="bi bi-pencil-square me-2"></i>Edit Pengajuan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="<?= BASE_URL; ?>/external/updatePinjam" method="POST">
                    <input type="hidden" name="id" id="edit_id">

                    <div class="alert alert-light border small text-muted mb-3">
                        <i class="bi bi-info-circle me-1"></i>
                        Anda hanya dapat mengubah detail kegiatan. Data pemohon dan proposal tidak dapat diubah.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase">Nama Kegiatan</label>
                        <input type="text" class="form-control" name="nama_kegiatan" id="edit_kegiatan" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase">Jumlah Peserta</label>
                        <input type="number" class="form-control" name="jumlah_peserta" id="edit_peserta" min="0"
                            oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 1) this.value = this.value.replace(/^0+/, '0').replace(/^0([1-9])/, '$1');"
                            onkeydown="if(['e','E','+','-','.'].includes(event.key)) event.preventDefault();" required>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase">Tanggal Mulai</label>
                            <input type="date" class="form-control" name="tgl_mulai" id="edit_mulai" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase">Tanggal Selesai</label>
                            <input type="date" class="form-control" name="tgl_selesai" id="edit_selesai" required>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning fw-bold py-2">
                            <i class="bi bi-check-circle-fill me-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DETAIL -->
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="d-flex align-items-center justify-content-between px-4 py-3 bg-light border-bottom">
                <h5 class="modal-title fw-bold text-dark mb-0">
                    <i class="bi bi-info-circle me-2 text-primary"></i>
                    Detail Pengajuan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <!-- Alert Status: Success -->
                <div id="view_success" class="detail-section" style="display: none;">
                    <div class="alert alert-success d-flex gap-3 align-items-start border-0 shadow-sm"
                        style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <i class="bi bi-patch-check-fill fs-4"></i>
                        <div>
                            <strong class="d-block mb-1">Pengajuan Diterima!</strong>
                            Selamat, pengajuan peminjaman laboratorium Anda telah disetujui. Silakan gunakan ruangan
                            sesuai dengan jadwal yang telah ditentukan.
                        </div>
                    </div>
                </div>

                <!-- Alert Status: Waiting -->
                <div id="view_wait" class="detail-section" style="display: none;">
                    <div class="alert alert-warning d-flex gap-3 align-items-start">
                        <i class="bi bi-hourglass-split fs-4"></i>
                        <div>
                            <strong>Pengajuan Anda sedang ditinjau oleh Admin.</strong><br>
                            Mohon menunggu, Admin akan memverifikasi berkas proposal Anda terlebih dahulu.
                        </div>
                    </div>
                </div>

                <!-- Alert Status: Interview -->
                <div id="view_interview" class="detail-section" style="display: none;">
                    <div class="alert alert-info d-flex gap-3 align-items-start border-0 shadow-sm"
                        style="background: rgba(56, 189, 248, 0.1); color: #0ea5e9;">
                        <i class="bi bi-person-workspace fs-4"></i>
                        <div>
                            <strong class="d-block mb-1">Tahap Interview</strong>
                            Berkas Anda telah diverifikasi oleh Laboran. Silakan melanjutkan ke tahap interview untuk
                            verifikasi data akhir.
                        </div>
                    </div>
                </div>

                <!-- Alert Status: Rejected -->
                <div id="view_reject" class="detail-section" style="display: none;">
                    <div class="alert alert-danger d-flex gap-3 align-items-start">
                        <i class="bi bi-x-circle-fill fs-4"></i>
                        <div>
                            <strong>Kami telah meninjau proposal Anda. Mohon maaf, pengajuan tersebut belum dapat kami
                                terima.</strong><br>
                            Alasan: <span id="text_alasan" class="fw-bold"></span>
                        </div>
                    </div>
                </div>

                <div class="bg-light p-4 rounded-3 border">
                    <div class="row mb-2 border-bottom pb-2">
                        <div class="col-12 col-sm-4 fw-bold text-secondary">Nama Lengkap</div>
                        <div class="col-12 col-sm-8 text-break" id="det_nama"></div>
                    </div>
                    <div class="row mb-2 border-bottom pb-2">
                        <div class="col-12 col-sm-4 fw-bold text-secondary">Kegiatan</div>
                        <div class="col-12 col-sm-8 text-break" id="det_kegiatan"></div>
                    </div>
                    <div class="row mb-2 border-bottom pb-2">
                        <div class="col-12 col-sm-4 fw-bold text-secondary">Email</div>
                        <div class="col-12 col-sm-8 text-break" id="det_email"></div>
                    </div>
                    <div class="row mb-2 border-bottom pb-2">
                        <div class="col-12 col-sm-4 fw-bold text-secondary">No. Telepon</div>
                        <div class="col-12 col-sm-8 text-break" id="det_telepon"></div>
                    </div>
                    <div class="row mb-2 border-bottom pb-2">
                        <div class="col-12 col-sm-4 fw-bold text-secondary">Jumlah Peserta</div>
                        <div class="col-12 col-sm-8 text-break" id="det_peserta"></div>
                    </div>
                    <div class="row mb-2 border-bottom pb-2">
                        <div class="col-12 col-sm-4 fw-bold text-secondary">Tanggal Mulai</div>
                        <div class="col-12 col-sm-8 text-break" id="det_mulai"></div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-4 fw-bold text-secondary">Tanggal Selesai</div>
                        <div class="col-12 col-sm-8 text-break" id="det_selesai"></div>
                    </div>

                    <div class="mt-4">
                        <a href="#" id="det_proposal" target="_blank"
                            class="btn btn-dark w-100 py-2 rounded-pill shadow-sm">
                            <i class="fas fa-file-pdf me-2"></i>
                            Download Proposal
                        </a>
                    </div>
                </div>

                <div id="warning_footer" class="detail-section alert alert-warning d-flex gap-3 align-items-start mt-4"
                    style="display: none;">
                    <i class="bi bi-exclamation-triangle-fill fs-3"></i>
                    <div>
                        <strong>Perhatian Penting</strong><br>
                        Harap tiba 15 menit sebelum jadwal. Bawa kartu identitas & Hard File Proposal.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL RESTRICTION (Blokir Edit/Hapus) -->
<div class="modal fade" id="modalRestriction" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <div class="bg-warning bg-opacity-10 text-warning d-inline-flex align-items-center justify-content-center rounded-circle"
                        style="width: 80px; height: 80px;">
                        <i class="bi bi-lock-fill fs-1"></i>
                    </div>
                </div>

                <h4 class="fw-bold text-dark mb-2">Akses Dibatasi</h4>

                <p id="restrictionMsg" class="text-secondary mb-4">
                    Status pengajuan ini sudah dikunci.
                </p>

                <button type="button" class="btn btn-warning fw-bold w-100 rounded-pill py-2" data-bs-dismiss="modal">
                    Mengerti
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DELETE CONFIRMATION -->
<div class="modal fade" id="modalDeleteConfirm" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <div class="bg-danger bg-opacity-10 text-danger d-inline-flex align-items-center justify-content-center rounded-circle"
                        style="width: 80px; height: 80px;">
                        <i class="bi bi-trash3-fill fs-1"></i>
                    </div>
                </div>

                <h4 class="fw-bold text-dark mb-2">Hapus Pengajuan?</h4>
                <p class="text-secondary mb-4">
                    Apakah Anda yakin ingin membatalkan pengajuan ini?<br>
                    Data akan dihapus secara <strong>permanen</strong>.
                </p>

                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <a href="#" id="btnConfirmDelete" class="btn btn-danger rounded-pill px-4 fw-bold">
                        Ya, Hapus
                    </a>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>`
