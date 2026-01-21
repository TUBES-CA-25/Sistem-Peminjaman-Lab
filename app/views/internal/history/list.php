<?php
// views/internal/history/list.php
?>

<!-- TABLE WRAPPER -->
<div class="p-table-wrap">
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
                // Gabungkan tanggal dan jam selesai untuk mendapatkan waktu berakhir peminjaman
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
                <!-- Jika sudah lewat, tidak ada aksi (read-only) atau icon info -->
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
