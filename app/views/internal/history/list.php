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
            <span class="p-lab-name"><?= htmlspecialchars($p['nama_ruangan']) ?></span>
          </td>
          <td>
            <div class="p-dt">
              <span class="p-date"><i class="far fa-calendar"></i> <?= date('Y-m-d', strtotime($p['tanggal'])) ?></span>
              <span class="p-time"><i class="far fa-clock"></i> <?= $p['jam_mulai'] ?> - <?= $p['jam_selesai'] ?></span>
            </div>
          </td>
          <td>
            <span class="p-keterangan"><?= htmlspecialchars($p['keterangan']) ?></span>
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
              } elseif ($p['status'] == 'tergeser') {
                $statusClass = 'p-status-tergeser';
                $statusText = 'Tergeser';
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
                <span class="p-status-done">Selesai</span>
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
        <div class="p-empty-state">
          <i class="fas fa-inbox"></i>
          Belum ada riwayat
        </div>
    <?php else: ?>
        <div class="p-mobile-list">
            <?php foreach ($data['peminjaman'] as $p): ?>
            <div class="p-mobile-card">
                <div class="p-mobile-card-header">
                    <div>
                        <div class="p-lab-name"><?= htmlspecialchars($p['nama_ruangan']) ?></div>
                        <div class="p-mobile-info"><i class="far fa-calendar"></i> <?= date('d M Y', strtotime($p['tanggal'])) ?></div>
                        <div class="p-mobile-info"><i class="far fa-clock"></i> <?= $p['jam_mulai'] ?> - <?= $p['jam_selesai'] ?></div>
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
                      } elseif ($p['status'] == 'tergeser') {
                        $statusClass = 'p-status-tergeser';
                        $statusText = 'Tergeser';
                      }
                    ?>
                    <span class="p-badge <?= $statusClass ?>"><?= $statusText ?></span>
                </div>
                
                <div class="p-mobile-keterangan">
                    <?= htmlspecialchars($p['keterangan']) ?>
                </div>
                
                <div class="p-mobile-card-footer">
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
                        <span class="p-status-done"><i class="fas fa-check-circle"></i> Selesai</span>
                      <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
