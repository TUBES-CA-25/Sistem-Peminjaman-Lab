<?php
// views/internal/history/list.php
?>

<!-- TABLE WRAPPER -->
<!-- DESKTOP TABLE VIEW (Hidden on Mobile) -->
<div class="p-table-wrap d-none d-md-block">
  <table class="p-table">
    <thead>
      <tr>
        <th>Lab</th>
        <th>Tanggal & Waktu</th>
        <th>Keterangan</th>
        <th>Status</th>
        <th style="text-align:right;">Aksi</th>
      </tr>
    </thead>
    <tbody id="historyTableBody">
      <?php if (empty($data['peminjaman'])): ?>
      <tr>
        <td colspan="5" style="text-align: center; padding: 40px; color: #64748b;">
          <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 16px; display: block;"></i>
          Belum ada riwayat peminjaman
        </td>
      </tr>
      <?php else: ?>
        <?php foreach ($data['peminjaman'] as $p): ?>
        <tr data-id="<?= $p['id'] ?>">
          <td>
            <span style="font-weight: 700; color: #0f172a;"><?= htmlspecialchars($p['nama_ruangan']) ?></span>
          </td>
          <td>
            <div class="p-dt">
              <span class="p-date"><i class="far fa-calendar"></i> <?= date('Y-m-d', strtotime($p['tanggal'])) ?></span>
              <span class="p-time"><i class="far fa-clock"></i> <?= $p['jam_mulai'] ?> - <?= $p['jam_selesai'] ?></span>
            </div>
          </td>
          <td>
            <span style="color: #475569;"><?= htmlspecialchars($p['keterangan']) ?></span>
          </td>
          <td>
            <?php 
              $statusClass = 'p-status-nonaktif';
              $statusText = 'Menunggu';
              if ($p['status'] == 'disetujui') {
                $statusClass = 'p-status-aktif';
                $statusText = 'Disetujui';
              } elseif ($p['status'] == 'ditolak') {
                $statusClass = 'p-status-nonaktif';
                $statusText = 'Ditolak';
              }
            ?>
            <span class="p-badge <?= $statusClass ?>"><?= $statusText ?></span>
          </td>
          <td>
            <div class="p-actions">
              <?php 
                $waktuSelesai = strtotime($p['tanggal'] . ' ' . $p['jam_selesai']);
                $waktuSekarang = time();
                $isExpired = $waktuSelesai < $waktuSekarang;
              ?>

              <?php if (!$isExpired): ?>
                <button class="p-act p-edit" onclick="editPeminjaman(<?= $p['id'] ?>)" title="Edit">
                  <i class="fas fa-pen"></i>
                </button>
                <button class="p-act p-del" onclick="deletePeminjaman(<?= $p['id'] ?>)" title="Hapus">
                  <i class="fas fa-trash"></i>
                </button>
              <?php else: ?>
                <span style="color: #94a3b8; font-size: 0.85rem; font-style: italic;">Selesai</span>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- MOBILE CARD VIEW (Hidden on Desktop) -->
<div class="d-md-none">
    <?php if (empty($data['peminjaman'])): ?>
        <div style="text-align: center; padding: 40px; color: #64748b; background: white; border-radius: 12px; border: 1px solid #e2e8f0;">
          <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 16px; display: block;"></i>
          Belum ada riwayat
        </div>
    <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 16px;">
            <?php foreach ($data['peminjaman'] as $p): ?>
            <div style="background: white; border-radius: 12px; padding: 16px; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <div>
                        <div style="font-weight: 800; color: #0f172a; font-size: 1.1rem;margin-bottom: 4px;"><?= htmlspecialchars($p['nama_ruangan']) ?></div>
                        <div style="font-size: 0.9rem; color: #64748b;"><i class="far fa-calendar"></i> <?= date('d M Y', strtotime($p['tanggal'])) ?></div>
                        <div style="font-size: 0.9rem; color: #64748b;"><i class="far fa-clock"></i> <?= $p['jam_mulai'] ?> - <?= $p['jam_selesai'] ?></div>
                    </div>
                    <?php 
                      $statusClass = 'p-status-nonaktif';
                      $statusText = 'Menunggu';
                      if ($p['status'] == 'disetujui') {
                        $statusClass = 'p-status-aktif';
                        $statusText = 'Disetujui';
                      } elseif ($p['status'] == 'ditolak') {
                        $statusClass = 'p-status-nonaktif';
                        $statusText = 'Ditolak';
                      }
                    ?>
                    <span class="p-badge <?= $statusClass ?>" style="font-size: 0.75rem;"><?= $statusText ?></span>
                </div>
                
                <div style="background: #f8fafc; padding: 10px; border-radius: 8px; font-size: 0.9rem; color: #475569; margin-bottom: 12px;">
                    <?= htmlspecialchars($p['keterangan']) ?>
                </div>
                
                <div style="display: flex; justify-content: flex-end; pt-2; border-top: 1px solid #f1f5f9; padding-top: 12px;">
                     <?php 
                        $waktuSelesai = strtotime($p['tanggal'] . ' ' . $p['jam_selesai']);
                        $isExpired = $waktuSelesai < time();
                      ?>
                      <?php if (!$isExpired): ?>
                        <div class="p-actions">
                            <button class="p-act p-edit" onclick="editPeminjaman(<?= $p['id'] ?>)" title="Edit">
                              <i class="fas fa-pen"></i>
                            </button>
                            <button class="p-act p-del" onclick="deletePeminjaman(<?= $p['id'] ?>)" title="Hapus">
                              <i class="fas fa-trash"></i>
                            </button>
                        </div>
                      <?php else: ?>
                        <span style="color: #94a3b8; font-size: 0.85rem; font-style: italic;"><i class="fas fa-check-circle"></i> Selesai</span>
                      <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
