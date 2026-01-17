<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered"> 
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Form Pengajuan Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="<?= BASE_URL; ?>/external/prosesPinjam" method="POST" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-md-6"><label class="form-label fw-bold small">Nama Lengkap</label><input type="text" class="form-control" name="nama" required></div>
                        <div class="col-md-6"><label class="form-label fw-bold small">Email</label><input type="email" class="form-control" name="email" required></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6"><label class="form-label fw-bold small">No. Telepon</label><input type="text" class="form-control" name="telepon" required></div>
                        <div class="col-md-6"><label class="form-label fw-bold small">Jumlah Peserta</label><input type="number" class="form-control" name="jumlah_peserta" required></div>
                    </div>
                    <div class="mb-3"><label class="form-label fw-bold small">Nama Kegiatan</label><input type="text" class="form-control" name="nama_kegiatan" required></div>
                    <div class="row mb-3">
                        <div class="col-md-6"><label class="form-label fw-bold small">Tanggal Mulai</label><input type="date" class="form-control" name="tgl_mulai" required></div>
                        <div class="col-md-6"><label class="form-label fw-bold small">Tanggal Selesai</label><input type="date" class="form-control" name="tgl_selesai" required></div>
                    </div>
                    <div class="mb-4"><label class="form-label fw-bold small">Upload Proposal (PDF)</label><input type="file" class="form-control" name="proposal" accept=".pdf,.doc,.docx" required></div>
                    <div class="d-grid"><button type="submit" class="btn btn-primary fw-bold" style="background-color: #0d3b66;">Kirim Pengajuan</button></div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered"> 
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning-subtle">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-pencil-square me-2"></i>Edit Pengajuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="<?= BASE_URL; ?>/external/updatePinjam" method="POST">
                    <input type="hidden" name="id" id="edit_id">
                    
                    <div class="alert alert-light border small text-muted mb-3">
                        <i class="bi bi-info-circle me-1"></i> Anda hanya dapat mengubah detail kegiatan. Data pemohon dan proposal tidak dapat diubah.
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase">Nama Kegiatan</label>
                        <input type="text" class="form-control" name="nama_kegiatan" id="edit_kegiatan" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase">Jumlah Peserta</label>
                        <input type="number" class="form-control" name="jumlah_peserta" id="edit_peserta" required>
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
                        <button type="submit" class="btn btn-warning fw-bold">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <div class="d-flex align-items-center justify-content-between px-4 py-3 bg-light border-bottom">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-info-circle me-2 text-primary"></i>
                    Detail Pengajuan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-5">

                <div id="view_success" class="detail-section">
                    <div class="alert-box-success">
                        <i class="bi bi-info-circle-fill fs-4"></i>
                        <div>
                            <strong>Selamat! Peminjaman ruangan Anda telah disetujui.</strong><br>
                            Jadwal telah dibuatkan. Silakan datang 15 menit sebelum kegiatan.
                        </div>
                    </div>
                </div>

                <div id="view_wait" class="detail-section">
                    <span class="badge-status-wait mb-3 d-inline-block">
                        <i class="bi bi-hourglass-split me-1"></i> MENUNGGU KONFIRMASI
                    </span>
                    <div class="alert-box-wait">
                        <i class="bi bi-exclamation-circle-fill fs-4"></i>
                        <div>
                            <strong>Pengajuan sedang diperiksa Admin.</strong><br>
                            Admin akan mengecek berkas proposal Anda terlebih dahulu.
                        </div>
                    </div>
                </div>

                <div id="view_interview" class="detail-section">
                    <div class="alert-box-interview d-flex gap-3">
                        <i class="bi bi-people-fill fs-4"></i>
                        <div>
                            <strong>Lolos Berkas! Silakan Lanjut Interview.</strong><br>
                            Harap temui Kepala Lab di Ruang Admin untuk verifikasi akhir.
                        </div>
                    </div>
                </div>

                <div id="view_reject" class="detail-section">
                    <div class="alert-box-reject">
                        <i class="bi bi-x-circle-fill fs-4"></i>
                        <div>
                            <strong>Mohon Maaf, pengajuan Anda ditolak.</strong><br>
                            Alasan: <span id="text_alasan" class="fw-bold"></span>
                        </div>
                    </div>
                </div>

                <h5 class="fw-bold mb-3 mt-4">Detail Pengajuan</h5>
                <div class="detail-list bg-light p-4 rounded-3">
                    <div class="row mb-2"><div class="col-4 label">Nama Lengkap</div><div class="col-8 value" id="det_nama"></div></div>
                    <div class="row mb-2"><div class="col-4 label">Kegiatan</div><div class="col-8 value" id="det_kegiatan"></div></div>
                    <div class="row mb-2"><div class="col-4 label">Email</div><div class="col-8 value" id="det_email"></div></div>
                    <div class="row mb-2"><div class="col-4 label">No. Telepon</div><div class="col-8 value" id="det_telepon"></div></div>
                    <div class="row mb-2"><div class="col-4 label">Jumlah Peserta</div><div class="col-8 value" id="det_peserta"></div></div>
                    <div class="row mb-2"><div class="col-4 label">Tanggal Mulai</div><div class="col-8 value" id="det_mulai"></div></div>
                    <div class="row"><div class="col-4 label">Tanggal Selesai</div><div class="col-8 value" id="det_selesai"></div></div>

                    <div class="mt-4">
                        <label class="label mb-2 d-block">Proposal Kegiatan</label>
                        <a href="#"
                           id="det_proposal"
                           target="_blank"
                           class="btn btn-dark w-100 py-2 rounded-pill">
                            <i class="bi bi-file-earmark-pdf me-2"></i>
                            Download Proposal
                        </a>
                    </div>
                </div>

                <div id="warning_footer" class="warning-footer detail-section mt-4">
                    <i class="bi bi-exclamation-triangle-fill fs-3 text-warning"></i>
                    <div>
                        <strong>Perhatian Penting</strong><br>
                        Harap tiba 30 menit sebelum jadwal. Bawa kartu identitas & surat resmi.
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRestriction" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-body p-4 text-center">
                
                <div class="mb-3">
                    <div class="bg-warning bg-opacity-10 text-warning d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px;">
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

<div class="modal fade" id="modalDeleteConfirm" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-body p-4 text-center">
                
                <div class="mb-3">
                    <div class="bg-danger bg-opacity-10 text-danger d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px;">
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