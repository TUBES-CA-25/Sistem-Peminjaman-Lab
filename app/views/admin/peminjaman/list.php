<?php
// views/admin/peminjaman/list.php
?>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
  <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
    <h5 class="mb-0 fw-bold text-dark">Daftar Peminjaman</h5>
    <span class="badge bg-secondary-subtle text-dark rounded-pill px-3" id="totalBookings">Total: 0</span>
  </div>
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0" id="pTable">
      <thead class="table-light text-secondary">
        <tr>
          <th class="px-4 py-3 border-0 rounded-start fw-bold" style="width: 50px;">No</th>
          <th class="px-4 py-3 border-0 fw-bold">Peminjam</th>
          <th class="py-3 border-0 fw-bold">Lab</th>
          <th class="py-3 border-0 fw-bold">Tanggal & Waktu</th>
          <th class="py-3 border-0 fw-bold">Status</th>
          <th class="py-3 border-0 fw-bold">Tipe</th>
          <th class="text-end px-4 py-3 border-0 rounded-end fw-bold" data-sortable="false">Aksi</th>
        </tr>
      </thead>
      <tbody id="pTableBody">
        <!-- Data akan di-render dari JavaScript -->
      </tbody>
    </table>
  </div>
</div>

<!-- INFO BOX -->
<div class="p-info">
  <i class="fas fa-info-circle"></i>
  <div>
    <div class="p-info-title">Notifikasi Email Otomatis</div>
    <div class="p-info-text">Ketika peminjaman disetujui, sistem akan otomatis mengirimkan email notifikasi ke
      koordinator lab terkait.</div>
  </div>
</div>
</div>