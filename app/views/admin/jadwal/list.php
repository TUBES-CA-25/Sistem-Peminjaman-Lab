<?php
// views/admin/jadwal/list.php
?>

<!-- TABLE SECTION -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
    <h5 class="mb-0 fw-bold text-dark">Daftar Jadwal Praktikum Tetap</h5>
    <span class="badge bg-secondary-subtle text-dark rounded-pill px-3">Total: <?= count($data['schedules']) ?></span>
  </div>
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0" id="jadwalTable" width="100%" cellspacing="0">
      <thead class="table-light text-secondary">
        <tr>
          <th class="px-4 py-3 border-0 rounded-start">Hari</th>
          <th class="py-3 border-0">Laboratorium</th>
          <th class="py-3 border-0">Jam</th>
          <th class="py-3 border-0">Mata Kuliah</th>
          <th class="py-3 border-0">Frekuensi</th>
          <th class="py-3 border-0">Kelas</th>
          <th class="px-4 py-3 border-0 text-end rounded-end">Aksi</th>
        </tr>
      </thead>
      <tbody id="jadwalTableBody">
        <!-- Data will be rendered by JavaScript -->
      </tbody>
    </table>
  </div>
</div>