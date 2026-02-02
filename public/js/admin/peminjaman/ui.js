/**
 * PeminjamanApp.UI
 * UI Rendering logic for Peminjaman module
 */

window.PeminjamanApp = window.PeminjamanApp || {};

window.PeminjamanApp.UI = {
    renderTable: () => {
        const Data = window.PeminjamanApp.Data;
        const Utils = window.PeminjamanApp.Utils;

        const tbody = document.getElementById('pTableBody');
        const totalEl = document.getElementById('totalBookings');
        if (!tbody) return;
        tbody.innerHTML = '';

        if (totalEl) totalEl.innerText = `Total: ${Data.bookings.length}`;

        Data.bookings.forEach((item, index) => {
            const tr = document.createElement('tr');
            tr.className = 'border-bottom';

            // 1. Column Pemohon (User)
            const peminjamHTML = `
        <div class="fw-bold text-dark">${item.name}</div>
        <div class="small text-primary fw-bold mt-1">${item.instansi}</div>
      `;

            // 2. Column Status Badge
            let badgeClass = 'bg-secondary';
            const statusLower = item.statusPeminjaman.toLowerCase();
            if (statusLower.includes('disetujui') || statusLower === 'aktif') badgeClass = 'bg-success-subtle text-success';
            else if (statusLower.includes('menunggu')) badgeClass = 'bg-warning-subtle text-warning';
            else if (statusLower.includes('tergeser')) badgeClass = 'bg-danger-subtle text-danger';
            else if (statusLower.includes('ditolak') || statusLower === 'nonaktif') badgeClass = 'bg-danger-subtle text-danger';

            // 3. Column Tipe Badge
            let tipeBadgeClass = 'bg-secondary';
            if (item.tipe.toLowerCase() === 'eksternal') tipeBadgeClass = 'bg-primary-subtle text-primary';
            else if (item.tipe.toLowerCase() === 'internal') tipeBadgeClass = 'bg-warning-subtle text-dark';
            else if (item.tipe.toLowerCase() === 'admin') tipeBadgeClass = 'bg-dark-subtle text-dark';

            // 4. Action Buttons
            let actionButtons = '';
            const id = item.id;

            if (item.role === 'internal') {
                // Internal: Only Delete biasanya
                actionButtons = `
          <button type="button" class="btn-icon btn-delete" title="Hapus" onclick="PeminjamanApp.Actions.delete(${id})">
              <i class="fas fa-trash"></i>
          </button>
        `;
            } else {
                // Eksternal/Admin: Edit, Delete
                actionButtons = `
          <button type="button" class="btn-icon btn-edit" title="Edit" onclick="PeminjamanApp.Actions.openExternalEdit(${id})">
              <i class="fas fa-edit"></i>
          </button>
          <button type="button" class="btn-icon btn-delete" title="Hapus" onclick="PeminjamanApp.Actions.delete(${id})">
              <i class="fas fa-trash"></i>
          </button>
        `;
            }

            tr.innerHTML = `
        <td class="ps-4 fw-bold">${index + 1}</td>
        <td class="ps-4">${peminjamHTML}</td>
        <td class="fw-bold text-dark">${item.lab}</td>
        <td>
            <div class="small">
                <div class="mb-1"><span class="text-primary fw-bold"><i class="far fa-calendar me-1"></i></span> ${item.tanggal}</div>
                <div><span class="text-secondary fw-bold"><i class="far fa-clock me-1"></i></span> ${item.waktuMulai} - ${item.waktuSelesai}</div>
            </div>
        </td>
        <td><span class="badge rounded-pill ${badgeClass}">${item.statusPeminjaman}</span></td>
        <td><span class="badge rounded-pill ${tipeBadgeClass}">${item.tipe}</span></td>
        <td>
            <div class="d-flex align-items-center">
                ${actionButtons}
            </div>
        </td>
      `;
            tbody.appendChild(tr);
        });

        // Init Simple DataTables for dynamic content
        if (typeof simpleDatatables !== 'undefined') {
            const tableEl = document.getElementById('pTable');
            if (tableEl) {
                if (window.pTableInstance) {
                    window.pTableInstance.destroy();
                }
                window.pTableInstance = new simpleDatatables.DataTable(tableEl, {
                    perPage: 10,
                    perPageSelect: [10, 20, 50],
                    columns: [{ select: -1, sortable: false }]
                });
            }
        }
    },

    renderSchedule: () => {
        const Data = window.PeminjamanApp.Data;
        const Config = window.PeminjamanApp.Config;
        const Core = window.PeminjamanApp.Core;
        const Utils = window.PeminjamanApp.Utils;

        const dateInput = document.getElementById('pBookingDate');
        if (!dateInput || !dateInput.value) return;

        const selectedDate = new Date(dateInput.value + 'T00:00:00');
        const dayName = Config.dayNames[selectedDate.getDay()];
        const grid = document.getElementById('pLabsGrid');
        grid.innerHTML = '';

        Data.labs.forEach(lab => {
            const praktikum = Data.fixedSchedule[dayName]?.[lab.key] || [];
            const freeIntervals = Core.computeFreeIntervals(dayName, lab.key, dateInput.value);

            const card = document.createElement('div');
            card.className = 'p-lab-card';
            let slots = '';

            // Fixed Slots
            praktikum.forEach(slot => {
                const conflictBooking = Core.getBentrokBooking(dateInput.value, lab.key, slot.start, slot.end);
                const adaBentrok = !!conflictBooking;
                const slotClass = adaBentrok ? 'tergeser' : 'praktikum';
                const slotLabel = adaBentrok ? 'Jadwal Tergeser' : 'Praktikum Tetap';
                let overriddenText = adaBentrok ? `- Digeser oleh ${conflictBooking.instansi || conflictBooking.name}` : '';

                const cursorStyle = 'cursor:default;';
                const onClickAttr = '';

                slots += `
          <div class="p-slot ${slotClass}" style="${cursorStyle}" ${onClickAttr}>
            <span class="p-slot-label">${slotLabel} ${slot.start}–${slot.end}</span>
            <div class="p-slot-sub">${slot.title} ${overriddenText}</div>
          </div>
        `;
            });

            // Booking Slots
            const bookings = Core.getBookingsForLab(dateInput.value, lab.key);
            bookings.forEach(booking => {
                let slotClass = 'internal';
                let slotLabel = 'Peminjaman Internal';
                if (booking.role === 'eksternal') {
                    slotClass = 'eksternal';
                    slotLabel = 'Peminjaman Eksternal';
                } else if (booking.role === 'admin') {
                    slotClass = 'admin';
                    slotLabel = 'Maintenance / Admin';
                }

                slots += `
          <div class="p-slot ${slotClass}">
            <span class="p-slot-label">${slotLabel} ${booking.start}–${booking.end}</span>
            <div class="p-slot-sub">${booking.title}</div>
          </div>
        `;
            });

            // Empty Slots
            freeIntervals.forEach(interval => {
                slots += `
          <div class="p-slot available" onclick="PeminjamanApp.UiActions.handleSlotClick('${dateInput.value}', '${dayName}', '${lab.key}', '${lab.name}', '${Utils.toHHMM(interval.start)}', '${Utils.toHHMM(interval.end)}')">
            <span class="p-slot-label">+ Pinjam (Kosong ${Utils.toHHMM(interval.start)}–${Utils.toHHMM(interval.end)})</span>
          </div>
        `;
            });

            card.innerHTML = `<h3>${lab.name}</h3><div class="p-slot-list">${slots || '<div style="text-align:center;padding:22px;color:#94a3b8;font-weight:800;">Tidak ada jadwal tersedia</div>'}</div>`;
            grid.appendChild(card);
        });
    },

    initExternalModal: (tanggal) => {
        const Data = window.PeminjamanApp.Data;

        const pExternalModal = document.getElementById('pExternalBookingModal');
        const pExternalForm = document.getElementById('pExternalBookingForm');
        const externalLabTimesBody = document.getElementById('externalLabTimes');

        // Reset
        if (pExternalForm.elements['id']) pExternalForm.elements['id'].value = '';
        if (pExternalForm.elements['action']) pExternalForm.elements['action'].value = 'create';
        pExternalForm.reset();

        document.getElementById('pExternalModalTitle').textContent = 'Tambah Peminjaman (Admin)';
        document.getElementById('btnSaveExternal').textContent = 'Simpan Peminjaman';

        const today = new Date().toISOString().split('T')[0];
        const t = (typeof tanggal === 'string' && tanggal) ? tanggal : today;
        document.getElementById('externalTanggalMulai').value = t;
        document.getElementById('externalTanggalSelesai').value = t;

        externalLabTimesBody.innerHTML = '';
        Data.labs.forEach(lab => {
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
    },

    initExternalEdit: (item) => {
        const Data = window.PeminjamanApp.Data;

        const pExternalModal = document.getElementById('pExternalBookingModal');
        const pExternalForm = document.getElementById('pExternalBookingForm');
        const externalLabTimesBody = document.getElementById('externalLabTimes');

        pExternalForm.reset();

        let actionInput = pExternalForm.elements['action'];
        if (!actionInput) {
            actionInput = document.createElement('input'); actionInput.type = 'hidden'; actionInput.name = 'action'; pExternalForm.appendChild(actionInput);
        }
        actionInput.value = 'update';

        let idInput = pExternalForm.elements['id'];
        if (!idInput) {
            idInput = document.createElement('input'); idInput.type = 'hidden'; idInput.name = 'id'; pExternalForm.appendChild(idInput);
        }
        idInput.value = item.id;

        document.getElementById('pExternalModalTitle').textContent = 'Edit Peminjaman';
        document.getElementById('btnSaveExternal').textContent = 'Update Peminjaman';

        document.getElementById('externalTanggalMulai').value = item.tanggal_peminjaman;
        document.getElementById('externalTanggalSelesai').value = item.tanggal_peminjaman;
        document.getElementById('instansiKegiatan').value = item.nama_peminjam || '';
        document.getElementById('namaKegiatan').value = item.kegiatan || '';
        pExternalForm.elements['tipe'].value = item.tipe || 'eksternal';

        // --- GROUP DETECTION LOGIC ---
        const targetDate = item.tanggal_peminjaman;
        const targetName = (item.nama_peminjam || '').trim().toLowerCase();
        const targetActivity = (item.kegiatan || '').trim().toLowerCase();

        const siblingBookings = Data.bookings.filter(b =>
            b.tanggal === targetDate &&
            (b.name || '').trim().toLowerCase() === targetName &&
            (b.instansi || '').trim().toLowerCase() === targetActivity
        );

        const existingLabMap = {};
        siblingBookings.forEach(b => {
            existingLabMap[b.labId] = b.id;
        });

        existingLabMap[item.lab_id] = item.id;

        pExternalForm.dataset.existingLabMap = JSON.stringify(existingLabMap);

        externalLabTimesBody.innerHTML = '';
        Data.labs.forEach(lab => {
            const existingId = existingLabMap[lab.key];
            const isActive = !!existingId;
            const checkedAttr = isActive ? 'checked' : '';

            let jamMulai = '07:00';
            let jamSelesai = '12:00';

            if (isActive) {
                if (lab.key == item.lab_id) {
                    jamMulai = item.jam_mulai.substring(0, 5);
                    jamSelesai = item.jam_selesai.substring(0, 5);
                } else {
                    const sibling = siblingBookings.find(sb => sb.labId == lab.key);
                    if (sibling) {
                        jamMulai = sibling.waktuMulai;
                        jamSelesai = sibling.waktuSelesai;
                    }
                }
            }

            const html = `
        <tr>
          <td>${lab.name}</td>
          <td style="text-align:center;"><input type="checkbox" name="aktif_${lab.key}" ${checkedAttr} class="lab-checkbox" /></td>
          <td><input type="time" name="mulai_${lab.key}" value="${jamMulai}" /></td>
          <td><input type="time" name="selesai_${lab.key}" value="${jamSelesai}" /></td>
        </tr>
      `;
            externalLabTimesBody.insertAdjacentHTML('beforeend', html);
        });
        pExternalModal.classList.add('active');
    }
};
