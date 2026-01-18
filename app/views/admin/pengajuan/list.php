<?php if (empty($data['pengajuan'])): ?>
    <div class="text-center py-5 text-muted">
        <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
        <p class="fw-bold">Belum ada data pengajuan baru.</p>
    </div>
<?php else: ?>

    <div class="table-responsive">
        <table class="admin-table w-100">
            <thead>
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="20%">Pemohon</th>
                    <th width="25%">Kegiatan</th>
                    <th width="20%">Waktu Pelaksanaan</th>
                    <th width="10%" class="text-center">Proposal</th>
                    <th width="10%" class="text-center">Status</th>
                    <th width="10%" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($data['pengajuan'] as $row): ?>
                    <?php
                    // PERBAIKAN: Encode JSON agar aman dimasukkan ke HTML Attribute
                    $dataJSON = htmlspecialchars(json_encode([
                        'id' => $row['id'],
                        'nama' => $row['nama_lengkap'],
                        'email' => $row['email'],
                        'telepon' => $row['telepon'],
                        'kegiatan' => $row['nama_kegiatan'],
                        'peserta' => $row['jumlah_peserta'],
                        'mulai_fmt' => date('d M Y', strtotime($row['tgl_mulai'])),
                        'selesai_fmt' => date('d M Y', strtotime($row['tgl_selesai'])),
                        'raw_mulai' => $row['tgl_mulai'], // Format YYYY-MM-DD untuk input date
                        'raw_selesai' => $row['tgl_selesai'], // Format YYYY-MM-DD untuk input date

                        'proposal' => BASE_URL . '/' . $row['file_proposal'],
                        'status' => $row['status'],
                        'alasan' => $row['alasan_penolakan'] ?? ''
                    ]), ENT_QUOTES, 'UTF-8');
                    ?>
                    <tr>
                        <td class="text-center fw-bold text-secondary"><?= $no++; ?></td>

                        <td>
                            <div class="fw-bold text-dark"><?= $row['nama_lengkap']; ?></div>
                            <div class="small text-muted"><i class="fas fa-envelope me-1"></i> <?= $row['email']; ?></div>
                            <div class="small text-primary fw-bold"><?= $row['telepon']; ?></div>
                        </td>

                        <td>
                            <div class="fw-bold text-dark"><?= $row['nama_kegiatan']; ?></div>
                            <span class="badge bg-light text-dark border mt-1">
                                <i class="fas fa-users me-1"></i> <?= $row['jumlah_peserta']; ?> Peserta
                            </span>
                        </td>

                        <td>
                            <div class="small">
                                <div class="mb-1"><span class="text-primary fw-bold">Mulai:</span>
                                    <?= date('d/m/Y', strtotime($row['tgl_mulai'])); ?></div>
                                <div><span class="text-danger fw-bold">Selesai:</span>
                                    <?= date('d/m/Y', strtotime($row['tgl_selesai'])); ?></div>
                            </div>
                        </td>

                        <td class="text-center">
                            <?php if (!empty($row['file_proposal'])): ?>
                                <a href="<?= BASE_URL; ?>/<?= $row['file_proposal']; ?>" target="_blank"
                                    class="btn btn-sm btn-outline-danger shadow-sm py-1" title="Lihat / Download Proposal">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                            <?php else: ?>
                                <span class="text-muted small">-</span>
                            <?php endif; ?>
                        </td>

                        <td class="text-center">
                            <?php
                            $statusClass = 'bs-warning';
                            if ($row['status'] == 'Disetujui')
                                $statusClass = 'bs-success';
                            elseif ($row['status'] == 'Ditolak')
                                $statusClass = 'bs-danger';
                            elseif ($row['status'] == 'Menunggu Interview')
                                $statusClass = 'bs-info';
                            ?>
                            <span class="badge-status <?= $statusClass; ?>">
                                <?= $row['status']; ?>
                            </span>
                        </td>

                        <td class="text-end">
                            <div class="d-flex justify-content-end">
                                <button class="btn-icon btn-view" data-item="<?= $dataJSON; ?>" onclick="openDetailModal(this)"
                                    title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <button class="btn-icon btn-edit" data-item="<?= $dataJSON; ?>" onclick="openEditModal(this)"
                                    title="Proses Status / Edit Waktu">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>