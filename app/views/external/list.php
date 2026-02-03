<?php if (empty($data['riwayat'])): ?>
    <div class="p-empty-state">
        <i class="bi bi-inbox"></i>
        <h6 class="fw-bold text-main">Belum ada riwayat pengajuan.</h6>
        <p class="text-muted small">Klik tombol "Ajukan Baru" di atas untuk memulai.</p>
    </div>
<?php else: ?>
    <!-- DESKTOP VIEW -->
    <div class="d-none d-md-block">
        <div class="card border-0 rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-body-secondary border-bottom">
                        <tr>
                            <th width="5%" class="text-center py-3 text-muted text-uppercase small fw-bold">No</th>
                            <th width="30%" class="py-3 text-muted text-uppercase small fw-bold ps-4">Kegiatan</th>
                            <th width="25%" class="py-3 text-muted text-uppercase small fw-bold">Waktu</th>
                            <th width="10%" class="text-center py-3 text-muted text-uppercase small fw-bold">Proposal</th>
                            <th width="15%" class="text-center py-3 text-muted text-uppercase small fw-bold">Status</th>
                            <th width="15%" class="text-end py-3 text-muted text-uppercase small fw-bold pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($data['riwayat'] as $item): ?>
                            <?php
                            $pathProposal = BASE_URL . '/public/storage/uploads/proposals/' . $item['file_proposal'];
                            $dataLengkap = [
                                'id' => $item['id'],
                                'nama' => $item['nama_lengkap'],
                                'email' => $item['email'],
                                'telepon' => $item['telepon'],
                                'kegiatan' => $item['nama_kegiatan'],
                                'peserta' => $item['jumlah_peserta'],
                                'mulai' => date('d M Y', strtotime($item['tgl_mulai'])),
                                'selesai' => date('d M Y', strtotime($item['tgl_selesai'])),
                                'raw_mulai' => $item['tgl_mulai'],
                                'raw_selesai' => $item['tgl_selesai'],
                                'proposal' => $pathProposal,
                                'status' => $item['status'],
                                'alasan' => $item['alasan_penolakan'] ?? '-'
                            ];
                            $safeJson = htmlspecialchars(json_encode($dataLengkap), ENT_QUOTES, 'UTF-8');
                            ?>
                            <tr class="border-bottom border-light-subtle">
                                <td class="text-center fw-bold text-muted"><?= $no++; ?></td>
                                <td class="ps-4">
                                    <div class="fw-bold text-main mb-1"><?= $item['nama_kegiatan']; ?></div>
                                    <span class="badge bg-light-subtle text-muted border rounded-pill fw-normal">
                                        <i class="bi bi-people me-1"></i> <?= $item['jumlah_peserta']; ?> Peserta
                                    </span>
                                </td>
                                <td>
                                    <div class="small">
                                        <div class="mb-1 text-primary fw-bold">
                                            <i
                                                class="bi bi-calendar-check me-2"></i><?= date('d M Y', strtotime($item['tgl_mulai'])); ?>
                                        </div>
                                        <div class="text-danger fw-bold">
                                            <i
                                                class="bi bi-calendar-x me-2"></i><?= date('d M Y', strtotime($item['tgl_selesai'])); ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <?php if (!empty($item['file_proposal'])): ?>
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
                                    $statusSlug = 'status-waiting';
                                    $iconStatus = 'bi-hourglass-split';
                                    if ($item['status'] == 'Disetujui') {
                                        $statusSlug = 'status-approved';
                                        $iconStatus = 'bi-check-circle-fill';
                                    } elseif ($item['status'] == 'Ditolak') {
                                        $statusSlug = 'status-rejected';
                                        $iconStatus = 'bi-x-circle-fill';
                                    } elseif ($item['status'] == 'Menunggu Interview') {
                                        $statusSlug = 'status-interview';
                                        $iconStatus = 'bi-mic-fill';
                                    }
                                    ?>
                                    <span class="status-pill <?= $statusSlug; ?>">
                                        <i class="bi <?= $iconStatus; ?>"></i> <?= $item['status']; ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group gap-2 justify-content-end" role="group">
                                        <button class="btn-action btn-view" data-item="<?= $safeJson; ?>"
                                            onclick="openDetail(this)" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn-action btn-edit" data-item="<?= $safeJson; ?>"
                                            onclick="tryEdit(this)" title="Edit Data">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn-action btn-delete"
                                            onclick="tryDelete('<?= $item['id']; ?>', '<?= $item['status']; ?>')"
                                            title="Batalkan">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <<<<<<< HEAD <!-- MOBILE VIEW -->
        <div class="d-md-none p-mobile-list">
            <?php foreach ($data['riwayat'] as $item): ?>
                <?php
                $pathProposal = BASE_URL . '/public/storage/uploads/proposals/' . $item['file_proposal'];
                $dataLengkap = [
                    'id' => $item['id'],
                    'nama' => $item['nama_lengkap'],
                    'email' => $item['email'],
                    'telepon' => $item['telepon'],
                    'kegiatan' => $item['nama_kegiatan'],
                    'peserta' => $item['jumlah_peserta'],
                    'mulai' => date('d M Y', strtotime($item['tgl_mulai'])),
                    'selesai' => date('d M Y', strtotime($item['tgl_selesai'])),
                    'raw_mulai' => $item['tgl_mulai'],
                    'raw_selesai' => $item['tgl_selesai'],
                    'proposal' => $pathProposal,
                    'status' => $item['status'],
                    'alasan' => $item['alasan_penolakan'] ?? '-'
                ];
                $safeJson = htmlspecialchars(json_encode($dataLengkap), ENT_QUOTES, 'UTF-8');
                $statusSlug = 'status-waiting';
                $iconStatus = 'bi-hourglass-split';
                if ($item['status'] == 'Disetujui') {
                    $statusSlug = 'status-approved';
                    $iconStatus = 'bi-check-circle-fill';
                } elseif ($item['status'] == 'Ditolak') {
                    $statusSlug = 'status-rejected';
                    $iconStatus = 'bi-x-circle-fill';
                } elseif ($item['status'] == 'Menunggu Interview') {
                    $statusSlug = 'status-interview';
                    $iconStatus = 'bi-mic-fill';
                }
                ?>
                <div class="p-mobile-card shadow-sm">
                    <div class="p-mobile-title"><?= $item['nama_kegiatan']; ?></div>

                    <span class="status-pill <?= $statusSlug; ?> mb-3">
                        <i class="bi <?= $iconStatus; ?> me-1"></i> <?= $item['status']; ?>
                    </span>

                    <div class="p-mobile-info-stack">
                        <div class="p-mobile-info-item">
                            <span class="p-mobile-info-label">Peserta</span>
                            <span class="p-mobile-info-value"><i class="bi bi-people"></i>
                                <?= $item['jumlah_peserta']; ?></span>
                        </div>

                        <div class="p-mobile-info-item">
                            <span class="p-mobile-info-label">Proposal</span>
                            <span class="p-mobile-info-value">
                                <?php if (!empty($item['file_proposal'])): ?>
                                    <a href="<?= $pathProposal; ?>" target="_blank"
                                        class="text-danger text-decoration-none d-inline-flex align-items-center gap-2">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted"><i class="bi bi-file-earmark-x"></i> -</span>
                                <?php endif; ?>
                            </span>
                        </div>

                        <div class="p-mobile-info-item">
                            <span class="p-mobile-info-label text-blue">Mulai</span>
                            <span class="p-mobile-info-value"><i class="bi bi-calendar-check"></i>
                                <?= date('d M Y', strtotime($item['tgl_mulai'])); ?></span>
                        </div>

                        <div class="p-mobile-info-item">
                            <span class="p-mobile-info-label text-red">Selesai</span>
                            <span class="p-mobile-info-value"><i class="bi bi-calendar-x"></i>
                                <?= date('d M Y', strtotime($item['tgl_selesai'])); ?></span>
                        </div>
                    </div>

                    <div class="p-mobile-card-footer">
                        <div class="p-mobile-actions">
                            <button class="btn-mobile-outline btn-detail-outline" data-item="<?= $safeJson; ?>"
                                onclick="openDetail(this)">
                                <i class="bi bi-eye"></i> Detail
                            </button>
                            <button class="btn-mobile-outline btn-edit-outline" data-item="<?= $safeJson; ?>"
                                onclick="tryEdit(this)">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                            <button class="btn-mobile-outline btn-del-outline"
                                onclick="tryDelete('<?= $item['id']; ?>', '<?= $item['status']; ?>')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>