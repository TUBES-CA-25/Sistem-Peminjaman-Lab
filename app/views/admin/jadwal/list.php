<?php
// views/admin/jadwal/list.php
?>

<!-- TABLE SECTION -->
<div class="card border-0 shadow-sm">
  <div class="card-header border-bottom py-3 d-flex justify-content-between align-items-center">
    <h5 class="mb-0 fw-bold">Daftar Jadwal Praktikum Tetap</h5>
    <span class="badge bg-secondary-subtle rounded-pill px-3">Total: <?= count($data['schedules']) ?></span>
  </div>
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0" id="jadwalTable">
      <thead class="text-secondary">
        <tr>
          <th class="px-4 py-3 border-0 rounded-start">Hari</th>
          <th class="py-3 border-0">Laboratorium</th>
          <th class="py-3 border-0">Jam</th>
          <th class="py-3 border-0">Mata Kuliah</th>
          <th class="py-3 border-0">Frekuensi</th>
          <th class="py-3 border-0">Kelas</th>
          <th class="px-4 py-3 border-0 text-end rounded-end" data-sortable="false">Aksi</th>
        </tr>
      </thead>
      <tbody id="jadwalTableBody">
        <?php
        // Create Lab Map for easy lookup
        $labMap = [];
        foreach ($data['labs'] as $lab) {
          $labMap[$lab['id']] = $lab['nama_ruangan'];
        }

        // HARI List helper
        $hariMap = [
          'senin' => 'Senin',
          'selasa' => 'Selasa',
          'rabu' => 'Rabu',
          'kamis' => 'Kamis',
          'jumat' => 'Jumat',
          'sabtu' => 'Sabtu',
          'minggu' => 'Minggu'
        ];
        ?>

        <?php foreach ($data['schedules'] as $index => $item): ?>
          <tr>
            <td class="px-4 fw-bold text-secondary">
              <?= $hariMap[strtolower($item['hari'])] ?? ucfirst($item['hari']) ?>
            </td>
            <td class="fw-bold text-dark">
              <?= $labMap[$item['lab_id']] ?? $item['lab_id'] ?>
            </td>
            <td>
              <div class="small">
                <span class="fw-bold text-secondary"><i class="far fa-clock me-1"></i></span>
                <?= substr($item['jam_mulai'], 0, 5) ?> - <?= substr($item['jam_selesai'], 0, 5) ?>
              </div>
            </td>
            <td class="fw-bold text-dark">
              <?= htmlspecialchars($item['nama_matakuliah']) ?>
            </td>
            <td>
              <span class="badge bg-secondary-subtle text-secondary-emphasis border">
                <?= htmlspecialchars($item['frekuensi'] ?: 'Mingguan') ?>
              </span>
            </td>
            <td>
              <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3">
                <?= htmlspecialchars($item['nama_kelas']) ?>
              </span>
            </td>
            <td>
              <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-primary fw-bold" title="Edit"
                  onclick="editJadwal(<?= $item['id'] ?>)">
                  <i class="fas fa-edit"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger fw-bold" title="Hapus"
                  onclick="hapusJadwal(<?= $item['id'] ?>)">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
