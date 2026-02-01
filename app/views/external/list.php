<?php if (empty($data['riwayat'])): ?>
    <div class="text-center py-5 bg-white rounded shadow-sm">
        <i class="bi bi-inbox fs-1 text-muted opacity-25 mb-3 d-block"></i>
        <h6 class="fw-bold text-dark">Belum ada riwayat peminjaman.</h6>
        <p class="text-muted small">Klik tombol "Ajukan Baru" di atas untuk memulai.</p>
    </div>
<?php else: ?>
    
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover table-borderless align-middle mb-0" style="min-width: 800px;">
                <thead class="bg-light border-bottom">
                    <tr>
                        <th width="5%" class="text-center py-3 text-secondary text-uppercase small fw-bold">No</th>
                        <th width="30%" class="py-3 text-secondary text-uppercase small fw-bold ps-4">Kegiatan</th>
                        <th width="25%" class="py-3 text-secondary text-uppercase small fw-bold">Waktu</th>
                        <th width="10%" class="text-center py-3 text-secondary text-uppercase small fw-bold">Proposal</th>
                        <th width="15%" class="text-center py-3 text-secondary text-uppercase small fw-bold">Status</th>
                        <th width="15%" class="text-end py-3 text-secondary text-uppercase small fw-bold pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($data['riwayat'] as $item): ?>
                        <?php
                        // --- DATA PASSING ---
                        // Menggunakan options JSON_HEX agar aman saat dimasukkan ke onclick HTML
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

                        // Encode ke JSON yang aman
                        $safeJson = htmlspecialchars(json_encode($dataLengkap), ENT_QUOTES, 'UTF-8');
                        ?>
                        <tr class="border-bottom">
                            <td class="text-center fw-bold text-secondary"><?= $no++; ?></td>
                            
                            <td class="ps-4">
                                <div class="fw-bold text-dark mb-1"><?= $item['nama_kegiatan']; ?></div>
                                <span class="badge bg-light text-dark border rounded-pill fw-normal">
                                    <i class="bi bi-people me-1"></i> <?= $item['jumlah_peserta']; ?> Peserta
                                </span>
                            </td>
                            
                            <td>
                                <div class="small">
                                    <div class="mb-1 text-primary fw-bold">
                                        <i class="bi bi-calendar-check me-2"></i><?= date('d M Y', strtotime($item['tgl_mulai'])); ?>
                                    </div>
                                    <div class="text-danger fw-bold">
                                        <i class="bi bi-calendar-x me-2"></i><?= date('d M Y', strtotime($item['tgl_selesai'])); ?>
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
                                    <span class="text-muted small">No File</span>
                                <?php endif; ?>
                            </td>
                            
                            <td class="text-center">
                                <?php
                                $badgeClass = 'bg-warning text-dark bg-opacity-25';
                                $iconStatus = 'bi-hourglass-split';

                                if ($item['status'] == 'Disetujui') {
                                    $badgeClass = 'bg-success text-success bg-opacity-10';
                                    $iconStatus = 'bi-check-circle-fill';
                                } elseif ($item['status'] == 'Ditolak') {
                                    $badgeClass = 'bg-danger text-danger bg-opacity-10';
                                    $iconStatus = 'bi-x-circle-fill';
                                } elseif ($item['status'] == 'Menunggu Interview') {
                                    $badgeClass = 'bg-info text-info bg-opacity-10';
                                    $iconStatus = 'bi-mic-fill';
                                }
                                ?>
                                <span class="badge <?= $badgeClass; ?> rounded-pill px-3 py-2 d-inline-flex align-items-center gap-2">
                                    <i class="bi <?= $iconStatus; ?>"></i> <?= $item['status']; ?>
                                </span>
                            </td>
                            
                            <td class="text-end pe-4">
                                <div class="btn-group shadow-sm rounded-3" role="group">
                                    <button class="btn-action btn-view" data-item="<?= $safeJson; ?>" onclick="openDetail(this)"
                                    title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <button class="btn-action btn-edit" data-item="<?= $safeJson; ?>" onclick="tryEdit(this)"
                                    title="Edit Data">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <button class="btn-action btn-delete"
                                    onclick="tryDelete('<?= $item['id']; ?>', '<?= $item['status']; ?>')" title="Batalkan">
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
<?php endif; ?>