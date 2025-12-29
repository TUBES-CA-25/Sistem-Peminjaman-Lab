<?php
// app/views/pages/admin/data_peminjaman_content.php
// PURE CONTENT ONLY (tanpa <html>, <head>, <body>, tanpa sidebar/navbar)
?>

<!-- HERO -->
<div style="background: linear-gradient(135deg, #122E4F 0%, #1F45AC 100%); padding: 2.5rem; border-radius: 12px; margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1); gap: 1rem;">
  <div>
    <h1 style="color: white; font-size: 2rem; font-weight: 800; margin: 0 0 0.5rem 0;">Data Peminjaman</h1>
    <p style="color: rgba(255,255,255,0.9); font-size: 1rem; margin:0;">Kelola dan monitoring peminjaman laboratorium</p>
  </div>

  <div style="display:flex; gap:10px; flex-wrap:wrap;">
    <button type="button" class="p-add-btn" onclick="openBookingModal()">
      <i class="fas fa-plus"></i>
      Tambah Peminjaman
    </button>
    <button type="button" class="p-export-btn" onclick="exportReport()">
      <i class="fas fa-download"></i>
      Export Laporan
    </button>
  </div>
</div>

<!-- TABLE -->
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
      <!-- Data di-render dari JavaScript -->
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

<!-- MODAL: TAMBAH PEMINJAMAN SIMPLE -->
<div id="pBookingModal" class="p-modal">
  <div class="p-modal-card">
    <div class="p-modal-head">
      <h2 style="margin:0;font-size:20px;font-weight:900;color:#0f172a;">Tambah Peminjaman</h2>
      <button type="button" class="p-x" onclick="closeBookingModal()">&times;</button>
    </div>

    <div class="p-modal-body">
      <div class="p-form-head" style="align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
        <div class="p-date-picker" style="flex-grow:1;">
          <label for="pBookingDate">Tanggal</label>
          <input type="date" id="pBookingDate" />
        </div>
        <button type="button" class="p-type-btn" id="pTypeBtn" onclick="toggleBookingType()" data-type="eksternal" style="min-width:180px;">
          Booking Eksternal
        </button>
      </div>

      <div class="p-labs-grid" id="pLabsGrid"></div>

      <div class="p-legend">
        <div class="p-legend-item"><span class="p-legend-color p-lg-praktikum"></span> Praktikum Tetap</div>
        <div class="p-legend-item"><span class="p-legend-color p-lg-internal"></span> Peminjaman Internal</div>
        <div class="p-legend-item"><span class="p-legend-color p-lg-eksternal"></span> Peminjaman Eksternal</div>
        <div class="p-legend-item"><span class="p-legend-color p-lg-expired"></span> Jadwal Terpakai</div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL: FORM DETAIL PINJAM (INTERNAL) -->
<div id="pDetailedBookingModal" class="p-modal">
  <div class="p-modal-card" style="max-width:480px;">
    <div class="p-modal-head">
      <h2 style="margin:0; font-size:20px; font-weight:900; color:#0f172a;" id="pDetailModalTitle">Tambah Peminjaman (Internal)</h2>
      <button type="button" class="p-x" onclick="closeDetailedBookingModal()">&times;</button>
    </div>
    <form id="pBookingForm" class="p-modal-body" onsubmit="return savePeminjaman(event)">
      <div style="margin-bottom:12px;">
        <label for="bookingDateDetail" style="font-weight:900; font-size:13px; color:#334155;">Tanggal</label>
        <input id="bookingDateDetail" name="tanggal" type="date" required style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; margin-top:4px;" />
      </div>
      <div style="margin-bottom:12px;">
        <label for="hariDetail" style="font-weight:900; font-size:13px; color:#334155;">Hari</label>
        <input id="hariDetail" name="hari" type="text" readonly style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; background:#f9fafb; margin-top:4px;" />
      </div>
      <div style="margin-bottom:12px;">
        <label for="labDetail" style="font-weight:900; font-size:13px; color:#334155;">Laboratorium</label>
        <input id="labDetail" name="laboratorium" type="text" readonly style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; background:#f9fafb; margin-top:4px;" />
      </div>
      <div style="display:flex; gap:12px; margin-bottom:12px;">
        <div style="flex:1;">
          <label for="jamMulaiDetail" style="font-weight:900; font-size:13px; color:#334155;">Jam Mulai</label>
          <input id="jamMulaiDetail" name="jamMulai" type="time" required step="60" style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; margin-top:4px;" />
        </div>
        <div style="flex:1;">
          <label for="jamSelesaiDetail" style="font-weight:900; font-size:13px; color:#334155;">Jam Selesai</label>
          <input id="jamSelesaiDetail" name="jamSelesai" type="time" required step="60" style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; margin-top:4px;" />
        </div>
      </div>
      <div style="margin-bottom:12px; background:#f3f4f6; padding:10px 14px; border-radius:10px; font-size:13px; color:#334155;">
        <strong id="slotKosongInfo">Slot kosong: 09:40–10:30</strong><br />
        <small style="opacity:0.7;">Pilih jam mulai/selesai di dalam slot kosong.</small>
      </div>
      <div style="margin-bottom:12px;">
        <label for="namaPeminjamDetail" style="font-weight:900; font-size:13px; color:#334155;">Nama Peminjam</label>
        <input id="namaPeminjamDetail" name="namaPeminjam" type="text" required style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; margin-top:4px;" />
      </div>
      <div style="margin-bottom:18px;">
        <label for="namaKegiatanDetail" style="font-weight:900; font-size:13px; color:#334155;">Nama Kegiatan</label>
        <input id="namaKegiatanDetail" name="namaKegiatan" type="text" required style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; margin-top:4px;" />
      </div>
      <div style="text-align:right; display:flex; justify-content:flex-end; gap:10px;">
        <button type="button" onclick="closeDetailedBookingModal()" style="padding:10px 20px; border-radius:10px; border:1px solid #ccc; background:#f9fafb; cursor:pointer; font-weight:700;">Batal</button>
        <button type="submit" style="padding:10px 20px; border-radius:10px; border:none; background:#1F45AC; color:#fff; font-weight:900; cursor:pointer;">Simpan Peminjaman</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL: FORM DETAIL PINJAM EKSTERNAL -->
<div id="pExternalBookingModal" class="p-modal">
  <div class="p-modal-card" style="max-width:600px;">
    <div class="p-modal-head">
      <h2 style="margin:0; font-size:20px; font-weight:900; color:#0f172a;">Tambah Peminjaman (Eksternal)</h2>
      <button type="button" class="p-x" onclick="closeExternalBookingModal()">&times;</button>
    </div>
    <form id="pExternalBookingForm" class="p-modal-body" onsubmit="return savePeminjamanEksternal(event)">
      <div style="display:flex; gap:20px; flex-wrap:wrap; margin-bottom:12px;">
        <div style="flex:1; min-width:140px;">
          <label for="externalTanggalMulai" style="font-weight:900; font-size:13px; color:#334155;">Tanggal Mulai</label>
          <input type="date" id="externalTanggalMulai" name="tanggalMulai" required style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; margin-top:4px;" />
        </div>
        <div style="flex:1; min-width:140px;">
          <label for="externalTanggalSelesai" style="font-weight:900; font-size:13px; color:#334155;">Tanggal Selesai</label>
          <input type="date" id="externalTanggalSelesai" name="tanggalSelesai" required style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; margin-top:4px;" />
        </div>
      </div>

      <div style="margin-bottom:12px;">
        <label for="instansiKegiatan" style="font-weight:900; font-size:13px; color:#334155;">Nama Instansi / Kegiatan</label>
        <input type="text" id="instansiKegiatan" name="instansiKegiatan" required placeholder="POLDA" style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; margin-top:4px;" />
      </div>

      <div style="margin-bottom:20px;">
        <label for="catatanOpsional" style="font-weight:900; font-size:13px; color:#334155;">Catatan (opsional)</label>
        <textarea id="catatanOpsional" name="catatanOpsional" rows="3" placeholder="Catatan tambahan..." style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; margin-top:4px; resize:none;"></textarea>
      </div>

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

      <div style="text-align:right; display:flex; justify-content:flex-end; gap:10px;">
        <button type="button" onclick="closeExternalBookingModal()" style="padding:10px 20px; border-radius:10px; border:1px solid #ccc; background:#f9fafb; cursor:pointer; font-weight:700;">Batal</button>
        <button type="submit" style="padding:10px 20px; border-radius:10px; border:none; background:#1F45AC; color:#fff; font-weight:900; cursor:pointer;">Simpan Peminjaman</button>
      </div>
    </form>
  </div>
</div>

<style>
  /* Buttons */
  .p-add-btn{
    background:#fff;color:#1F45AC;border:none;border-radius:10px;
    padding:.75rem 1.1rem;font-weight:900;cursor:pointer;
    display:flex;align-items:center;gap:.5rem;box-shadow:0 2px 4px rgba(0,0,0,.12);
  }
  .p-add-btn:hover{ transform:translateY(-1px); }

  .p-export-btn{
    background:#0f172a;color:#fff;border:none;border-radius:10px;
    padding:.75rem 1.1rem;font-weight:900;cursor:pointer;
    display:flex;align-items:center;gap:.5rem;box-shadow:0 2px 4px rgba(0,0,0,.12);
  }
  .p-export-btn:hover{ background:#1f2937; transform:translateY(-1px); }

  /* Table */
  .p-table-wrap{
    background:#fff;border-radius:12px;padding:22px;
    box-shadow:0 1px 3px rgba(0,0,0,.08);
  }
  .p-table{ width:100%; border-collapse:collapse; }
  .p-table thead{ background:#f8fafc; }
  .p-table th{
    text-align:left;padding:12px;font-size:12px;font-weight:900;color:#64748b;
    text-transform:uppercase;letter-spacing:.04em;
  }
  .p-table td{ padding:16px 12px; border-top:1px solid #f1f5f9; font-size:14px; vertical-align:top; }
  .p-user-name{ font-weight:900; color:#0f172a; }
  .p-user-email{ font-size:12px; color:#94a3b8; margin-top:2px; display:block; }
  .p-user-instansi{ font-size:12px; color:#64748b; margin-top:2px; font-style:italic; }
  .p-user-role{ font-size:11px; color:#fff; background:#64748b; padding:2px 8px; border-radius:999px; display:inline-block; margin-top:4px; text-transform:uppercase; }
  .p-role-eksternal{ background:#2563eb; }
  .p-role-internal{ background:#059669; }
  .p-role-admin{ background:#b45309; }
  .p-date{ font-weight:800; color:#0f172a; }
  .p-time{ font-size:12px; color:#94a3b8; margin-top:2px; }
  .p-table tr:hover{ background:#fafafa; }

  /* Status badges pada kolom Status */
  .p-badge{ display:inline-block;padding:4px 12px;border-radius:999px;font-size:11px;font-weight:900; }
  .p-status-aktif{ background:#dcfce7; color:#059669; }
  .p-status-nonaktif{ background:#fee2e2; color:#dc2626; }

  /* Badges untuk tipe */
  .p-badge-tipe{ display:inline-block;padding:4px 12px; border-radius:999px;font-size:11px;font-weight:900; }
  .p-eksternal{ background:#dbeafe;color:#2563eb; }
  .p-internal{ background:#dcfce7;color:#059669; }
  .p-admin{ background:#fef3c7; color:#b45309; }

  /* Actions */
  .p-actions{ display:flex; gap:8px; justify-content:flex-end; }
  .p-act{
    width:34px;height:34px;border:none;border-radius:8px;cursor:pointer;
    display:inline-flex;align-items:center;justify-content:center;
    transition:transform .12s;
  }
  .p-act:hover{ transform:scale(1.06); }
  .p-edit{ background:#dbeafe;color:#2563eb; }
  .p-check{ background:#dcfce7;color:#059669; }
  .p-del{ background:#fee2e2;color:#dc2626; }

  /* Info */
  .p-info{
    margin-top:18px; display:flex; gap:12px; align-items:flex-start;
    background:#dbeafe; border-left:4px solid #3b82f6;
    padding:14px 16px; border-radius:12px;
  }
  .p-info i{ color:#2563eb; margin-top:2px; }
  .p-info-title{ font-weight:900; color:#1e40af; margin-bottom:2px; }
  .p-info-text{ color:#1e40af; font-size:13px; }

  /* Modal */
  .p-modal{
    display:none; position:fixed; inset:0; z-index:9999;
    background:rgba(0,0,0,.5); padding:22px;
    align-items:center; justify-content:center;
  }
  .p-modal.active{ display:flex; }
  .p-modal-card{
    width:95%; max-width:1400px; max-height:90vh; overflow:auto;
    background:#fff; border-radius:12px;
    box-shadow:0 20px 60px rgba(0,0,0,.3);
    animation:pSlide .18s ease-out;
  }
  @keyframes pSlide{ from{transform:translateY(-12px);opacity:.7} to{transform:translateY(0);opacity:1} }
  .p-modal-head{
    padding:18px 22px; border-bottom:1px solid #e5e7eb;
    display:flex; align-items:center; justify-content:space-between;
  }
  .p-x{
    width:40px;height:40px;border:none;border-radius:10px;
    background:transparent; font-size:28px; line-height:1;
    color:#94a3b8; cursor:pointer;
  }
  .p-x:hover{ background:#f1f5f9; color:#475569; }
  .p-modal-body{ padding:22px; }

  .p-form-head{ display:flex; gap:12px; align-items:flex-end; flex-wrap:wrap; margin-bottom:18px; }
  .p-date-picker label{ display:block; font-size:14px; font-weight:900; color:#334155; margin-bottom:8px; }
  .p-date-picker input{
    padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px;
    font-size:14px; min-width:180px; outline:none;
  }
  .p-date-picker input:focus{ border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,.12); }
  .p-type-btn{
    padding:10px 16px; border:none; border-radius:10px; cursor:pointer;
    background:#1e3a5f; color:#fff; font-weight:900;
    user-select:none;
  }
  .p-type-btn:hover{ background:#2c5282; }

  .p-labs-grid{
    display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));
    gap:18px; margin-bottom:18px;
  }
  .p-lab-card{
    background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px;
    padding:16px;
  }
  .p-lab-card h3{
    margin:0 0 12px 0; font-size:15px; font-weight:900; color:#0f172a;
    padding-bottom:10px; border-bottom:2px solid #e2e8f0;
  }
  .p-slot-list{ display:flex; flex-direction:column; gap:10px; }
  .p-slot{
    padding:12px 14px; border-radius:10px; font-size:13px; border:1px solid; line-height:1.5;
  }
  .p-slot.praktikum{ background:#fff; border-color:#cbd5e1; color:#1e40af; }
  /* Ganti warna garis putus-putus di sini */
  .p-slot.available{
    background:#fff; border:2px dashed #1B3555; color:#1B3555;
    cursor:pointer; font-weight:900;
    transition: all 0.3s ease;
  }
  .p-slot.available:hover{
    background:#1B3555; color:#fff; border-style:solid;
    transform:translateY(-2px); box-shadow:0 6px 14px rgba(27,53,85,.25);
  }
  .p-slot-label{ display:block; font-weight:900; margin-bottom:4px; }
  .p-slot-sub{ font-size:12px; opacity:.85; }

  /* Style input labs di external modal */
  #externalLabTimes input[type="time"]{
    width: 100%;
    padding:6px 8px;
    border:1px solid #e2e8f0;
    border-radius:6px;
    font-size:14px;
    box-sizing:border-box;
  }
  #externalLabTimes td{
    padding:4px 12px;
    vertical-align:middle;
  }
</style>

<script>
(function(){
  // ===== DATA PEMINJAMAN (contoh) =====
  // Menggunakan data peminjam contoh untuk tabel.
  const peminjamanData = [
    {
      name:'AKP Ahmad Kurniawan', email:'ahmad.kurniawan@polri.go.id', instansi:'Polda Metro Jaya',
      role:'eksternal', tanggal:'15 Des 2025', status:'aktif', username:'ahmad.kurniawan',
      lab:'Startup Lab', waktuMulai:'10:00', waktuSelesai:'12:00', statusPeminjaman:'Menunggu Konfirmasi', tipe:'Eksternal'
    },
    {
      name:'IPDA Budi Santoso', email:'budi.santoso@polri.go.id', instansi:'Polres Jakarta Selatan',
      role:'eksternal', tanggal:'14 Des 2025', status:'aktif', username:'budi.santoso',
      lab:'IoT Lab', waktuMulai:'07:00', waktuSelesai:'08:00', statusPeminjaman:'Disetujui', tipe:'Eksternal'
    },
    {
      name:'Admin Utama', email:'admin@iclabs.ac.id', instansi:'Admin Sistem',
      role:'admin', tanggal:'01 Des 2025', status:'aktif', username:'admin.utama',
      lab:'Cyber Security Lab', waktuMulai:'08:00', waktuSelesai:'10:00', statusPeminjaman:'Menunggu Konfirmasi', tipe:'Admin'
    },
    {
      name:'Dinda Lestari', email:'dinda@external.com', instansi:'Mitra Eksternal',
      role:'eksternal', tanggal:'12 Des 2025', status:'aktif', username:'dinda.lestari',
      lab:'Startup Lab', waktuMulai:'13:00', waktuSelesai:'15:00', statusPeminjaman:'Disetujui', tipe:'Eksternal'
    },
    {
      name:'Bima Saputra', email:'bima@iclabs.ac.id', instansi:'Dosen',
      role:'internal', tanggal:'10 Des 2025', status:'aktif', username:'bima.saputra',
      lab:'IoT Lab', waktuMulai:'07:00', waktuSelesai:'09:00', statusPeminjaman:'Disetujui', tipe:'Internal'
    },
    {
      name:'Eka Pratama', email:'eka@iclabs.ac.id', instansi:'Asisten',
      role:'internal', tanggal:'09 Des 2025', status:'aktif', username:'eka.pratama',
      lab:'Cyber Security Lab', waktuMulai:'10:00', waktuSelesai:'12:00', statusPeminjaman:'Menunggu Konfirmasi', tipe:'Internal'
    }
  ];

  // Render tabel berdasarkan data di atas
  function renderTable(){
    const tbody = document.getElementById('pTableBody');
    tbody.innerHTML = '';
    peminjamanData.forEach(item => {
      const tr = document.createElement('tr');

      const peminjamHTML = `
        <div class="p-user-name">${item.name}</div>
        <div class="p-user-email">${item.email}</div>
        <div class="p-user-instansi">${item.instansi}</div>
        <div class="p-user-role ${
          item.role==='eksternal' ? 'p-role-eksternal' :
          item.role==='internal' ? 'p-role-internal' :
          'p-role-admin'}">${item.role}</div>
      `;

      const statusClass = item.statusPeminjaman.toLowerCase().includes('menunggu') ? 'p-status-nonaktif' : 'p-status-aktif';
      const tipeClass = item.tipe.toLowerCase() === 'eksternal' ? 'p-eksternal' : (item.tipe.toLowerCase()==='internal' ? 'p-internal' : 'p-admin');

      tr.innerHTML = `
        <td>${peminjamHTML}</td>
        <td>${item.lab}</td>
        <td>
          <div class="p-dt">
            <div class="p-date"><i class="far fa-calendar"></i> ${item.tanggal}</div>
            <div class="p-time"><i class="far fa-clock"></i> ${item.waktuMulai} - ${item.waktuSelesai}</div>
          </div>
        </td>
        <td><span class="p-badge ${statusClass}">${item.statusPeminjaman}</span></td>
        <td><span class="p-badge-tipe ${tipeClass}">${item.tipe}</span></td>
        <td style="text-align:right;">
          <div class="p-actions">
            <button type="button" class="p-act p-edit" title="Edit" onclick="alert('Edit: demo')"><i class="fas fa-edit"></i></button>
            <button type="button" class="p-act p-check" title="Approve" onclick="alert('Approve: demo')"><i class="fas fa-check"></i></button>
            <button type="button" class="p-act p-del" title="Hapus" onclick="alert('Hapus: demo')"><i class="fas fa-times"></i></button>
          </div>
        </td>
      `;

      tbody.appendChild(tr);
    });
  }

  // ===== LAB LIST =====
  const LABS = [
    { key: "startup", name: "Startup" },
    { key: "iot", name: "IoT" },
    { key: "micro", name: "Micro" },
    { key: "cv", name: "CV" },
    { key: "ds", name: "DS" },
    { key: "cn", name: "Comnet" },
    { key: "mm", name: "Multimedia" },
    { key: "riset", name: "Riset 2" }
  ];

  // FIXED SCHEDULE (PRAKTIKUM) - sesuaikan bila perlu sama seperti sebelumnya
  const fixedSchedule = {
    senin: {
      startup: [
        { start:"10:30", end:"14:20", title:"Pemrograman (A1)" },
        { start:"14:30", end:"18:20", title:"P. Pemrograman (A2)" }
      ],
      iot: [{ start:"14:30", end:"18:20", title:"P. Pemrograman (A4)" }],
      micro: [
        { start:"07:00", end:"09:30", title:"Microcontroller (A1,A2,A3)" },
        { start:"12:10", end:"13:00", title:"Microcontroller (A7)" },
        { start:"13:00", end:"15:30", title:"Microcontroller (A8)" }
      ],
      cv: [
        { start:"09:40", end:"12:10", title:"Struktur Data (A7)" },
        { start:"13:00", end:"15:30", title:"Struktur Data (A5)" }
      ],
      ds: [
        { start:"07:00", end:"09:30", title:"Basis Data II (B4)" },
        { start:"09:40", end:"12:10", title:"Struktur Data (A8)" },
        { start:"13:00", end:"15:30", title:"Struktur Data (A6)" }
      ],
      cn: [],
      mm: [],
      riset: []
    },
    //... (jadwal sesuai sebelumnya, bisa diperpanjang)
  };

  const dayNames = ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
  const DAY_RANGE = { start: "07:00", end: "18:20" };

  // Fungsi utilitas konversi waktu
  function toMin(hhmm){ const [h,m]=hhmm.split(":").map(Number); return h*60+m; }
  function toHHMM(mins){ const h=String(Math.floor(mins/60)).padStart(2,"0"); const m=String(mins%60).padStart(2,"0"); return `${h}:${m}`; }

  // Cari interval waktu kosong
  function computeFreeIntervals(dayKey, labKey){
    const busy = (fixedSchedule[dayKey]?.[labKey] || []).map(ev => ({ start: toMin(ev.start), end: toMin(ev.end) }));
    const dayStart = toMin(DAY_RANGE.start);
    const dayEnd = toMin(DAY_RANGE.end);

    const sorted = busy
      .filter(x => x.end > dayStart && x.start < dayEnd)
      .map(x => ({ start: Math.max(x.start, dayStart), end: Math.min(x.end, dayEnd) }))
      .sort((a,b)=>a.start-b.start);

    const merged=[];
    for(const b of sorted){
      if(!merged.length || merged[merged.length-1].end < b.start) merged.push({ ...b });
      else merged[merged.length-1].end = Math.max(merged[merged.length-1].end, b.end);
    }

    const free=[];
    let cur=dayStart;
    for(const m of merged){
      if(cur < m.start) free.push({ start:cur, end:m.start });
      cur = Math.max(cur, m.end);
    }
    if(cur < dayEnd) free.push({ start:cur, end:dayEnd });

    return free.filter(x => (x.end-x.start) >= 15);
  }

  // ==== UI MAIN MODAL SIMPLE ====
  window.openBookingModal = function(){
    const modal = document.getElementById('pBookingModal');
    const dateInput = document.getElementById('pBookingDate');
    const today = new Date().toISOString().split('T')[0];
    dateInput.value = today;

    // Set type btn ke eksternal default
    const btnType = document.getElementById('pTypeBtn');
    btnType.dataset.type = 'eksternal';
    btnType.textContent = 'Booking Eksternal';
    btnType.style.background = '#1e3a5f';

    modal.classList.add('active');
    loadSchedule();
  };

  window.closeBookingModal = function(){
    document.getElementById('pBookingModal').classList.remove('active');
  };

  // Toggle booking type di modal utama
  window.toggleBookingType = function(){
    const btn = document.getElementById('pTypeBtn');
    const isEksternal = btn.dataset.type === 'eksternal';
    if(isEksternal){
      btn.dataset.type = 'internal';
      btn.textContent = 'Booking Internal';
      btn.style.background = '#0f172a';
    } else {
      btn.dataset.type = 'eksternal';
      btn.textContent = 'Booking Eksternal';
      btn.style.background = '#1e3a5f';
    }
  };

  window.exportReport = function(){
    alert('Export: demo');
  };

  // Render grid schedule di modal utama
  function loadSchedule(){
    const dateInput = document.getElementById('pBookingDate');
    const selectedDate = new Date(dateInput.value + 'T00:00:00');
    const dayName = dayNames[selectedDate.getDay()];

    const grid = document.getElementById('pLabsGrid');
    grid.innerHTML = '';

    LABS.forEach(lab => {
      const praktikum = fixedSchedule[dayName]?.[lab.key] || [];
      const freeIntervals = computeFreeIntervals(dayName, lab.key);

      const card = document.createElement('div');
      card.className = 'p-lab-card';

      let slots = '';

      praktikum.forEach(slot => {
        slots += `
          <div class="p-slot praktikum">
            <span class="p-slot-label">Praktikum Tetap ${slot.start}–${slot.end}</span>
            <div class="p-slot-sub">${slot.title}</div>
          </div>
        `;
      });

      freeIntervals.forEach(interval => {
        slots += `
          <div class="p-slot available" onclick="handleSlotClick('${dateInput.value}', '${dayName}', '${lab.name}', '${toHHMM(interval.start)}', '${toHHMM(interval.end)}')">
            <span class="p-slot-label">+ Pinjam (Kosong ${toHHMM(interval.start)}–${toHHMM(interval.end)})</span>
          </div>
        `;
      });

      card.innerHTML = `
        <h3>${lab.name}</h3>
        <div class="p-slot-list">
          ${slots || '<div style="text-align:center;padding:22px;color:#94a3b8;font-weight:800;">Tidak ada jadwal tersedia</div>'}
        </div>
      `;
      grid.appendChild(card);
    });
  }

  // Fungsi handler slot click: buka modal internal jika tipe internal, atau modal external jika eksternal
  function handleSlotClick(tanggal, hari, lab, jamMulai, jamSelesai){
    const btnType = document.getElementById('pTypeBtn');
    const tipe = btnType.dataset.type || 'eksternal';
    if(tipe === 'internal'){
      openDetailedBookingModal(tanggal, hari, lab, jamMulai, jamSelesai);
    } else {
      openExternalBookingModal(tanggal); // Buka modal eksternal
    }
  }
  window.handleSlotClick = handleSlotClick; // Expose ke global

  // ==== MODAL INTERNAL DETAIL ====
  const pDetailModal = document.getElementById('pDetailedBookingModal');
  const pBookingForm = document.getElementById('pBookingForm');
  const slotKosongInfo = document.getElementById('slotKosongInfo');

  // Buka modal detail internal dan isi form
  window.openDetailedBookingModal = function(tanggal, hari, lab, jamMulai, jamSelesai){
    document.getElementById('bookingDateDetail').value = tanggal;
    document.getElementById('hariDetail').value = hari.toUpperCase();
    document.getElementById('labDetail').value = lab;
    document.getElementById('jamMulaiDetail').value = jamMulai;
    document.getElementById('jamSelesaiDetail').value = jamSelesai;
    slotKosongInfo.textContent = `Slot kosong: ${jamMulai}–${jamSelesai}`;

    document.getElementById('namaPeminjamDetail').value = '';
    document.getElementById('namaKegiatanDetail').value = '';

    pDetailModal.classList.add('active');
  };

  window.closeDetailedBookingModal = function(){
    pDetailModal.classList.remove('active');
  };

  // Validasi dan simpan peminjaman internal (dummy)
  window.savePeminjaman = function(event){
    event.preventDefault();
    const form = event.target;

    const tanggal = form.tanggal.value;
    const hari = form.hari.value;
    const lab = form.laboratorium.value;
    const jamMulai = form.jamMulai.value;
    const jamSelesai = form.jamSelesai.value;
    const namaPeminjam = form.namaPeminjam.value.trim();
    const namaKegiatan = form.namaKegiatan.value.trim();

    // Validasi jam dalam slot kosong
    const slotText = slotKosongInfo.textContent;
    const match = slotText.match(/Slot kosong: (\d{2}:\d{2})[–-](\d{2}:\d{2})/);
    if(match){
      const slotStart = match[1];
      const slotEnd = match[2];
      if(jamMulai < slotStart || jamMulai >= slotEnd){
        alert('Jam Mulai harus berada di dalam slot kosong.');
        return false;
      }
      if(jamSelesai <= slotStart || jamSelesai > slotEnd){
        alert('Jam Selesai harus berada di dalam slot kosong.');
        return false;
      }
      if(jamMulai >= jamSelesai){
        alert('Jam Selesai harus lebih besar dari Jam Mulai.');
        return false;
      }
    }

    alert(`Peminjaman internal disimpan:\nTanggal: ${tanggal} (${hari})\nLab: ${lab}\nJam: ${jamMulai} - ${jamSelesai}\nNama: ${namaPeminjam}\nKegiatan: ${namaKegiatan}`);
    closeDetailedBookingModal();
    closeBookingModal();
    return false;
  };

  // ==== MODAL EKSTERNAL DETAIL ====
  const pExternalModal = document.getElementById('pExternalBookingModal');
  const pExternalForm = document.getElementById('pExternalBookingForm');
  const externalLabTimesBody = document.getElementById('externalLabTimes');

  // Buka modal eksternal, isi tanggal default dan reset form
  window.openExternalBookingModal = function(tanggal){
    // Reset form awal
    pExternalForm.reset();
    document.getElementById('externalTanggalMulai').value = tanggal;
    document.getElementById('externalTanggalSelesai').value = tanggal;
    document.getElementById('instansiKegiatan').value = '';
    document.getElementById('catatanOpsional').value = '';

    // Render daftar lab dengan checkbox dan waktu default
    externalLabTimesBody.innerHTML = '';

    LABS.forEach(lab => {
      const html = `
        <tr>
          <td>${lab.name}</td>
          <td style="text-align:center;"><input type="checkbox" name="aktif_${lab.key}" /></td>
          <td><input type="time" name="mulai_${lab.key}" value="07:00" /></td>
          <td><input type="time" name="selesai_${lab.key}" value="12:00" /></td>
        </tr>
      `;
      externalLabTimesBody.insertAdjacentHTML('beforeend', html);
    });

    pExternalModal.classList.add('active');
  };

  window.closeExternalBookingModal = function(){
    pExternalModal.classList.remove('active');
  };

  // Simpan data eksternal, validasi sederhana dan alert hasil (dummy)
  window.savePeminjamanEksternal = function(event){
    event.preventDefault();
    const form = event.target;

    let tanggalMulai = form.tanggalMulai.value;
    let tanggalSelesai = form.tanggalSelesai.value;
    let instansiKegiatan = form.instansiKegiatan.value.trim();
    let catatan = form.catatanOpsional.value.trim();

    if(tanggalMulai > tanggalSelesai){
      alert('Tanggal Mulai harus sama atau sebelum Tanggal Selesai.');
      return false;
    }
    if(instansiKegiatan === ''){
      alert('Nama Instansi / Kegiatan wajib diisi.');
      return false;
    }

    // Cek setidaknya ada satu lab aktif
    let adaAktif = false;
    const labData = [];
    LABS.forEach(lab => {
      const aktif = form[`aktif_${lab.key}`].checked;
      const mulai = form[`mulai_${lab.key}`].value;
      const selesai = form[`selesai_${lab.key}`].value;
      if(aktif) adaAktif = true;
      labData.push({lab: lab.name, aktif, mulai, selesai});
    });
    if(!adaAktif){
      alert('Pilih minimal satu laboratorium yang aktif.');
      return false;
    }

    // Validasi waktu mulai < selesai di tiap lab aktif
    for(const item of labData){
      if(item.aktif && item.mulai >= item.selesai){
        alert(`Jam Mulai harus sebelum Jam Selesai pada lab ${item.lab}.`);
        return false;
      }
    }

    // Simulasi simpan - tampilkan data ringkas termasuk labs
    alert(`Peminjaman Eksternal disimpan:
Tanggal Mulai: ${tanggalMulai}
Tanggal Selesai: ${tanggalSelesai}
Instansi/Kegiatan: ${instansiKegiatan}
Catatan: ${catatan || '-'}
Laboratorium Aktif:
${labData.filter(d => d.aktif).map(d => `${d.lab}: ${d.mulai} - ${d.selesai}`).join('\n')}`);

    closeExternalBookingModal();
    closeBookingModal();
    return false;
  };

  // Event listener untuk perubahan tanggal load schedule ulang
  document.getElementById('pBookingDate')?.addEventListener('change', loadSchedule);

  // Close modal ketika klik di luar modal card
  document.getElementById('pBookingModal')?.addEventListener('click', e=>{
    if(e.target === e.currentTarget) closeBookingModal();
  });
  document.getElementById('pDetailedBookingModal')?.addEventListener('click', e=>{
    if(e.target === e.currentTarget) closeDetailedBookingModal();
  });
  document.getElementById('pExternalBookingModal')?.addEventListener('click', e=>{
    if(e.target === e.currentTarget) closeExternalBookingModal();
  });

  // Render tabel saat halaman siap
  renderTable();

})();
</script>