<?php if (empty($data['riwayat'])) : ?>
    <div class="text-center py-5">
        <i class="bi bi-inbox fs-1 text-muted opacity-50 mb-3 d-block"></i>
        <h5 class="text-muted">Belum ada riwayat peminjaman.</h5>
        <p class="text-muted small">Klik tombol "Ajukan Baru" untuk memulai.</p>
    </div>
<?php else : ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="text-center py-3">No</th>
                    <th>Kegiatan</th>
                    <th>Waktu</th>
                    <th class="text-center">Proposal</th>
                    <th class="text-center">Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($data['riwayat'] as $item) : ?>
                <?php 
                    // --- PERBAIKAN DATA PASSING ---
                    // Menggunakan options JSON_HEX agar aman saat dimasukkan ke onclick HTML
                    $dataLengkap = [
                        'id' => $item['id'],
                        'nama' => $item['nama_lengkap'],
                        'email' => $item['email'],
                        'telepon' => $item['telepon'],
                        'kegiatan' => $item['nama_kegiatan'],
                        'peserta' => $item['jumlah_peserta'],
                        'mulai' => date('d M Y', strtotime($item['tgl_mulai'])), // Format tanggal agar enak dibaca
                        'selesai' => date('d M Y', strtotime($item['tgl_selesai'])),
                        'raw_mulai' => $item['tgl_mulai'], // Tanggal mentah untuk form edit
                        'raw_selesai' => $item['tgl_selesai'],
                        'proposal' => BASE_URL . '/uploads/' . $item['file_proposal'],
                        'status' => $item['status'],
                        'alasan' => $item['alasan_penolakan'] ?? '-'
                    ];
                    
                    // Encode ke JSON yang aman untuk HTML Attribute
                    $safeJson = htmlspecialchars(json_encode($dataLengkap), ENT_QUOTES, 'UTF-8');
                ?>
                <tr>
                    <td class="text-center text-muted fw-bold"><?= $no++; ?></td>
                    <td>
                        <div class="fw-bold text-dark"><?= $item['nama_kegiatan']; ?></div>
                        <div class="small text-muted"><i class="bi bi-person me-1"></i> <?= $item['jumlah_peserta']; ?> Peserta</div>
                    </td>
                    <td>
                        <div class="small">
                            <span class="text-primary fw-bold">Mulai:</span> <?= date('d/m/Y', strtotime($item['tgl_mulai'])); ?><br>
                            <span class="text-danger fw-bold">Selesai:</span> <?= date('d/m/Y', strtotime($item['tgl_selesai'])); ?>
                        </div>
                    </td>
                    <td class="text-center">
                        <?php if (!empty($item['file_proposal'])): ?>
                            <a href="<?= BASE_URL; ?>/uploads/<?= $item['file_proposal']; ?>" target="_blank" class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size: 0.8rem;">
                                <i class="bi bi-file-earmark-pdf"></i> PDF
                            </a>
                        <?php else: ?> - <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <?php 
                            $status = $item['status'];
                            $badge = 'badge-status-wait'; $icon = 'hourglass-split';
                            if ($status == 'Disetujui') { $badge = 'badge-status-ok'; $icon = 'check-circle'; }
                            elseif ($status == 'Ditolak') { $badge = 'badge-status-reject'; $icon = 'x-circle'; }
                            elseif ($status == 'Menunggu Interview') { $badge = 'badge-status-interview'; $icon = 'chat-text'; }
                        ?>
                        <span class="<?= $badge; ?>"><i class="bi bi-<?= $icon; ?> me-1"></i> <?= $status; ?></span>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            
                            <button class="btn-action btn-view" 
                                    data-item="<?= $safeJson; ?>"
                                    onclick="openDetail(this)" 
                                    title="Lihat Detail">
                                <i class="bi bi-eye"></i>
                            </button>

                            <button class="btn-action btn-edit" 
                                    data-item="<?= $safeJson; ?>"
                                    onclick="tryEdit(this)" 
                                    title="Edit Data">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <button class="btn-action btn-delete" 
                                    onclick="tryDelete('<?= $item['id']; ?>', '<?= $status; ?>')" 
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
<?php endif; ?>