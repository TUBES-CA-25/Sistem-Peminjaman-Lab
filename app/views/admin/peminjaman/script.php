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
        tanggal: "<?= $booking['tanggal'] ?? $booking['tanggal_peminjaman'] ?? '' ?>", // YYYY-MM-DD (with fallback)
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

    // Get bookings for rendering in GRID (Exclude Tergeser)
    function getBookingsForLab(tanggal, labIdKey) {
      return peminjamanData
        .filter(item =>
          item.tanggal === tanggal &&
          item.labId == labIdKey &&
          item.statusPeminjaman !== 'Tergeser' // Hide shifted bookings from grid
        )
        .map(item => ({
          ...item, // keep all props
          start: item.waktuMulai,
          end: item.waktuSelesai,
          title: item.instansi || item.name // Display title
        }));
    }

    // Compute free intervals
    // Compute free intervals
    function computeFreeIntervals(dayKey, labIdKey, dateStr) {
      // 1. Get Fixed Schedule
      const fixedBusy = (fixedSchedule[dayKey]?.[labIdKey] || []).map(ev => ({ start: toMin(ev.start), end: toMin(ev.end) }));

      // 2. Get Actual Bookings on that date (Excluding 'Tergeser' & 'Ditolak')
      const bookingsBusy = peminjamanData
        .filter(b =>
          b.labId == labIdKey &&
          b.tanggal === dateStr &&
          !['tergeser', 'ditolak'].includes(b.statusPeminjaman.toLowerCase())
        )
        .map(b => ({ start: toMin(b.waktuMulai), end: toMin(b.waktuSelesai) }));

      // Combine
      const busy = [...fixedBusy, ...bookingsBusy];

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

        let statusClass;
        if (statusPeminjaman.toLowerCase().includes('menunggu')) {
          statusClass = 'p-status-nonaktif';
        } else if (statusPeminjaman.toLowerCase().includes('tergeser')) {
          statusClass = 'p-status-tergeser'; // Red for shifted
        } else if (statusPeminjaman.toLowerCase().includes('ditolak')) {
          statusClass = 'p-status-nonaktif';
        } else { // Default for 'Disetujui' or others
          statusClass = 'p-status-aktif';
        }
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
          <button type="button" class="p-act p-edit" title="Edit" onclick="editPeminjamanEksternal(${id})">
            <i class="fas fa-edit"></i>
          </button>
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
        const freeIntervals = computeFreeIntervals(dayName, lab.key, dateInput.value);

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
      let tipe = form.tipe.value; // 'eksternal' (hidden input)

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
            return false;
          }
          labsToBook.push({ labId: lab.key, mulai, selesai });
        }
      });

      if (labsToBook.length === 0) {
        alert('Pilih minimal satu laboratorium yang aktif.');
        return false;
      }

      // Generate List of Requests
      let requestItems = [];
      let curr = new Date(tanggalMulai + 'T00:00:00');
      let end = new Date(tanggalSelesai + 'T00:00:00');

      while (curr <= end) {
        // Fix: Use local date components avoids timezone offset issues from toISOString()
        const y = curr.getFullYear();
        const m = String(curr.getMonth() + 1).padStart(2, '0');
        const dt = String(curr.getDate()).padStart(2, '0');
        const d = `${y}-${m}-${dt}`;
        labsToBook.forEach(l => {
          requestItems.push({
            tanggal: d,
            lab: l.labId,
            jamMulai: l.mulai,
            jamSelesai: l.selesai,
            kegiatan: instansiKegiatan,
            catatan: catatan,
            tipe: tipe,
            nama_peminjam: instansiKegiatan
          });
        });
        curr.setDate(curr.getDate() + 1);
      }

      // Helper Fetch Function
      const sendBooking = (item, override = false) => {
        const formData = new FormData();

        // Determine Action: create or update?
        // Check if main form has ID
        const formAction = form.elements['action'] ? form.elements['action'].value : 'create';
        const formId = form.elements['id'] ? form.elements['id'].value : '';

        formData.append('action', formAction); // 'create' or 'update'
        if (formId) formData.append('id', formId);

        formData.append('ajax', '1');

        // Append all item props
        for (const key in item) {
          formData.append(key, item[key]);
        }

        if (override) {
          formData.append('override', '1');
        }

        return fetch('<?= BASE_URL ?>/peminjaman', {
          method: 'POST',
          body: formData
        })
          .then(response => response.json())
          .then(data => ({ item, success: data.success, message: data.message || '' }))
          .catch(err => ({ item, success: false, message: 'Connection Error' }));
      };

      // 1. Initial Attempt (No Override)
      const promises = requestItems.map(item => sendBooking(item, false));

      Promise.all(promises).then(results => {
        const failures = results.filter(r => !r.success);

        // If everything success
        if (failures.length === 0) {
          alert('✅ Semua peminjaman berhasil disimpan!');
          window.location.reload();
          return;
        }

        // Analyze Conflicts
        const conflicts = failures.filter(r => r.message.toLowerCase().includes('bentrok'));
        const otherErrors = failures.filter(r => !r.message.toLowerCase().includes('bentrok'));

        if (conflicts.length > 0) {
          const confirmMsg = `⚠️ Ditemukan ${conflicts.length} jadwal bentrok dengan booking Internal!\n` +
            `(${conflicts[0].item.tanggal} ${conflicts[0].item.jamMulai}-${conflicts[0].item.jamSelesai}...)\n\n` +
            `Apakah Anda ingin MENGGESER (Override) jadwal internal tersebut?\n` +
            `Jadwal internal akan diubah statusnya menjadi 'Tergeser'.`;

          if (confirm(confirmMsg)) {
            // 2. Retry with Override
            const retries = conflicts.map(r => sendBooking(r.item, true));

            Promise.all(retries).then(retryResults => {
              const retryFailures = retryResults.filter(r => !r.success);

              if (retryFailures.length === 0 && otherErrors.length === 0) {
                alert('✅ Sukses! Jadwal bentrok berhasil digeser dan disimpan.');
              } else {
                let msg = '⚠️ Proses selesai dengan catatan:\n';
                if (otherErrors.length > 0) msg += `- ${otherErrors.length} error teknis/validasi.\n`;
                if (retryFailures.length > 0) msg += `- ${retryFailures.length} gagal override (mungkin bentrok sesama prioritas/fixed).`;
                alert(msg);
              }
              window.location.reload();
            });
            return;
          }
        }

        // If conflicts exist but user cancelled override, OR only other errors exist
        alert(`❌ Terjadi kesalahan pada ${failures.length} peminjaman.\n` +
          (conflicts.length > 0 ? '(Anda membatalkan override jadwal bentrok)' : '(Cek koneksi atau validasi data)'));
        // Don't reload immediately so user can see form? Or reload to show partial success?
        // Reload is safer to reflect partial bookings.
        window.location.reload();
      });

      return false;
    };

    window.editPeminjamanEksternal = function (id) {
      // Fetch the booking data for the given ID
      fetch(`<?= BASE_URL ?>/peminjaman?action=get&id=${id}`)
        .then(response => {
          if (!response.ok) {
            throw new Error('Server Error: ' + response.statusText);
          }
          return response.text().then(text => {
            try {
              return JSON.parse(text);
            } catch (e) {
              throw new Error('Invalid JSON: ' + text.substring(0, 100)); // Show start of text
            }
          });
        })
        .then(item => {
          if (!item) {
            alert('Data peminjaman tidak ditemukan.');
            return;
          }

          pExternalForm.reset();

          // Set Hidden Action & ID
          const actionInput = document.createElement('input');
          actionInput.type = 'hidden'; actionInput.name = 'action'; actionInput.value = 'update';
          // Check if exists, update value
          if (pExternalForm.elements['action']) pExternalForm.elements['action'].value = 'update';
          else pExternalForm.appendChild(actionInput);

          const idInput = document.createElement('input');
          idInput.type = 'hidden'; idInput.name = 'id'; idInput.value = item.id;
          if (pExternalForm.elements['id']) pExternalForm.elements['id'].value = item.id;
          else pExternalForm.appendChild(idInput);

          document.getElementById('pExternalModalTitle').textContent = 'Edit Peminjaman';
          document.getElementById('btnSaveExternal').textContent = 'Update Peminjaman';

          document.getElementById('externalTanggalMulai').value = item.tanggal_peminjaman;
          document.getElementById('externalTanggalSelesai').value = item.tanggal_peminjaman;
          document.getElementById('instansiKegiatan').value = item.kegiatan || item.nama_peminjam || '';
          document.getElementById('catatanOpsional').value = item.catatan || '';

          // Set Tipe
          pExternalForm.elements['tipe'].value = item.tipe || 'eksternal';

          externalLabTimesBody.innerHTML = '';
          LABS.forEach(lab => {
            const isActive = (lab.key == item.lab_id); // Strict match for key? item.lab_id is string from DB
            const checkedAttr = isActive ? 'checked' : '';
            // If active, use stored times. If not, default 07:00-12:00
            const jamMulai = isActive ? item.jam_mulai.substring(0, 5) : '07:00';
            const jamSelesai = isActive ? item.jam_selesai.substring(0, 5) : '12:00';

            const html = `
            <tr>
              <td>${lab.name}</td>
              <td style="text-align:center;">
                  <input type="checkbox" name="aktif_${lab.key}" ${checkedAttr} 
                  class="lab-checkbox" 
                  onchange="handleSingleLabEdit(this)" />
              </td>
              <td><input type="time" name="mulai_${lab.key}" value="${jamMulai}" /></td>
              <td><input type="time" name="selesai_${lab.key}" value="${jamSelesai}" /></td>
            </tr>
          `;
            externalLabTimesBody.insertAdjacentHTML('beforeend', html);
          });

          pExternalModal.classList.add('active');
        })
        .catch(error => {
          console.error('Error fetching booking data:', error);
          alert('Gagal memuat data: ' + error.message);
        });
    };

    // Helper to ensure single lab selection in edit mode
    window.handleSingleLabEdit = function (checkbox) {
      // Optional: if needed to enforce 1 lab
    };

    // Reset Form when opening 'Add' modal, to clear Update IDs
    const originalOpenExternal = window.openExternalBookingModal;
    window.openExternalBookingModal = function (tanggal) {
      // Reset ID and Action to Create
      if (pExternalForm.elements['id']) pExternalForm.elements['id'].value = '';
      if (pExternalForm.elements['action']) pExternalForm.elements['action'].value = 'create';

      document.getElementById('pExternalModalTitle').textContent = 'Tambah Peminjaman (Admin)';
      document.getElementById('btnSaveExternal').textContent = 'Simpan Peminjaman';

      originalOpenExternal(tanggal);
    };


    window.approvePeminjaman = function (id) {
      if (confirm(`Approve peminjaman dengan ID ${id}?`)) {
        const formData = new FormData();
        formData.append('action', 'approve');
        formData.append('ajax', '1');
        formData.append('id', id);

        fetch('<?= BASE_URL ?>/peminjaman', {
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

        fetch('<?= BASE_URL ?>/peminjaman', {
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
    // OPEN DETAIL BOOKING (SINGLE SLOT)
    window.handleSlotClick = function (date, day, labId, labName, start, end) {
      const modal = document.getElementById('pDetailedBookingModal');
      const form = document.getElementById('pBookingForm');

      // Prevent clicking if modal is already open
      if (modal.classList.contains('active')) return;

      // Set values
      document.getElementById('bookingDateDetail').value = date;
      document.getElementById('hariDetail').value = day.toUpperCase();
      document.getElementById('labDetail').value = labName;
      document.getElementById('labIdDetail').value = labId; // Hidden ID
      document.getElementById('jamMulaiDetail').value = start;
      document.getElementById('jamSelesaiDetail').value = end;

      // Default info
      document.getElementById('slotKosongInfo').innerHTML = `<strong>Slot kosong: ${start}-${end}</strong><br><small>Pilih jam mulai/selesai di dalam slot kosong.</small>`;

      // Open modal
      modal.classList.add('active');
    };

    window.closeDetailedBookingModal = function () {
      document.getElementById('pDetailedBookingModal').classList.remove('active');
    };

    // SAVE PEMINJAMAN (SINGLE - INTERNAL / REGULER)
    window.savePeminjaman = function (event) {
      event.preventDefault();
      const form = event.target;

      // Prepare Form Data
      const formData = new FormData(form);
      formData.append('action', 'create');
      formData.append('ajax', '1');
      // Tipe is now hidden 'internal', so it's auto appended.

      fetch('<?= BASE_URL ?>/peminjaman', {
        method: 'POST',
        body: formData
      })
        .then(response => {
          if (!response.ok) throw new Error(response.statusText);
          return response.text().then(text => {
            try { return JSON.parse(text); }
            catch (e) { throw new Error('Server Return Invalid JSON'); }
          });
        })
        .then(data => {
          if (data.success) {
            alert('✅ Peminjaman Internal berhasil disimpan!');
            window.location.reload();
          } else {
            // NO OVERRIDE OFFER HERE. Strict check.
            alert('❌ Gagal: ' + (data.message || 'Slot tidak tersedia / Bentrok.'));
          }
        })
        .catch(err => {
          console.error(err);
          alert('❌ Terjadi kesalahan: ' + err.message);
        });

      return false;
    };

    document.getElementById('pExternalBookingModal')?.addEventListener('click', e => {
      if (e.target === e.currentTarget) closeExternalBookingModal();
    });

    // ===== INIT =====
    renderTable();

  })();
</script>