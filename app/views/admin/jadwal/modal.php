<?php
// views/admin/jadwal/modal.php
?>

<!-- MODAL: TAMBAH/EDIT JADWAL PRAKTIKUM -->
<div id="pScheduleModal" class="p-modal">
  <div class="p-modal-card" style="max-width:600px;">
    <div class="p-modal-head">
      <h2 style="margin:0; font-size:20px; font-weight:900; color:#0f172a;" id="scheduleModalTitle">Tambah Jadwal Praktikum Tetap</h2>
      <button type="button" class="p-x" onclick="closeScheduleModal()">&times;</button>
    </div>
    
    <form id="pScheduleForm" class="p-modal-body" onsubmit="return saveJadwalPraktikum(event)">
      <!-- Hidden Field for Edit Mode -->
      <input type="hidden" id="scheduleEditIndex" name="editIndex" value="" />
      
      <!-- Hari -->
      <div style="margin-bottom:12px;">
        <label for="scheduleHari" style="font-weight:900; font-size:13px; color:#334155;">Hari</label>
        <select id="scheduleHari" name="hari" required style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; margin-top:4px;">
          <option value="">Pilih Hari</option>
          <option value="senin">Senin</option>
          <option value="selasa">Selasa</option>
          <option value="rabu">Rabu</option>
          <option value="kamis">Kamis</option>
          <option value="jumat">Jumat</option>
          <option value="sabtu">Sabtu</option>
          <option value="minggu">Minggu</option>
        </select>
      </div>

      <!-- Laboratorium -->
      <div style="margin-bottom:12px;">
        <label for="scheduleLab" style="font-weight:900; font-size:13px; color:#334155;">Laboratorium</label>
        <select id="scheduleLab" name="lab" required style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; margin-top:4px;">
          <option value="">Pilih Lab</option>
          <option value="startup">Startup Lab</option>
          <option value="iot">IoT Lab</option>
          <option value="micro">Micro Lab</option>
          <option value="cv">Computer Vision Lab</option>
          <option value="ds">Data Science Lab</option>
          <option value="cn">Computer Networking Lab</option>
          <option value="mm">Multimedia Lab</option>
          <option value="riset">Riset 2 Lab</option>
        </select>
      </div>

      <!-- Jam Mulai & Selesai -->
      <div style="display:flex; gap:12px; margin-bottom:12px;">
        <div style="flex:1;">
          <label for="scheduleJamMulai" style="font-weight:900; font-size:13px; color:#334155;">Jam Mulai</label>
          <input id="scheduleJamMulai" name="jamMulai" type="time" required step="60" style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; margin-top:4px;" />
        </div>
        <div style="flex:1;">
          <label for="scheduleJamSelesai" style="font-weight:900; font-size:13px; color:#334155;">Jam Selesai</label>
          <input id="scheduleJamSelesai" name="jamSelesai" type="time" required step="60" style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; margin-top:4px;" />
        </div>
      </div>

      <!-- Mata Kuliah -->
      <div style="margin-bottom:12px;">
        <label for="scheduleMataKuliah" style="font-weight:900; font-size:13px; color:#334155;">Mata Kuliah</label>
        <input id="scheduleMataKuliah" name="mataKuliah" type="text" required placeholder="e.g., Basis Data II" style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; margin-top:4px;" />
      </div>

      <!-- Kelas -->
      <div style="margin-bottom:18px;">
        <label for="scheduleKelas" style="font-weight:900; font-size:13px; color:#334155;">Kelas</label>
        <input id="scheduleKelas" name="kelas" type="text" required placeholder="e.g., B4, A1, A2" style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; margin-top:4px;" />
      </div>

      <!-- Action Buttons -->
      <div style="text-align:right; display:flex; justify-content:flex-end; gap:10px;">
        <button type="button" onclick="closeScheduleModal()" style="padding:10px 20px; border-radius:10px; border:1px solid #ccc; background:#f9fafb; cursor:pointer; font-weight:700;">Batal</button>
        <button type="submit" style="padding:10px 20px; border-radius:10px; border:none; background:#1F45AC; color:#fff; font-weight:900; cursor:pointer;">Simpan Jadwal</button>
      </div>
    </form>
  </div>
</div>