<?php
// views/admin/peminjaman/script.php
?>

<script>
(function(){
  // ===== DATA PEMINJAMAN =====
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

  // ===== FIXED SCHEDULE (PRAKTIKUM) =====
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
    selasa: {
      startup: [],
      iot: [],
      micro: [],
      cv: [],
      ds: [],
      cn: [],
      mm: [],
      riset: []
    },
    rabu: {
      startup: [],
      iot: [],
      micro: [],
      cv: [],
      ds: [],
      cn: [],
      mm: [],
      riset: []
    },
    kamis: {
      startup: [],
      iot: [],
      micro: [],
      cv: [],
      ds: [],
      cn: [],
      mm: [],
      riset: []
    },
    jumat: {
      startup: [],
      iot: [],
      micro: [],
      cv: [],
      ds: [],
      cn: [],
      mm: [],
      riset: []
    },
    sabtu: {
      startup: [],
      iot: [],
      micro: [],
      cv: [],
      ds: [],
      cn: [],
      mm: [],
      riset: []
    },
    minggu: {
      startup: [],
      iot: [],
      micro: [],
      cv: [],
      ds: [],
      cn: [],
      mm: [],
      riset: []
    }
  };

  const dayNames = ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
  const DAY_RANGE = { start: "07:00", end: "18:20" };

  // ===== HELPER FUNCTIONS =====
  function toMin(hhmm){ 
    const [h,m]=hhmm.split(":").map(Number); 
    return h*60+m; 
  }

  function toHHMM(mins){ 
    const h=String(Math.floor(mins/60)).padStart(2,"0"); 
    const m=String(mins%60).padStart(2,"0"); 
    return `${h}:${m}`; 
  }

  // Cek bentrok dengan peminjaman eksternal
  function cekBentrokEksternal(tanggal, labName, jamMulai, jamSelesai) {
    const eksternalBookings = peminjamanData.filter(item => 
      item.tipe === 'Eksternal' && 
      item.tanggal === tanggal && 
      item.lab === labName
    );
    
    for (const booking of eksternalBookings) {
      const bookStart = toMin(booking.waktuMulai);
      const bookEnd = toMin(booking.waktuSelesai);
      const slotStart = toMin(jamMulai);
      const slotEnd = toMin(jamSelesai);
      
      if (bookStart < slotEnd && bookEnd > slotStart) {
        return true;
      }
    }
    
    return false;
  }

  // Get peminjaman internal
  function getInternalBookings(tanggal, labName) {
    return peminjamanData
      .filter(item => 
        item.tipe === 'Internal' && 
        item.tanggal === tanggal && 
        item.lab === labName
      )
      .map(item => ({
        start: item.waktuMulai,
        end: item.waktuSelesai,
        title: item.namaKegiatan || item.name
      }));
  }

  // Compute free intervals
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

  // ===== RENDER TABLE =====
  function renderTable(){
    const tbody = document.getElementById('pTableBody');
    tbody.innerHTML = '';
    
    peminjamanData.forEach((item, index) => {
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

      const statusPeminjaman = item.role === 'internal' ? 'Disetujui' : item.statusPeminjaman;
      const statusClass = statusPeminjaman.toLowerCase().includes('menunggu') ? 'p-status-nonaktif' : 'p-status-aktif';
      const tipeClass = item.tipe.toLowerCase() === 'eksternal' ? 'p-eksternal' : (item.tipe.toLowerCase()==='internal' ? 'p-internal' : 'p-admin');

      let actionButtons = '';
      if (item.role === 'internal') {
        actionButtons = `
          <button type="button" class="p-act p-del" title="Hapus" onclick="hapusPeminjaman(${index})">
            <i class="fas fa-times"></i>
          </button>
        `;
      } else if (item.role === 'eksternal') {
        actionButtons = `
          <button type="button" class="p-act p-edit" title="Edit" onclick="editPeminjamanEksternal(${index})">
            <i class="fas fa-edit"></i>
          </button>
          <button type="button" class="p-act p-check" title="Approve" onclick="approvePeminjaman(${index})">
            <i class="fas fa-check"></i>
          </button>
          <button type="button" class="p-act p-del" title="Hapus" onclick="hapusPeminjaman(${index})">
            <i class="fas fa-times"></i>
          </button>
        `;
      } else {
        actionButtons = `
          <button type="button" class="p-act p-edit" title="Edit" onclick="alert('Edit admin: demo')">
            <i class="fas fa-edit"></i>
          </button>
          <button type="button" class="p-act p-check" title="Approve" onclick="approvePeminjaman(${index})">
            <i class="fas fa-check"></i>
          </button>
          <button type="button" class="p-act p-del" title="Hapus" onclick="hapusPeminjaman(${index})">
            <i class="fas fa-times"></i>
          </button>
        `;
      }

      tr.innerHTML = `
        <td>${peminjamHTML}</td>
        <td>${item.lab}</td>
        <td>
          <div class="p-dt">
            <div class="p-date"><i class="far fa-calendar"></i> ${item.tanggal}</div>
            <div class="p-time"><i class="far fa-clock"></i> ${item.waktuMulai} - ${item.waktuSelesai}</div>
          </div>
        </td>
        <td><span class="p-badge ${statusClass}">${statusPeminjaman}</span></td>
        <td><span class="p-badge-tipe ${tipeClass}">${item.tipe}</span></td>
        <td style="text-align:right;">
          <div class="p-actions">
            ${actionButtons}
          </div>
        </td>
      `;

      tbody.appendChild(tr);
    });
  }

  // ===== MODAL FUNCTIONS =====
  
  // Open Main Booking Modal
  window.openBookingModal = function(){
    const modal = document.getElementById('pBookingModal');
    const dateInput = document.getElementById('pBookingDate');
    const today = new Date().toISOString().split('T')[0];
    dateInput.value = today;

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

  // Toggle Booking Type
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

  // Load Schedule Grid
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

      // Render praktikum tetap atau tergeser
      praktikum.forEach(slot => {
        const adaBentrok = cekBentrokEksternal(dateInput.value, lab.name, slot.start, slot.end);
        const slotClass = adaBentrok ? 'tergeser' : 'praktikum';
        const slotLabel = adaBentrok ? 'Jadwal Tergeser' : 'Praktikum Tetap';
        
        slots += `
          <div class="p-slot ${slotClass}">
            <span class="p-slot-label">${slotLabel} ${slot.start}–${slot.end}</span>
            <div class="p-slot-sub">${slot.title}</div>
          </div>
        `;
      });

      // Render peminjaman internal
      const internalBookings = getInternalBookings(dateInput.value, lab.name);
      internalBookings.forEach(booking => {
        const adaBentrok = cekBentrokEksternal(dateInput.value, lab.name, booking.start, booking.end);
        const slotClass = adaBentrok ? 'tergeser' : 'internal';
        const slotLabel = adaBentrok ? 'Jadwal Tergeser' : 'Peminjaman Internal';
        
        slots += `
          <div class="p-slot ${slotClass}">
            <span class="p-slot-label">${slotLabel} ${booking.start}–${booking.end}</span>
            <div class="p-slot-sub">${booking.title}</div>
          </div>
        `;
      });

      // Render slot kosong
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

  // Handle Slot Click
  window.handleSlotClick = function(tanggal, hari, lab, jamMulai, jamSelesai){
    const btnType = document.getElementById('pTypeBtn');
    const tipe = btnType.dataset.type || 'eksternal';
    if(tipe === 'internal'){
      openDetailedBookingModal(tanggal, hari, lab, jamMulai, jamSelesai);
    } else {
      openExternalBookingModal(tanggal);
    }
  };

  // ===== INTERNAL MODAL =====
  const pDetailModal = document.getElementById('pDetailedBookingModal');
  const pBookingForm = document.getElementById('pBookingForm');
  const slotKosongInfo = document.getElementById('slotKosongInfo');

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

  // ===== EXTERNAL MODAL =====
  const pExternalModal = document.getElementById('pExternalBookingModal');
  const pExternalForm = document.getElementById('pExternalBookingForm');
  const externalLabTimesBody = document.getElementById('externalLabTimes');

  window.openExternalBookingModal = function(tanggal){
    pExternalForm.reset();
    document.getElementById('externalTanggalMulai').value = tanggal;
    document.getElementById('externalTanggalSelesai').value = tanggal;
    document.getElementById('instansiKegiatan').value = '';
    document.getElementById('catatanOpsional').value = '';

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

    for(const item of labData){
      if(item.aktif && item.mulai >= item.selesai){
        alert(`Jam Mulai harus sebelum Jam Selesai pada lab ${item.lab}.`);
        return false;
      }
    }

    const editIndex = form.dataset.editIndex;
    if(editIndex !== undefined && editIndex !== ''){
      const idx = parseInt(editIndex);
      const firstActiveLab = labData.find(d => d.aktif);
      peminjamanData[idx].instansi = instansiKegiatan;
      peminjamanData[idx].lab = firstActiveLab.lab;
      peminjamanData[idx].waktuMulai = firstActiveLab.mulai;
      peminjamanData[idx].waktuSelesai = firstActiveLab.selesai;
      peminjamanData[idx].tanggal = tanggalMulai;
      
      alert('Peminjaman Eksternal berhasil diupdate!');
      delete form.dataset.editIndex;
      renderTable();
    } else {
      alert(`Peminjaman Eksternal disimpan:
Tanggal Mulai: ${tanggalMulai}
Tanggal Selesai: ${tanggalSelesai}
Instansi/Kegiatan: ${instansiKegiatan}
Catatan: ${catatan || '-'}
Laboratorium Aktif:
${labData.filter(d => d.aktif).map(d => `${d.lab}: ${d.mulai} - ${d.selesai}`).join('\n')}`);
    }

    closeExternalBookingModal();
    closeBookingModal();
    return false;
  };

  // ===== ACTION FUNCTIONS =====
  window.editPeminjamanEksternal = function(index){
    const item = peminjamanData[index];
    
    pExternalForm.reset();
    
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('externalTanggalMulai').value = today;
    document.getElementById('externalTanggalSelesai').value = today;
    
    document.getElementById('instansiKegiatan').value = item.instansi || '';
    document.getElementById('catatanOpsional').value = '';

    externalLabTimesBody.innerHTML = '';
    LABS.forEach(lab => {
      const isActive = lab.name === item.lab;
      const checkedAttr = isActive ? 'checked' : '';
      const jamMulai = isActive ? item.waktuMulai : '07:00';
      const jamSelesai = isActive ? item.waktuSelesai : '12:00';
      
      const html = `
        <tr>
          <td>${lab.name}</td>
          <td style="text-align:center;"><input type="checkbox" name="aktif_${lab.key}" ${checkedAttr} /></td>
          <td><input type="time" name="mulai_${lab.key}" value="${jamMulai}" /></td>
          <td><input type="time" name="selesai_${lab.key}" value="${jamSelesai}" /></td>
        </tr>
      `;
      externalLabTimesBody.insertAdjacentHTML('beforeend', html);
    });

    pExternalForm.dataset.editIndex = index;
    pExternalModal.classList.add('active');
  };

  window.approvePeminjaman = function(index){
    const item = peminjamanData[index];
    if(confirm(`Approve peminjaman ${item.name} di ${item.lab}?`)){
      peminjamanData[index].statusPeminjaman = 'Disetujui';
      renderTable();
      alert('Peminjaman disetujui! Email notifikasi akan dikirim ke koordinator lab.');
    }
  };

  window.hapusPeminjaman = function(index){
    const item = peminjamanData[index];
    if(confirm(`Hapus peminjaman ${item.name} di ${item.lab}?`)){
      peminjamanData.splice(index, 1);
      renderTable();
      alert('Peminjaman berhasil dihapus.');
    }
  };

  // ===== EXPORT REPORT =====
  window.exportReport = function() {
    const headerInfo = [
      ['LAPORAN DATA PEMINJAMAN LABORATORIUM'],
      ['IC-LABS - Innovation Center Laboratories'],
      [`Tanggal Export: ${new Date().toLocaleDateString('id-ID', { 
        day: 'numeric', 
        month: 'long', 
        year: 'numeric' 
      })}`],
      [],
      ['No', 'Nama Peminjam', 'Email', 'Instansi', 'Role', 'Laboratorium', 'Tanggal', 'Waktu Mulai', 'Waktu Selesai', 'Status', 'Tipe']
    ];
    
    const dataRows = peminjamanData.map((item, index) => {
      const statusPeminjaman = item.role === 'internal' ? 'Disetujui' : item.statusPeminjaman;
      return [
        index + 1,
        item.name,
        item.email,
        item.instansi,
        item.role.toUpperCase(),
        item.lab,
        item.tanggal,
        item.waktuMulai,
        item.waktuSelesai,
        statusPeminjaman,
        item.tipe
      ];
    });
    
    const fullData = [...headerInfo, ...dataRows];
    
    const summaryRow = [
      '',
      `Total Peminjaman: ${peminjamanData.length}`,
      `Internal: ${peminjamanData.filter(x => x.tipe === 'Internal').length}`,
      `Eksternal: ${peminjamanData.filter(x => x.tipe === 'Eksternal').length}`,
      `Menunggu: ${peminjamanData.filter(x => x.statusPeminjaman === 'Menunggu Konfirmasi').length}`,
      `Disetujui: ${peminjamanData.filter(x => x.statusPeminjaman === 'Disetujui' || x.role === 'internal').length}`
    ];
    fullData.push([], summaryRow);
    
    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.aoa_to_sheet(fullData);
    
    ws['!cols'] = [
      { wch: 5 },  { wch: 25 }, { wch: 30 }, { wch: 25 }, { wch: 12 },
      { wch: 20 }, { wch: 15 }, { wch: 12 }, { wch: 12 }, { wch: 20 }, { wch: 12 }
    ];
    
    ws['!merges'] = [
      { s: { r: 0, c: 0 }, e: { r: 0, c: 10 } },
      { s: { r: 1, c: 0 }, e: { r: 1, c: 10 } },
      { s: { r: 2, c: 0 }, e: { r: 2, c: 10 } }
    ];
    
    XLSX.utils.book_append_sheet(wb, ws, 'Data Peminjaman');
    
    const filename = `Laporan_Peminjaman_${new Date().toISOString().split('T')[0]}.xlsx`;
    
    XLSX.writeFile(wb, filename);
    
    alert('✅ Laporan berhasil diexport ke Excel!\n\n' +
          `Total: ${peminjamanData.length} peminjaman\n` +
          `File: ${filename}`);
  };

  // ===== EVENT LISTENERS =====
  document.getElementById('pBookingDate')?.addEventListener('change', loadSchedule);

  document.getElementById('pBookingModal')?.addEventListener('click', e=>{
    if(e.target === e.currentTarget) closeBookingModal();
  });
  document.getElementById('pDetailedBookingModal')?.addEventListener('click', e=>{
    if(e.target === e.currentTarget) closeDetailedBookingModal();
  });
  document.getElementById('pExternalBookingModal')?.addEventListener('click', e=>{
    if(e.target === e.currentTarget) closeExternalBookingModal();
  });

  // ===== INIT =====
  renderTable();

})();
</script>