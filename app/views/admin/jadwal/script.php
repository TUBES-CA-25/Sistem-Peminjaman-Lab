<?php
// views/admin/jadwal/script.php
?>

<script>
(function(){
  // ===== LAB LIST =====
  const LABS = {
    startup: "Startup Lab",
    iot: "IoT Lab",
    micro: "Micro Lab",
    cv: "Computer Vision Lab",
    ds: "Data Science Lab",
    cn: "Computer Networking Lab",
    mm: "Multimedia Lab",
    riset: "Riset 2 Lab"
  };

  const HARI_LIST = {
    senin: "Senin",
    selasa: "Selasa",
    rabu: "Rabu",
    kamis: "Kamis",
    jumat: "Jumat",
    sabtu: "Sabtu",
    minggu: "Minggu"
  };

  // ===== FIXED SCHEDULE DATA =====
  const fixedSchedule = {
    senin: {
      startup: [
        { start:"10:30", end:"14:20", mataKuliah:"Pemrograman", kelas:"A1" },
        { start:"14:30", end:"18:20", mataKuliah:"P. Pemrograman", kelas:"A2" }
      ],
      iot: [
        { start:"14:30", end:"18:20", mataKuliah:"P. Pemrograman", kelas:"A4" }
      ],
      micro: [
        { start:"07:00", end:"09:30", mataKuliah:"Microcontroller", kelas:"A1,A2,A3" },
        { start:"12:10", end:"13:00", mataKuliah:"Microcontroller", kelas:"A7" },
        { start:"13:00", end:"15:30", mataKuliah:"Microcontroller", kelas:"A8" }
      ],
      cv: [
        { start:"09:40", end:"12:10", mataKuliah:"Struktur Data", kelas:"A7" },
        { start:"13:00", end:"15:30", mataKuliah:"Struktur Data", kelas:"A5" }
      ],
      ds: [
        { start:"07:00", end:"09:30", mataKuliah:"Basis Data II", kelas:"B4" },
        { start:"09:40", end:"12:10", mataKuliah:"Struktur Data", kelas:"A8" },
        { start:"13:00", end:"15:30", mataKuliah:"Struktur Data", kelas:"A6" }
      ],
      cn: [],
      mm: [],
      riset: []
    },
    selasa: { startup:[], iot:[], micro:[], cv:[], ds:[], cn:[], mm:[], riset:[] },
    rabu: { startup:[], iot:[], micro:[], cv:[], ds:[], cn:[], mm:[], riset:[] },
    kamis: { startup:[], iot:[], micro:[], cv:[], ds:[], cn:[], mm:[], riset:[] },
    jumat: { startup:[], iot:[], micro:[], cv:[], ds:[], cn:[], mm:[], riset:[] },
    sabtu: { startup:[], iot:[], micro:[], cv:[], ds:[], cn:[], mm:[], riset:[] },
    minggu: { startup:[], iot:[], micro:[], cv:[], ds:[], cn:[], mm:[], riset:[] }
  };

  // ===== HELPER FUNCTIONS =====
  function toMin(hhmm){ 
    const [h,m]=hhmm.split(":").map(Number); 
    return h*60+m; 
  }

  // ===== FLATTEN SCHEDULE FOR TABLE =====
  function getFlatSchedule() {
    const flat = [];
    let id = 0;
    
    Object.keys(fixedSchedule).forEach(hari => {
      Object.keys(fixedSchedule[hari]).forEach(labKey => {
        fixedSchedule[hari][labKey].forEach(schedule => {
          flat.push({
            id: id++,
            hari,
            lab: labKey,
            start: schedule.start,
            end: schedule.end,
            mataKuliah: schedule.mataKuliah,
            kelas: schedule.kelas
          });
        });
      });
    });
    
    return flat;
  }

  // ===== RENDER TABLE =====
  function renderTable(){
    const tbody = document.getElementById('jadwalTableBody');
    const data = getFlatSchedule();
    
    if(data.length === 0){
      tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding:40px; color:#94a3b8;">Belum ada jadwal praktikum. Klik tombol "Tambah Jadwal Baru" untuk membuat jadwal.</td></tr>';
      return;
    }
    
    tbody.innerHTML = '';
    
    data.forEach(item => {
      const tr = document.createElement('tr');
      
      tr.innerHTML = `
        <td>
          <span style="font-weight:900; color:#0f172a;">${HARI_LIST[item.hari]}</span>
        </td>
        <td>
          <span style="font-weight:800; color:#1F45AC;">${LABS[item.lab]}</span>
        </td>
        <td>
          <div class="p-dt">
            <div class="p-time"><i class="far fa-clock"></i> ${item.start} - ${item.end}</div>
          </div>
        </td>
        <td>
          <span style="font-weight:700; color:#334155;">${item.mataKuliah}</span>
        </td>
        <td>
          <span class="p-badge p-internal">${item.kelas}</span>
        </td>
        <td style="text-align:center;">
          <div class="p-actions" style="justify-content:center;">
            <button type="button" class="p-act p-edit" title="Edit" onclick="editJadwal(${item.id})">
              <i class="fas fa-edit"></i>
            </button>
            <button type="button" class="p-act p-del" title="Hapus" onclick="hapusJadwal(${item.id})">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </td>
      `;
      
      tbody.appendChild(tr);
    });
  }

  // ===== MODAL FUNCTIONS =====
  
  // Open Schedule Modal
  window.openScheduleModal = function(){
    const modal = document.getElementById('pScheduleModal');
    const form = document.getElementById('pScheduleForm');
    const title = document.getElementById('scheduleModalTitle');
    
    form.reset();
    document.getElementById('scheduleEditIndex').value = '';
    title.textContent = 'Tambah Jadwal Praktikum Tetap';
    
    modal.classList.add('active');
  };

  window.closeScheduleModal = function(){
    document.getElementById('pScheduleModal').classList.remove('active');
  };

  // Save Jadwal Praktikum
  window.saveJadwalPraktikum = function(event){
    event.preventDefault();
    const form = event.target;

    const editIndex = form.editIndex.value;
    const hari = form.hari.value;
    const lab = form.lab.value;
    const jamMulai = form.jamMulai.value;
    const jamSelesai = form.jamSelesai.value;
    const mataKuliah = form.mataKuliah.value.trim();
    const kelas = form.kelas.value.trim();

    // Validasi jam
    if(jamMulai >= jamSelesai){
      alert('Jam Selesai harus lebih besar dari Jam Mulai.');
      return false;
    }

    // Cek bentrok dengan jadwal lain di hari dan lab yang sama
    const existingSchedules = fixedSchedule[hari]?.[lab] || [];
    for(let i = 0; i < existingSchedules.length; i++){
      const schedule = existingSchedules[i];
      
      // Skip jika sedang edit jadwal yang sama
      if(editIndex !== ''){
        const flatData = getFlatSchedule();
        const editItem = flatData[parseInt(editIndex)];
        if(editItem && editItem.hari === hari && editItem.lab === lab && 
           editItem.start === schedule.start && editItem.end === schedule.end){
          continue;
        }
      }
      
      const existStart = toMin(schedule.start);
      const existEnd = toMin(schedule.end);
      const newStart = toMin(jamMulai);
      const newEnd = toMin(jamSelesai);
      
      if(newStart < existEnd && newEnd > existStart){
        alert(`Bentrok dengan jadwal: ${schedule.mataKuliah} (${kelas}) ${schedule.start}-${schedule.end}`);
        return false;
      }
    }

    // Mode Edit
    if(editIndex !== ''){
      const flatData = getFlatSchedule();
      const editItem = flatData[parseInt(editIndex)];
      
      if(editItem){
        // Hapus jadwal lama
        const oldSchedules = fixedSchedule[editItem.hari][editItem.lab];
        const oldIndex = oldSchedules.findIndex(s => 
          s.start === editItem.start && 
          s.end === editItem.end && 
          s.mataKuliah === editItem.mataKuliah
        );
        if(oldIndex !== -1){
          oldSchedules.splice(oldIndex, 1);
        }
        
        // Tambah jadwal baru
        if(!fixedSchedule[hari]) fixedSchedule[hari] = {};
        if(!fixedSchedule[hari][lab]) fixedSchedule[hari][lab] = [];
        
        fixedSchedule[hari][lab].push({
          start: jamMulai,
          end: jamSelesai,
          mataKuliah: mataKuliah,
          kelas: kelas
        });
        
        alert(`✅ Jadwal praktikum berhasil diupdate!\n\nHari: ${HARI_LIST[hari]}\nLab: ${LABS[lab]}\nJam: ${jamMulai} - ${jamSelesai}\nMata Kuliah: ${mataKuliah}\nKelas: ${kelas}`);
      }
    } 
    // Mode Tambah
    else {
      if(!fixedSchedule[hari]) fixedSchedule[hari] = {};
      if(!fixedSchedule[hari][lab]) fixedSchedule[hari][lab] = [];
      
      fixedSchedule[hari][lab].push({
        start: jamMulai,
        end: jamSelesai,
        mataKuliah: mataKuliah,
        kelas: kelas
      });

      alert(`✅ Jadwal praktikum berhasil ditambahkan!\n\nHari: ${HARI_LIST[hari]}\nLab: ${LABS[lab]}\nJam: ${jamMulai} - ${jamSelesai}\nMata Kuliah: ${mataKuliah}\nKelas: ${kelas}`);
    }
    
    closeScheduleModal();
    renderTable();

    return false;
  };

  // Edit Jadwal
  window.editJadwal = function(id){
    const flatData = getFlatSchedule();
    const item = flatData[id];
    
    if(!item) return;
    
    const modal = document.getElementById('pScheduleModal');
    const form = document.getElementById('pScheduleForm');
    const title = document.getElementById('scheduleModalTitle');
    
    // Set form values
    document.getElementById('scheduleEditIndex').value = id;
    document.getElementById('scheduleHari').value = item.hari;
    document.getElementById('scheduleLab').value = item.lab;
    document.getElementById('scheduleJamMulai').value = item.start;
    document.getElementById('scheduleJamSelesai').value = item.end;
    document.getElementById('scheduleMataKuliah').value = item.mataKuliah;
    document.getElementById('scheduleKelas').value = item.kelas;
    
    title.textContent = 'Edit Jadwal Praktikum';
    modal.classList.add('active');
  };

  // Hapus Jadwal
  window.hapusJadwal = function(id){
    const flatData = getFlatSchedule();
    const item = flatData[id];
    
    if(!item) return;
    
    if(confirm(`Hapus jadwal ${item.mataKuliah} (${item.kelas}) di ${LABS[item.lab]} hari ${HARI_LIST[item.hari]}?`)){
      const schedules = fixedSchedule[item.hari][item.lab];
      const index = schedules.findIndex(s => 
        s.start === item.start && 
        s.end === item.end && 
        s.mataKuliah === item.mataKuliah
      );
      
      if(index !== -1){
        schedules.splice(index, 1);
        renderTable();
        alert('Jadwal berhasil dihapus.');
      }
    }
  };

  // ===== EXPORT REPORT =====
  window.exportJadwalReport = function() {
    const flatData = getFlatSchedule();
    
    if(flatData.length === 0){
      alert('Tidak ada data jadwal untuk diexport.');
      return;
    }
    
    const headerInfo = [
      ['LAPORAN JADWAL PRAKTIKUM TETAP'],
      ['IC-LABS - Innovation Center Laboratories'],
      [`Tanggal Export: ${new Date().toLocaleDateString('id-ID', { 
        day: 'numeric', 
        month: 'long', 
        year: 'numeric' 
      })}`],
      [],
      ['No', 'Hari', 'Laboratorium', 'Jam Mulai', 'Jam Selesai', 'Mata Kuliah', 'Kelas']
    ];
    
    const dataRows = flatData.map((item, index) => [
      index + 1,
      HARI_LIST[item.hari],
      LABS[item.lab],
      item.start,
      item.end,
      item.mataKuliah,
      item.kelas
    ]);
    
    const fullData = [...headerInfo, ...dataRows];
    
    const summaryRow = [
      '',
      `Total Jadwal: ${flatData.length}`,
      `Total Lab Terpakai: ${new Set(flatData.map(x => x.lab)).size}`,
      '',
      '',
      '',
      ''
    ];
    fullData.push([], summaryRow);
    
    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.aoa_to_sheet(fullData);
    
    ws['!cols'] = [
      { wch: 5 }, { wch: 12 }, { wch: 25 }, { wch: 12 }, 
      { wch: 12 }, { wch: 30 }, { wch: 15 }
    ];
    
    ws['!merges'] = [
      { s: { r: 0, c: 0 }, e: { r: 0, c: 6 } },
      { s: { r: 1, c: 0 }, e: { r: 1, c: 6 } },
      { s: { r: 2, c: 0 }, e: { r: 2, c: 6 } }
    ];
    
    XLSX.utils.book_append_sheet(wb, ws, 'Jadwal Praktikum');
    
    const filename = `Jadwal_Praktikum_${new Date().toISOString().split('T')[0]}.xlsx`;
    
    XLSX.writeFile(wb, filename);
    
    alert('✅ Laporan jadwal berhasil diexport ke Excel!\n\n' +
          `Total: ${flatData.length} jadwal\n` +
          `File: ${filename}`);
  };

  // ===== EVENT LISTENERS =====
  document.getElementById('pScheduleModal')?.addEventListener('click', e=>{
    if(e.target === e.currentTarget) closeScheduleModal();
  });

  // ===== INIT =====
  renderTable();

})();
</script>