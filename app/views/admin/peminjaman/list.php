<?php
// views/admin/peminjaman/list.php
?>

<!-- TABLE WRAPPER -->
<div class="p-table-wrap">
  <table class="p-table">
    <thead>
      <tr>
        <th>Peminjam</th>
        <th>Lab</th>
        <th>Tanggal & Waktu</th>
        <th>Status</th>
        <th>Tipe</th>
        <th style="text-align:right;">Aksi</th>
      </tr>
    </thead>
    <tbody id="pTableBody">
      <!-- Data akan di-render dari JavaScript di script.php -->
    </tbody>
  </table>

  <!-- INFO BOX -->
  <div class="p-info">
    <i class="fas fa-info-circle"></i>
    <div>
      <div class="p-info-title">Notifikasi Email Otomatis</div>
      <div class="p-info-text">Ketika peminjaman disetujui, sistem akan otomatis mengirimkan email notifikasi ke koordinator lab terkait.</div>
    </div>
  </div>
</div>