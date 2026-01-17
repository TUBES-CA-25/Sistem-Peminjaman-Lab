<?php
// views/admin/peminjaman/script.php
?>

<script>
  (function () {
    // ===== DATA FROM SERVER =====
    // Labs
    const LABS = [];
    <?php foreach ($data['labs'] as $lab): ?>
      LABS.push({ key: "<?= $lab['id'] ?>", name: "<?= $lab['nama_ruangan'] ?>" });
    <?php endforeach; ?>

    // Bookings (Real Peminjaman Data)
    // Mapping DB columns to JS object structure expected by existing functions
    // or refactoring functions to match DB columns.
    // DB: nama_peminjam, user_id, tipe, status, etc.
    // JS Expected: name, email, instansi, role, tanggal, status, lab, waktuMulai...
    const peminjamanData = [];
    <?php foreach ($data['bookings'] as $booking): ?>
      peminjamanData.push({
        id: "<?= $booking['id'] ?>",
        name: "<?= $booking['nama_peminjam'] ?: ($booking['user_nama'] ?? '-') ?>",
        email: "<?= $booking['user_email'] ?? '-' ?>",
        instansi: "<?= $booking['kegiatan'] ?>",
        role: "<?= $booking['tipe'] ?>", // internal/eksternal
        tanggal: "<?= $booking['tanggal_peminjaman'] ?>", // YYYY-MM-DD
        status: "<?= $booking['status'] == 'menunggu' ? 'nonaktif' : 'aktif' ?>", // mapped for UI badge color logic
        username: "-", // Not used
        lab: "<?= $booking['lab_nama'] ?>",
        labId: "<?= $booking['lab_id'] ?>",
        waktuMulai: "<?= substr($booking['jam_mulai'], 0, 5) ?>",
        waktuSelesai: "<?= substr($booking['jam_selesai'], 0, 5) ?>",
        statusPeminjaman: "<?= ucfirst($booking['status']) ?>", // Menunggu, Disetujui
        tipe: "<?= ucfirst($booking['tipe']) ?>"
      });
    <?php endforeach; ?>

    // Fixed Schedule (For conflict checking)
    // DB structure: lab_id, hari, jam_mulai, jam_selesai, mata_kuliah
    // JS Expected: { senin: { startup: [], ... } }
    // We need to transform this on client side or serve it ready.
    // Let's transform it here.
    const rawFixed = <?= json_encode($data['fixed_schedules']); ?>;
    const fixedSchedule = {};

    // Init empty arrays for weeks/labs
    ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'].forEach(d => {
      fixedSchedule[d] = {};
      LABS.forEach(l => fixedSchedule[d][l.name] = []); // Use Name as key to match existing logic?? 
      // Wait, current JS uses keys like 'startup', 'iot'.
      // But now we have dynamic IDs. 
      // Let's change existing logic to use Lab Names or IDs as keys.
      // Using Lab Names is easier IF names are unique.
      // Or better, use IDs. 
      // Let's stick to using Lab Names for visualization keys if possible, or refactor everything to IDs.
      // Refactoring to IDs is safer.
      LABS.forEach(l => fixedSchedule[d][l.key] = []);
    });

    rawFixed.forEach(item => {
      const d = (item.hari || '').toLowerCase(); // Force lowercase: 'Senin' -> 'senin'
      const l = item.lab_id; // ID
      if (fixedSchedule[d] && fixedSchedule[d][l]) {
        fixedSchedule[d][l].push({
          start: item.jam_mulai.substring(0, 5),
          end: item.jam_selesai.substring(0, 5),
          title: item.mata_kuliah
        });
      }
    });

    const dayNames = ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
    const DAY_RANGE = { start: "07:00", end: "18:20" };

    // ===== HELPER FUNCTIONS =====

    function toMin(hhmm) {
      const [h, m] = hhmm.split(":").map(Number);
      return h * 60 + m;
    }

    function toHHMM(mins) {
      const h = String(Math.floor(mins / 60)).padStart(2, "0");
      const m = String(mins % 60).padStart(2, "0");
      return `${h}:${m}`;
    }

    // Cek bentrok dengan ANY booking (Internal OR Eksternal)
    function cekBentrokAny(tanggal, labIdKey, jamMulai, jamSelesai) {
      // Filter bookings on this date and lab
      const relevantBookings = peminjamanData.filter(item =>
        item.tanggal === tanggal &&
        item.labId == labIdKey
      );

      for (const booking of relevantBookings) {
        const bookStart = toMin(booking.waktuMulai);
        const bookEnd = toMin(booking.waktuSelesai);
        const slotStart = toMin(jamMulai);
        const slotEnd = toMin(jamSelesai);

        // if booking overlaps slot
        if (bookStart < slotEnd && bookEnd > slotStart) {
          return true;
        }
      }

      return false;
    }

    // Get bookings for rendering
    function getBookingsForLab(tanggal, labIdKey) {
      return peminjamanData
        .filter(item =>
          item.tanggal === tanggal &&
          item.labId == labIdKey
        )
        .map(item => ({
          ...item, // keep all props
          start: item.waktuMulai,
          end: item.waktuSelesai,
          title: item.instansi || item.name // Display title
        }));
    }

    // Compute free intervals
    function computeFreeIntervals(dayKey, labIdKey) {
      // "Busy" includes Fixed Schedule AND Bookings?
      // Actually, user wants to be able to book "anywhere".
      // If we define "Available" gaps, we usually subtract everything.
      // BUT, if user can book over Fixed Schedule, Fixed Schedule shouldn't "consume" the free space for the purpose of clicking "Add"?
      // However, for visual clarity, usually "Available" is shown in empty spaces.
      // Fixed Scedule slots are shown explicitly.
      // Bookings are shown explicitly.
      // I will stick to: Free Intervals based on Fixed Schedule. 
      // And Fixed Schedule slots are clickable to Add Booking.
      // Bookings basically overlay.

      const busy = (fixedSchedule[dayKey]?.[labIdKey] || []).map(ev => ({ start: toMin(ev.start), end: toMin(ev.end) }));
      const dayStart = toMin(DAY_RANGE.start);
      const dayEnd = toMin(DAY_RANGE.end);

      const sorted = busy
        .filter(x => x.end > dayStart && x.start < dayEnd)
        .map(x => ({ start: Math.max(x.start, dayStart), end: Math.min(x.end, dayEnd) }))
        .sort((a, b) => a.start - b.start);

      const merged = [];
      for (const b of sorted) {
        if (!merged.length || merged[merged.length - 1].end < b.start) merged.push({ ...b });
        else merged[merged.length - 1].end = Math.max(merged[merged.length - 1].end, b.end);
      }

      const free = [];
      let cur = dayStart;
      for (const m of merged) {
        if (cur < m.start) free.push({ start: cur, end: m.start });
        cur = Math.max(cur, m.end);
      }
      if (cur < dayEnd) free.push({ start: cur, end: dayEnd });

      return free.filter(x => (x.end - x.start) >= 15);
    }

    // ===== RENDER TABLE =====
    function renderTable() {
      const tbody = document.getElementById('pTableBody');
      tbody.innerHTML = '';

      // Sort logic removed for brevity, assume controller sorted

      peminjamanData.forEach((item) => {
        const tr = document.createElement('tr');

        const peminjamHTML = `
        <div class="p-user-name">${item.name}</div>
        <div class="p-user-email">${item.email}</div>
        <div class="p-user-instansi">${item.instansi}</div>
        <div class="p-user-role ${item.role === 'eksternal' ? 'p-role-eksternal' :
            item.role === 'internal' ? 'p-role-internal' :
              'p-role-admin'}">${item.role}</div>
      `;

        // const statusPeminjaman = item.role === 'internal' ? 'Disetujui' : item.statusPeminjaman; 
        // Logic handled by backend now
        const statusPeminjaman = item.statusPeminjaman;

        const statusClass = statusPeminjaman.toLowerCase().includes('menunggu') ? 'p-status-nonaktif' : 'p-status-aktif';
        const tipeClass = item.tipe.toLowerCase() === 'eksternal' ? 'p-eksternal' : (item.tipe.toLowerCase() === 'internal' ? 'p-internal' : 'p-admin');

        let actionButtons = '';
        const id = item.id;

        // Admin can Edit/Approve/Delete everything.
        // But for Internal, maybe just Delete?
        // For External, Approve/Delete.

        if (item.role === 'internal') {
          actionButtons = `
          <button type="button" class="p-act p-del" title="Hapus" onclick="hapusPeminjaman(${id})">
            <i class="fas fa-times"></i>
          </button>
        `;
        } else {
          // Eksternal or Admin
          actionButtons = `
          <!-- No Edit for now -->
          <button type="button" class="p-act p-check" title="Approve" onclick="approvePeminjaman(${id})">
            <i class="fas fa-check"></i>
          </button>
          <button type="button" class="p-act p-del" title="Hapus" onclick="hapusPeminjaman(${id})">
            <i class="fas fa-times"></i>
          </button>
        `;
        }

        // If approved, hide approve button?
        if (statusPeminjaman === 'Disetujui') {
          // Replace approve button with nothing or disabled
          actionButtons = actionButtons.replace('title="Approve"', 'title="Approve" disabled style="opacity:0.3;cursor:default;"');
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

    window.approvePeminjaman = function (id) {
      if (confirm('Approve peminjaman ini?')) {
        const form = document.createElement('form');
        form.method = 'POST'; form.action = '<?= BASE_URL ?>peminjaman';
        const a = document.createElement('input'); a.type = 'hidden'; a.name = 'action'; a.value = 'approve';
        const i = document.createElement('input'); i.type = 'hidden'; i.name = 'id'; i.value = id;
        form.appendChild(a); form.appendChild(i);
        document.body.appendChild(form);
        form.submit();
      }
    };

    window.hapusPeminjaman = function (id) {
      if (confirm('Hapus booking ini?')) {
        const form = document.createElement('form');
        form.method = 'POST'; form.action = '<?= BASE_URL ?>peminjaman';
        const a = document.createElement('input'); a.type = 'hidden'; a.name = 'action'; a.value = 'delete';
        const i = document.createElement('input'); i.type = 'hidden'; i.name = 'id'; i.value = id;
        form.appendChild(a); form.appendChild(i);
        document.body.appendChild(form);
        form.submit();
      }
    };

    // ===== MODAL FUNCTIONS =====

    // Open Main Booking Modal
    window.openBookingModal = function () {
      const modal = document.getElementById('pBookingModal');
      const dateInput = document.getElementById('pBookingDate');
      const today = new Date().toISOString().split('T')[0];
      dateInput.value = today;

      modal.classList.add('active');
      loadSchedule();
    };

    window.closeBookingModal = function () {
      document.getElementById('pBookingModal').classList.remove('active');
    };

    // (toggleBookingType Removed)

    // Load Schedule Grid
    function loadSchedule() {
      const dateInput = document.getElementById('pBookingDate');
      if (!dateInput.value) return; // Guard
      const selectedDate = new Date(dateInput.value + 'T00:00:00');
      const dayName = dayNames[selectedDate.getDay()];

      const grid = document.getElementById('pLabsGrid');
      grid.innerHTML = '';

      LABS.forEach(lab => {
        // lab.key is ID now, fixedSchedule uses ID as key
        const praktikum = fixedSchedule[dayName]?.[lab.key] || [];
        const freeIntervals = computeFreeIntervals(dayName, lab.key);

        const card = document.createElement('div');
        card.className = 'p-lab-card';

        let slots = '';

        // Render praktikum tetap atau tergeser
        praktikum.forEach(slot => {
          // Check overlap with ANY booking
          const adaBentrok = cekBentrokAny(dateInput.value, lab.key, slot.start, slot.end);
          const slotClass = adaBentrok ? 'tergeser' : 'praktikum';
          const slotLabel = adaBentrok ? 'Jadwal Tergeser' : 'Praktikum Tetap';
          
          // Make fixed schedule clickable too!
          slots += `
          <div class="p-slot ${slotClass}" style="cursor:pointer;" onclick="handleSlotClick('${dateInput.value}', '${dayName}', '${lab.key}', '${lab.name}', '${slot.start}', '${slot.end}')">
            <span class="p-slot-label">${slotLabel} ${slot.start}–${slot.end}</span>
            <div class="p-slot-sub">${slot.title}</div>
          </div>
        `;
        });

        // Render ALL bookings (Internal, Eksternal, Admin)
        const bookings = getBookingsForLab(dateInput.value, lab.key);
        bookings.forEach(booking => {
          // Determine class based on role
          // booking.role -> internal, eksternal, admin
          let slotClass = 'internal'; // default
          let slotLabel = 'Peminjaman Internal';

          if (booking.role === 'eksternal') {
             slotClass = 'eksternal'; 
             slotLabel = 'Peminjaman Eksternal';
          } else if (booking.role === 'admin') {
             slotClass = 'admin'; // Need CSS for this? Or reuse internal? Admin usually distinct.
             slotLabel = 'Maintenance / Admin';
          }
          
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
          <div class="p-slot available" onclick="handleSlotClick('${dateInput.value}', '${dayName}', '${lab.key}', '${lab.name}', '${toHHMM(interval.start)}', '${toHHMM(interval.end)}')">
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


    // ===== EXTERNAL MODAL (NOW MULTI-LAB MANUAL) =====
    const pExternalModal = document.getElementById('pExternalBookingModal');
    const pExternalForm = document.getElementById('pExternalBookingForm'); // ID changed in modal.php to pExternalBookingForm? Yes.
    const externalLabTimesBody = document.getElementById('externalLabTimes');

    window.openExternalBookingModal = function (tanggal) {
      pExternalForm.reset();
      // If tanggal provided (e.g. valid date string), use it. Else today.
      const today = new Date().toISOString().split('T')[0];
      const t = (typeof tanggal === 'string' && tanggal) ? tanggal : today;
      
      document.getElementById('externalTanggalMulai').value = t;
      document.getElementById('externalTanggalSelesai').value = t;
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

    window.closeExternalBookingModal = function () {
      pExternalModal.classList.remove('active');
    };

    window.savePeminjamanEksternal = function (event) {
      event.preventDefault();
      const form = event.target;

      let tanggalMulai = form.externalTanggalMulai.value;
      let tanggalSelesai = form.externalTanggalSelesai.value;
      let instansiKegiatan = form.instansiKegiatan.value.trim();
      let catatan = form.catatanOpsional.value.trim();
      let tipe = form.tipe.value; // Get selected role

      // Basic Validation
      if (tanggalMulai > tanggalSelesai) {
        alert('Tanggal Mulai harus sama atau sebelum Tanggal Selesai.');
        return false;
      }
      if (instansiKegiatan === '') {
        alert('Nama Instansi / Kegiatan wajib diisi.');
        return false;
      }

      let labsToBook = [];
      LABS.forEach(lab => {
        const aktif = form[`aktif_${lab.key}`].checked;
        const mulai = form[`mulai_${lab.key}`].value;
        const selesai = form[`selesai_${lab.key}`].value;
        if (aktif) {
          if (mulai >= selesai) {
            alert(`Jam Mulai harus sebelum Jam Selesai pada lab ${lab.name}.`);
            return false; // Stop form submission
          }
          labsToBook.push({ labId: lab.key, mulai, selesai });
        }
      });

      if (labsToBook.length === 0) {
        alert('Pilih minimal satu laboratorium yang aktif.');
        return false;
      }

      // Generate dates
      let dates = [];
      let curr = new Date(tanggalMulai + 'T00:00:00'); 
      let end = new Date(tanggalSelesai + 'T00:00:00');
      while (curr <= end) {
        dates.push(new Date(curr).toISOString().split('T')[0]);
        curr.setDate(curr.getDate() + 1);
      }

      // Use Fetch API
      const promises = [];

      dates.forEach(d => {
        labsToBook.forEach(l => {
          const formData = new FormData();
          formData.append('action', 'create');
          formData.append('tipe', tipe); // Use selected tipe
          formData.append('ajax', '1');
          formData.append('tanggal', d);
          formData.append('lab', l.labId);
          formData.append('jamMulai', l.mulai);
          formData.append('jamSelesai', l.selesai);
          formData.append('kegiatan', instansiKegiatan);
          formData.append('catatan', catatan);
          formData.append('nama_peminjam', instansiKegiatan); // Or add specific field if needed

          promises.push(
            fetch('<?= BASE_URL ?>peminjaman', {
              method: 'POST',
              body: formData
            }).then(response => response.json())
              .then(data => data.success)
              .catch(err => false)
          );
        });
      });

      Promise.all(promises).then(results => {
        const allSuccess = results.every(r => r === true);
        if (allSuccess) {
          alert('✅ Peminjaman berhasil disimpan. Halaman akan dimuat ulang.');
        } else {
          alert('⚠️ Beberapa peminjaman gagal disimpan (Mungkin bentrok).');
        }
        window.location.reload();
      });

      return false;
    };

    window.editPeminjamanEksternal = function (id) {
      // Fetch the booking data for the given ID
      fetch(`<?= BASE_URL ?>peminjaman?action=get&id=${id}`)
        .then(response => response.json())
        .then(item => {
          if (!item) {
            alert('Data peminjaman tidak ditemukan.');
            return;
          }

          pExternalForm.reset();

          document.getElementById('externalTanggalMulai').value = item.tanggal_peminjaman; // use DB col name
          document.getElementById('externalTanggalSelesai').value = item.tanggal_peminjaman;
          document.getElementById('instansiKegiatan').value = item.kegiatan || item.nama_peminjam || '';
          document.getElementById('catatanOpsional').value = item.catatan || '';

          externalLabTimesBody.innerHTML = '';
          LABS.forEach(lab => {
            const isActive = (lab.key === item.lab_id);
            const checkedAttr = isActive ? 'checked' : '';
            const jamMulai = isActive ? item.jam_mulai.substring(0, 5) : '07:00';
            const jamSelesai = isActive ? item.jam_selesai.substring(0, 5) : '12:00';

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

          // Not fully implementing UPDATE yet in Controller, so this might just show data.
          // But user can delete and recreate.
          alert("Mode Edit hanya menampilkan data saat ini. \nSilakan hapus booking lama dan buat baru jika ingin mengubah secara drastis.");

          pExternalModal.classList.add('active');
        })
        .catch(error => {
          console.error('Error fetching booking data:', error);
          alert('Gagal memuat data peminjaman.');
        });
    };

    window.approvePeminjaman = function (id) {
      if (confirm(`Approve peminjaman dengan ID ${id}?`)) {
        const formData = new FormData();
        formData.append('action', 'approve');
        formData.append('ajax', '1');
        formData.append('id', id);

        fetch('<?= BASE_URL ?>peminjaman', {
          method: 'POST',
          body: formData
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert('✅ Peminjaman disetujui!');
              window.location.reload();
            } else {
              alert(`❌ Gagal: ${data.message || 'Error'}`);
            }
          })
          .catch(error => alert('Error connnection'));
      }
    };

    window.hapusPeminjaman = function (id) {
      if (confirm(`Hapus peminjaman dengan ID ${id}?`)) {
        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('ajax', '1');
        formData.append('id', id);

        fetch('<?= BASE_URL ?>peminjaman', {
          method: 'POST',
          body: formData
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert('✅ Peminjaman dihapus!');
              window.location.reload();
            } else {
              alert(`❌ Gagal: ${data.message || 'Error'}`);
            }
          })
          .catch(error => alert('Error connection'));
      }
    };

    // ===== EXPORT REPORT =====
    window.exportReport = function () {
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
        { wch: 5 }, { wch: 25 }, { wch: 30 }, { wch: 25 }, { wch: 12 },
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

    document.getElementById('pBookingModal')?.addEventListener('click', e => {
      if (e.target === e.currentTarget) closeBookingModal();
    });
    document.getElementById('pDetailedBookingModal')?.addEventListener('click', e => {
      if (e.target === e.currentTarget) closeDetailedBookingModal();
    });
    document.getElementById('pExternalBookingModal')?.addEventListener('click', e => {
      if (e.target === e.currentTarget) closeExternalBookingModal();
    });

    // ===== INIT =====
    renderTable();

  })();
</script>