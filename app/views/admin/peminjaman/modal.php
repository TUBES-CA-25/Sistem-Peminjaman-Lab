<?php
// views/admin/peminjaman/modal.php
?>

<!-- MODAL: TAMBAH PEMINJAMAN SIMPLE (Pilih Lab & Slot) -->
<div id="pBookingModal" class="p-modal">
  <div class="p-modal-card">
    <div class="p-modal-head">
      <h2 style="margin:0;font-size:20px;font-weight:900;color:#0f172a;">Jadwal Laboratorium</h2>
      <button type="button" class="p-x" onclick="closeBookingModal()">&times;</button>
    </div>

    <div class="p-modal-body">
      <!-- Date Picker & Advanced Booking Button -->
      <div class="p-form-head" style="align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
        <div class="p-date-picker" style="flex-grow:1;">
          <label for="pBookingDate">Tanggal</label>
          <input type="date" id="pBookingDate" />
        </div>
        <!-- Button to open Multi-Lab / Manual Form -->
        <button type="button" class="p-type-btn" onclick="openExternalBookingModal()"
          style="min-width:180px; background:#1e3a5f; color:#fff;">
          + Booking Manual / Banyak Lab
        </button>
      </div>

      <!-- Labs Grid (Rendered by JavaScript) -->
      <div class="p-labs-grid" id="pLabsGrid"></div>

      <!-- Legend -->
      <div class="p-legend">
        <div class="p-legend-item praktikum-tetap">
          <span class="p-legend-color p-lg-praktikum"></span>
          Praktikum Tetap
        </div>
        <div class="p-legend-item peminjaman-internal">
          <span class="p-legend-color p-lg-internal"></span>
          Peminjaman Internal
        </div>
        <div class="p-legend-item peminjaman-eksternal">
          <span class="p-legend-color p-lg-eksternal"></span>
          Peminjaman Eksternal
        </div>
        <div class="p-legend-item jadwal-tergeser">
          <span class="p-legend-color p-lg-expired"></span>
          Jadwal Tergeser
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL: FORM DETAIL PINJAM (SINGLE SLOT) -->
<div id="pDetailedBookingModal" class="p-modal">
  <div class="p-modal-card" style="max-width:480px;">
    <div class="p-modal-head">
      <h2 style="margin:0; font-size:20px; font-weight:900; color:#0f172a;" id="pDetailModalTitle">Tambah Peminjaman
      </h2>
      <button type="button" class="p-x" onclick="closeDetailedBookingModal()">&times;</button>
    </div>

    <form id="pBookingForm" class="p-modal-body" method="POST" action="<?= BASE_URL ?>peminjaman"
      onsubmit="return savePeminjaman(event)">
      <!-- Tanggal -->
      <div style="margin-bottom:12px;">
        <label for="bookingDateDetail" style="font-weight:900; font-size:13px; color:#334155;">Tanggal</label>
        <input id="bookingDateDetail" name="tanggal" type="date" required
          style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; margin-top:4px;" />
      </div>

      <!-- Hari -->
      <div style="margin-bottom:12px;">
        <label for="hariDetail" style="font-weight:900; font-size:13px; color:#334155;">Hari</label>
        <input id="hariDetail" name="hari" type="text" readonly
          style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; background:#f9fafb; margin-top:4px;" />
      </div>

      <!-- Lab -->
      <div style="margin-bottom:12px;">
        <label for="labDetail" style="font-weight:900; font-size:13px; color:#334155;">Laboratorium</label>
        <input id="labDetail" name="laboratorium_display" type="text" readonly
          style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; background:#f9fafb; margin-top:4px;" />
      </div>

      <!-- Jam Mulai & Selesai -->
      <div style="display:flex; gap:12px; margin-bottom:12px;">
        <div style="flex:1;">
          <label for="jamMulaiDetail" style="font-weight:900; font-size:13px; color:#334155;">Jam Mulai</label>
          <input id="jamMulaiDetail" name="jamMulai" type="time" required step="60"
            style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; margin-top:4px;" />
        </div>
        <div style="flex:1;">
          <label for="jamSelesaiDetail" style="font-weight:900; font-size:13px; color:#334155;">Jam Selesai</label>
          <input id="jamSelesaiDetail" name="jamSelesai" type="time" required step="60"
            style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; margin-top:4px;" />
        </div>
      </div>

      <!-- Slot Kosong Info -->
      <div
        style="margin-bottom:12px; background:#f3f4f6; padding:10px 14px; border-radius:10px; font-size:13px; color:#334155;">
        <strong id="slotKosongInfo">Slot kosong: -</strong><br />
        <small style="opacity:0.7;">Pilih jam mulai/selesai di dalam slot kosong.</small>
      </div>

      <!-- Tipe Peminjam (Role) -->
      <div style="margin-bottom:12px;">
        <label for="tipePeminjamDetail" style="font-weight:900; font-size:13px; color:#334155;">Tipe Peminjam</label>
        <select id="tipePeminjamDetail" name="tipe" required
          style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; margin-top:4px;">
          <option value="internal">Internal (Dosen/Asisten)</option>
          <option value="eksternal">Eksternal (Luar Kampus)</option>
          <option value="admin">Admin (Maintenance/Testing)</option>
        </select>
      </div>

      <!-- Nama Peminjam -->
      <div style="margin-bottom:12px;">
        <label for="namaPeminjamDetail" style="font-weight:900; font-size:13px; color:#334155;">Nama Peminjam</label>
        <input id="namaPeminjamDetail" name="nama_peminjam" type="text" required
          style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; margin-top:4px;" />
      </div>

      <!-- Nama Kegiatan -->
      <div style="margin-bottom:18px;">
        <label for="namaKegiatanDetail" style="font-weight:900; font-size:13px; color:#334155;">Nama Kegiatan</label>
        <input id="namaKegiatanDetail" name="kegiatan" type="text" required
          style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; margin-top:4px;" />
      </div>

      <!-- Action Buttons -->
      <div style="text-align:right; display:flex; justify-content:flex-end; gap:10px;">
        <button type="button" onclick="closeDetailedBookingModal()"
          style="padding:10px 20px; border-radius:10px; border:1px solid #ccc; background:#f9fafb; cursor:pointer; font-weight:700;">Batal</button>
        <button type="submit"
          style="padding:10px 20px; border-radius:10px; border:none; background:#1F45AC; color:#fff; font-weight:900; cursor:pointer;">Simpan
          Peminjaman</button>
      </div>
    </form>
  </div>
</div>
</div>
</div>

<!-- MODAL: FORM DETAIL PINJAM MULTI-LAB (MANUAL) -->
<div id="pExternalBookingModal" class="p-modal">
  <div class="p-modal-card" style="max-width:600px;">
    <div class="p-modal-head">
      <h2 style="margin:0; font-size:20px; font-weight:900; color:#0f172a;">Input Manual / Banyak Lab</h2>
      <button type="button" class="p-x" onclick="closeExternalBookingModal()">&times;</button>
    </div>

    <form id="pExternalBookingForm" class="p-modal-body" onsubmit="return savePeminjamanEksternal(event)">
      <!-- Tanggal Mulai & Selesai -->
      <div style="display:flex; gap:20px; flex-wrap:wrap; margin-bottom:12px;">
        <div style="flex:1; min-width:140px;">
          <label for="externalTanggalMulai" style="font-weight:900; font-size:13px; color:#334155;">Tanggal
            Mulai</label>
          <input type="date" id="externalTanggalMulai" name="tanggalMulai" required
            style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; margin-top:4px;" />
        </div>
        <div style="flex:1; min-width:140px;">
          <label for="externalTanggalSelesai" style="font-weight:900; font-size:13px; color:#334155;">Tanggal
            Selesai</label>
          <input type="date" id="externalTanggalSelesai" name="tanggalSelesai" required
            style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; margin-top:4px;" />
        </div>
      </div>

      <!-- Tipe Peminjam (Role) -->
      <div style="margin-bottom:12px;">
        <label for="tipePeminjamMulti" style="font-weight:900; font-size:13px; color:#334155;">Tipe Peminjam</label>
        <select id="tipePeminjamMulti" name="tipe" required
          style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; margin-top:4px;">
          <option value="eksternal">Eksternal (Luar Kampus)</option>
          <option value="internal">Internal (Dosen/Asisten)</option>
          <option value="admin">Admin (Maintenance/Testing)</option>
        </select>
      </div>

      <!-- Nama Instansi / Kegiatan -->
      <div style="margin-bottom:12px;">
        <label for="instansiKegiatan" style="font-weight:900; font-size:13px; color:#334155;">Nama Instansi /
          Kegiatan</label>
        <input type="text" id="instansiKegiatan" name="instansiKegiatan" required
          placeholder="Contoh: Kegiatan UKM / Instansi Luar"
          style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; margin-top:4px;" />
      </div>

      <!-- Catatan Opsional -->
      <div style="margin-bottom:20px;">
        <label for="catatanOpsional" style="font-weight:900; font-size:13px; color:#334155;">Catatan (opsional)</label>
        <textarea id="catatanOpsional" name="catatanOpsional" rows="3" placeholder="Catatan tambahan..."
          style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; margin-top:4px; resize:none;"></textarea>
      </div>

      <!-- Lab Times Table -->
      <h3 style="font-weight:900; color:#0f172a; margin-bottom:12px;">Jam per Hari</h3>
      <table style="width:100%; border-collapse:collapse; font-size:14px; color:#334155; margin-bottom:18px;">
        <thead>
          <tr style="background:#f8fafc; border-bottom:2px solid #e2e8f0;">
            <th style="padding:8px 12px; text-align:left; width:40%;">Laboratorium</th>
            <th style="padding:8px 12px; text-align:center; width:10%;">Aktif</th>
            <th style="padding:8px 12px; text-align:center; width:25%;">Mulai</th>
            <th style="padding:8px 12px; text-align:center; width:25%;">Selesai</th>
          </tr>
        </thead>
        <tbody id="externalLabTimes">
          <!-- Lab list rows di-render JS -->
        </tbody>
      </table>

      <!-- Action Buttons -->
      <div style="text-align:right; display:flex; justify-content:flex-end; gap:10px;">
        <button type="button" onclick="closeExternalBookingModal()"
          style="padding:10px 20px; border-radius:10px; border:1px solid #ccc; background:#f9fafb; cursor:pointer; font-weight:700;">Batal</button>
        <button type="submit"
          style="padding:10px 20px; border-radius:10px; border:none; background:#1F45AC; color:#fff; font-weight:900; cursor:pointer;">Simpan
          Peminjaman</button>
      </div>
    </form>
  </div>
</div>