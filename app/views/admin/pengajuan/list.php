<?php if (empty($data['pengajuan'])): ?>
    <div class="text-center py-5 text-muted bg-white rounded shadow-sm">
        <div class="mb-3">
            <i class="fas fa-inbox fa-3x opacity-25"></i>
        </div>
        <h6 class="fw-bold">Belum ada data pengajuan baru.</h6>
        <p class="small">Data pengajuan akan muncul di sini.</p>
    </div>
<?php else: ?>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover table-borderless align-middle mb-0" style="min-width: 800px;">
                <thead class="bg-light border-bottom">
                    <tr>
                        <th width="5%" class="text-center py-3 text-secondary text-uppercase small fw-bold">No</th>
                        <th width="20%" class="py-3 text-secondary text-uppercase small fw-bold ps-4">Pemohon</th>
                        <th width="25%" class="py-3 text-secondary text-uppercase small fw-bold">Kegiatan</th>
                        <th width="20%" class="py-3 text-secondary text-uppercase small fw-bold">Waktu</th>
                        <th width="10%" class="text-center py-3 text-secondary text-uppercase small fw-bold">File</th>
                        <th width="10%" class="text-center py-3 text-secondary text-uppercase small fw-bold">Status</th>
                        <th width="10%" class="text-end py-3 text-secondary text-uppercase small fw-bold pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($data['pengajuan'] as $row): ?>
                        <?php
                        // Set path relative to project root
                        $pathProposal = BASE_URL . '/public/uploads/' . $row['file_proposal'];
                        
                        // JSON Data for Modal
                        $dataJSON = htmlspecialchars(json_encode([
                            'id' => $row['id'],
                            'nama' => $row['nama_lengkap'],
                            'email' => $row['email'],
                            'telepon' => $row['telepon'],
                            'kegiatan' => $row['nama_kegiatan'],
                            'peserta' => $row['jumlah_peserta'],
                            'mulai_fmt' => date('d M Y', strtotime($row['tgl_mulai'])),
                            'selesai_fmt' => date('d M Y', strtotime($row['tgl_selesai'])),
                            'raw_mulai' => $row['tgl_mulai'], 
                            'raw_selesai' => $row['tgl_selesai'], 
                            'proposal' => $pathProposal,
                            'status' => $row['status'],
                            'alasan' => $row['alasan_penolakan'] ?? ''
                        ]), ENT_QUOTES, 'UTF-8');
                        ?>
                        <tr class="border-bottom">
                            <td class="text-center fw-bold text-secondary"><?= $no++; ?></td>

                            <td class="ps-4">
                                <div class="fw-bold text-dark"><?= htmlspecialchars($row['nama_lengkap']); ?></div>
                                <div class="small text-muted"><i class="fas fa-envelope me-1"></i> <?= htmlspecialchars($row['email']); ?></div>
                                <div class="small text-primary fw-bold"><?= htmlspecialchars($row['telepon']); ?></div>
                            </td>

                            <td>
                                <div class="fw-bold text-dark"><?= htmlspecialchars($row['nama_kegiatan']); ?></div>
                                <span class="badge bg-light text-dark border mt-1">
                                    <i class="fas fa-users me-1"></i> <?= htmlspecialchars($row['jumlah_peserta']); ?> Peserta
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
                                    <a href="<?= $pathProposal; ?>" target="_blank"
                                        class="btn btn-sm btn-outline-danger shadow-sm py-1" title="Download Proposal">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">
                                <?php
                                $badgeClass = 'bs-warning'; 
                                if ($row['status'] == 'Disetujui') $badgeClass = 'bs-success';
                                elseif ($row['status'] == 'Ditolak') $badgeClass = 'bs-danger';
                                elseif ($row['status'] == 'Menunggu Interview') $badgeClass = 'bs-info';
                                ?>
                                <span class="badge-status <?= $badgeClass; ?>">
                                    <?= $row['status']; ?>
                                </span>
                            </td>

                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1">
                                    <button class="btn-icon btn-view" data-item="<?= $dataJSON; ?>" onclick="openDetailModal(this)" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-icon btn-edit" data-item="<?= $dataJSON; ?>" onclick="openEditModal(this)" title="Proses Status">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>