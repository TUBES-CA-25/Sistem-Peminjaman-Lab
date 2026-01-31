/**
 * PeminjamanApp
 * Refactored modular script for Admin Peminjaman
 * Handles Data, Core Logic, UI Rendering, and User Actions.
 * 
 * Depends on Global Variables defined in script.php:
 * - window.PeminjamanConfig
 * - window.PeminjamanData
 */

const PeminjamanApp = (function () {
    // =========================================
    // 1. CONFIG & DATA
    // =========================================
    // Read from Globals
    const Config = window.PeminjamanConfig || {
        baseUrl: '',
        dayNames: ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'],
        dayRange: { start: "07:00", end: "18:20" }
    };

    const Data = window.PeminjamanData || {
        labs: [],
        bookings: [],
        fixedSchedule: {}
    };

    // =========================================
    // 2. UTILS
    // =========================================
    const Utils = {
        toMin: (hhmm) => {
            if (!hhmm) return 0;
            const [h, m] = hhmm.split(":").map(Number);
            return h * 60 + m;
        },
        toHHMM: (mins) => {
            const h = String(Math.floor(mins / 60)).padStart(2, "0");
            const m = String(mins % 60).padStart(2, "0");
            return `${h}:${m}`;
        },
        formatDateID: (date) => {
            return new Date(date).toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        },
        // Swal Helper
        showSuccess: (msg) => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: msg,
                timer: 1500,
                showConfirmButton: false
            });
        },
        showError: (msg) => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: msg
            });
        },
        showConfirm: (title, text, confirmBtnText = 'Ya', cancelBtnText = 'Batal') => {
            return Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: confirmBtnText,
                cancelButtonText: cancelBtnText
            });
        }
    };

    // =========================================
    // 3. CORE LOGIC
    // =========================================
    const Core = {
        // Check conflict with ANY booking (Internal OR Eksternal)
        getBentrokBooking: (tanggal, labIdKey, jamMulai, jamSelesai) => {
            const relevantBookings = Data.bookings.filter(item =>
                item.tanggal === tanggal &&
                item.labId == labIdKey
            );

            for (const booking of relevantBookings) {
                const bookStart = Utils.toMin(booking.waktuMulai);
                const bookEnd = Utils.toMin(booking.waktuSelesai);
                const slotStart = Utils.toMin(jamMulai);
                const slotEnd = Utils.toMin(jamSelesai);

                // if booking overlaps slot
                if (bookStart < slotEnd && bookEnd > slotStart) {
                    return booking; // Return the full booking object
                }
            }
            return null;
        },

        // Get bookings for rendering in GRID (Exclude Tergeser)
        getBookingsForLab: (tanggal, labIdKey) => {
            return Data.bookings
                .filter(item =>
                    item.tanggal === tanggal &&
                    item.labId == labIdKey &&
                    item.statusPeminjaman !== 'Tergeser'
                )
                .map(item => ({
                    ...item,
                    start: item.waktuMulai,
                    end: item.waktuSelesai,
                    title: item.instansi || item.name
                }));
        },

        // Compute free intervals
        computeFreeIntervals: (dayKey, labIdKey, dateStr) => {
            // 1. Fixed Schedule
            const fixedBusy = (Data.fixedSchedule[dayKey]?.[labIdKey] || []).map(ev => ({
                start: Utils.toMin(ev.start),
                end: Utils.toMin(ev.end)
            }));

            // 2. Actual Bookings (Excluding 'Tergeser' & 'Ditolak')
            const bookingsBusy = Data.bookings
                .filter(b =>
                    b.labId == labIdKey &&
                    b.tanggal === dateStr &&
                    !['tergeser', 'ditolak'].includes(b.statusPeminjaman.toLowerCase())
                )
                .map(b => ({
                    start: Utils.toMin(b.waktuMulai),
                    end: Utils.toMin(b.waktuSelesai)
                }));

            // Combine
            const busy = [...fixedBusy, ...bookingsBusy];
            const dayStart = Utils.toMin(Config.dayRange.start);
            const dayEnd = Utils.toMin(Config.dayRange.end);

            const sorted = busy
                .filter(x => x.end > dayStart && x.start < dayEnd)
                .map(x => ({
                    start: Math.max(x.start, dayStart),
                    end: Math.min(x.end, dayEnd)
                }))
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
    };

    // =========================================
    // 4. UI RENDERERS
    // =========================================
    const UI = {
        renderTable: () => {
            const tbody = document.getElementById('pTableBody');
            const totalEl = document.getElementById('totalBookings');
            if (!tbody) return;
            tbody.innerHTML = '';

            if (totalEl) totalEl.innerText = `Total: ${Data.bookings.length}`;

            Data.bookings.forEach((item, index) => {
                const tr = document.createElement('tr');
                tr.className = 'border-bottom';

                // 1. Column Pemohon (User)
                // Match Users/Pengajuan style: Name (Bold Dark), Email (Muted with icon), Status/Role (Small Primary)
                const peminjamHTML = `
            <div class="fw-bold text-dark">${item.name}</div>
            <div class="small text-muted"><i class="fas fa-envelope me-1"></i> ${item.email}</div>
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
                    // Eksternal/Admin: Edit, Delete (Approve removed as per request)
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
                    // Destroy if existing instance to prevent duplication on re-render
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

                    // Modification: Disable click on Fixed Schedule slots
                    // User request: "kenapa saya bisa menekan jadwal praktikum tetap"
                    const cursorStyle = 'cursor:default;';
                    const onClickAttr = ''; // No action on click

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
            document.getElementById('instansiKegiatan').value = item.kegiatan || item.nama_peminjam || '';
            document.getElementById('catatanOpsional').value = item.catatan || '';
            pExternalForm.elements['tipe'].value = item.tipe || 'eksternal';

            externalLabTimesBody.innerHTML = '';
            Data.labs.forEach(lab => {
                const isActive = (lab.key == item.lab_id);
                const checkedAttr = isActive ? 'checked' : '';
                const jamMulai = isActive ? item.jam_mulai.substring(0, 5) : '07:00';
                const jamSelesai = isActive ? item.jam_selesai.substring(0, 5) : '12:00';

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

    // =========================================
    // 5. ACTIONS & HANDLERS
    // =========================================
    const Actions = {
        approve: function (id) {
            Utils.showConfirm('Approve Booking?', `Setujui peminjaman ID ${id}?`)
                .then((result) => {
                    if (result.isConfirmed) {
                        const formData = new FormData();
                        formData.append('action', 'approve');
                        formData.append('ajax', '1');
                        formData.append('id', id);

                        fetch(Config.baseUrl + '/peminjaman', { method: 'POST', body: formData })
                            .then(r => r.json())
                            .then(data => {
                                if (data.success) {
                                    Utils.showSuccess('Peminjaman disetujui!');
                                    setTimeout(() => window.location.reload(), 1500);
                                } else Utils.showError(`Gagal: ${data.message || 'Error'}`);
                            })
                            .catch(e => Utils.showError('Error connection'));
                    }
                });
        },

        delete: function (id) {
            Utils.showConfirm('Hapus Booking?', `Anda yakin ingin menghapus data ID ${id}?`, 'Ya, Hapus!')
                .then((result) => {
                    if (result.isConfirmed) {
                        const formData = new FormData();
                        formData.append('action', 'delete');
                        formData.append('ajax', '1');
                        formData.append('id', id);

                        fetch(Config.baseUrl + '/peminjaman', { method: 'POST', body: formData })
                            .then(r => r.json())
                            .then(data => {
                                if (data.success) {
                                    Utils.showSuccess('Peminjaman dihapus!');
                                    setTimeout(() => window.location.reload(), 1500);
                                } else Utils.showError(`Gagal: ${data.message || 'Error'}`);
                            })
                            .catch(e => Utils.showError('Error connection'));
                    }
                });
        },

        openExternalEdit: function (id) {
            fetch(`${Config.baseUrl}/peminjaman?action=get&id=${id}`)
                .then(r => r.json())
                .then(item => {
                    if (!item) {
                        Utils.showError('Data tidak ditemukan');
                        return;
                    }
                    UI.initExternalEdit(item);
                })
                .catch(e => Utils.showError('Gagal load data: ' + e.message));
        },

        saveInternal: function (event) {
            event.preventDefault();
            const formData = new FormData(event.target);

            // --- VALIDASI TAMBAHAN ---
            const jamMulai = formData.get('jamMulai');
            const jamSelesai = formData.get('jamSelesai');
            const tanggal = formData.get('tanggal');

            // 1. Cek Jam Operasional
            if (jamMulai < "07:00" || jamSelesai > "18:20") {
                Utils.showError('Gagal: Jam operasional lab hanya dari 07:00 s/d 18:20.');
                return false;
            }

            // 1.5 Cek Logika Waktu (Mulai < Selesai)
            if (jamMulai >= jamSelesai) {
                Utils.showError('Gagal: Jam mulai harus lebih awal dari jam selesai.');
                return false;
            }

            // 2. Cek Backdate
            const bookingDateTime = new Date(tanggal + 'T' + jamMulai);
            const now = new Date();
            if (bookingDateTime < now) {
                Utils.showError('Gagal: Waktu booking sudah terlewat. Mohon pilih waktu yang valid.');
                return false;
            }
            // -------------------------

            formData.append('action', 'create');
            formData.append('ajax', '1');

            fetch(Config.baseUrl + '/peminjaman', { method: 'POST', body: formData })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        Utils.showSuccess('Peminjaman Internal berhasil disimpan!');
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        Utils.showError('Gagal: ' + (data.message || 'Stot tidak tersedia'));
                    }
                })
                .catch(e => Utils.showError('Error: ' + e.message));
            return false;
        },

        saveExternal: function (event) {
            event.preventDefault();
            const form = event.target;

            // --- Core Logic ---
            let tanggalMulai = form.externalTanggalMulai.value;
            let tanggalSelesai = form.externalTanggalSelesai.value;
            let instansi = form.instansiKegiatan.value.trim();

            if (tanggalMulai > tanggalSelesai) { Utils.showError('Tanggal Mulai melebihi Tanggal Selesai'); return false; }
            if (!instansi) { Utils.showError('Instansi/Kegiatan wajib diisi'); return false; }

            let labsToBook = [];
            Data.labs.forEach(lab => {
                const aktif = form[`aktif_${lab.key}`].checked;
                if (aktif) {
                    labsToBook.push({
                        labId: lab.key,
                        mulai: form[`mulai_${lab.key}`].value,
                        selesai: form[`selesai_${lab.key}`].value
                    });
                }
            });

            if (!labsToBook.length) { Utils.showError('Pilih minimal satu laboratorium'); return false; }

            const processBooking = async () => {
                let requestItems = [];
                let curr = new Date(tanggalMulai + 'T00:00:00');
                let end = new Date(tanggalSelesai + 'T00:00:00');

                while (curr <= end) {
                    const y = curr.getFullYear();
                    const m = String(curr.getMonth() + 1).padStart(2, '0');
                    const dt = String(curr.getDate()).padStart(2, '0');
                    const d = `${y}-${m}-${dt}`;

                    labsToBook.forEach(l => {
                        requestItems.push({
                            tanggal: d, lab: l.labId, jamMulai: l.mulai, jamSelesai: l.selesai,
                            kegiatan: instansi, catatan: form.catatanOpsional.value,
                            tipe: form.tipe.value, nama_peminjam: instansi
                        });
                    });
                    curr.setDate(curr.getDate() + 1);
                }

                const send = (item, override) => {
                    const fd = new FormData();
                    fd.append('action', form.elements['action']?.value || 'create');
                    fd.append('id', form.elements['id']?.value || '');
                    fd.append('ajax', '1');
                    if (override) fd.append('override', '1');
                    for (let k in item) fd.append(k, item[k]);

                    return fetch(Config.baseUrl + '/peminjaman', { method: 'POST', body: fd })
                        .then(r => r.json())
                        .then(d => ({ item, success: d.success, message: d.message || '' }))
                        .catch(e => ({ item, success: false, message: 'Conn Error' }));
                };

                // 1. Send all
                const results = await Promise.all(requestItems.map(i => send(i, false)));
                const failures = results.filter(r => !r.success);

                if (failures.length === 0) {
                    Utils.showSuccess('Peminjaman berhasil disimpan!');
                    setTimeout(() => window.location.reload(), 1500);
                    return;
                }

                const conflicts = failures.filter(r => r.message.toLowerCase().includes('bentrok'));
                if (conflicts.length > 0) {
                    const confirmMsg = `Ditemukan ${conflicts.length} jadwal bentrok. Override dan geser jadwal internal?`;

                    Utils.showConfirm('Jadwal Bentrok', confirmMsg, 'Ya, Override')
                        .then(async (res) => {
                            if (res.isConfirmed) {
                                const retries = await Promise.all(conflicts.map(r => send(r.item, true)));
                                if (retries.every(r => r.success)) {
                                    Utils.showSuccess('Sukses dengan Override!');
                                } else {
                                    Utils.showError('Masih ada error setelah override. Cek data.');
                                }
                                setTimeout(() => window.location.reload(), 1500);
                            } else {
                                Utils.showError('Override dibatalkan. Data lain mungkin tersimpan.');
                                setTimeout(() => window.location.reload(), 1500);
                            }
                        });
                } else {
                    // Show specific error from first failure if available
                    const msg = failures[0]?.message || `Gagal ${failures.length} booking. Cek validasi.`;
                    Utils.showError(msg);
                    // Do NOT reload, so user can fix the input
                }
            };
            processBooking();
            return false;
        },

        exportReport: function () {
            const header = [['LAPORAN DATA PEMINJAMAN'], [], ['No', 'Nama', 'Role', 'Lab', 'Tanggal', 'Jam', 'Status']];
            const rows = Data.bookings.map((b, i) => [i + 1, b.name, b.role, b.lab, b.tanggal, `${b.waktuMulai}-${b.waktuSelesai}`, b.statusPeminjaman]);
            if (typeof XLSX !== 'undefined') {
                const ws = XLSX.utils.aoa_to_sheet([...header, ...rows]);
                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'Laporan');
                XLSX.writeFile(wb, `Laporan_${new Date().toISOString().split('T')[0]}.xlsx`);
                Utils.showSuccess('Download dimulai...');
            } else {
                Utils.showError('Library XLSX belum dimuat');
            }
        }

    };

    // UiActions exposed for HTML inline calls
    const UiActions = {
        handleSlotClick: function (date, day, labId, labName, start, end) {
            const modal = document.getElementById('pDetailedBookingModal');
            if (modal.classList.contains('active')) return;

            document.getElementById('bookingDateDetail').value = date;
            document.getElementById('hariDetail').value = day.toUpperCase();
            document.getElementById('labDetail').value = labName;
            document.getElementById('labIdDetail').value = labId;
            document.getElementById('jamMulaiDetail').value = start;
            document.getElementById('jamSelesaiDetail').value = end;

            document.getElementById('slotKosongInfo').innerHTML = `<strong>Slot: ${start}-${end}</strong>`;
            modal.classList.add('active');
        }
    };

    // =========================================
    // INITIALIZATION
    // =========================================
    const init = () => {
        // 2. Render Initial UI
        UI.renderTable();

        // 3. Event Listeners
        document.getElementById('pBookingDate')?.addEventListener('change', UI.renderSchedule);

        const modal = document.getElementById('pBookingModal');
        if (modal) {
            modal.addEventListener('click', e => { if (e.target === modal) modal.classList.remove('active'); });
        }

        document.getElementById('pDetailedBookingModal')?.addEventListener('click', e => {
            if (e.target === e.currentTarget) document.getElementById('pDetailedBookingModal').classList.remove('active');
        });

        const extModal = document.getElementById('pExternalBookingModal');
        if (extModal) {
            extModal.addEventListener('click', e => { if (e.target === extModal) extModal.classList.remove('active'); });
        }
    };

    return {
        init,
        Data,
        Utils,
        Core,
        UI,
        Actions,
        UiActions
    };
})();

// Initialize when DOM Ready
document.addEventListener('DOMContentLoaded', PeminjamanApp.init);

// Expose Globals for HTML onclick attributes
window.PeminjamanApp = PeminjamanApp;
window.hapusPeminjaman = PeminjamanApp.Actions.delete;
window.approvePeminjaman = PeminjamanApp.Actions.approve;
window.editPeminjamanEksternal = PeminjamanApp.Actions.openExternalEdit;
window.exportReport = PeminjamanApp.Actions.exportReport;
window.savePeminjamanEksternal = PeminjamanApp.Actions.saveExternal;
window.savePeminjaman = PeminjamanApp.Actions.saveInternal;
window.handleSingleLabEdit = function () { };

// Modal Globals
window.openBookingModal = function () {
    document.getElementById('pBookingModal').classList.add('active');
    const d = document.getElementById('pBookingDate');
    if (d && !d.value) d.value = new Date().toISOString().split('T')[0];
    PeminjamanApp.UI.renderSchedule();
};
window.closeBookingModal = function () {
    document.getElementById('pBookingModal').classList.remove('active');
};
window.openExternalBookingModal = PeminjamanApp.UI.initExternalModal;
window.closeExternalBookingModal = function () {
    document.getElementById('pExternalBookingModal').classList.remove('active');
};
window.closeDetailedBookingModal = function () {
    document.getElementById('pDetailedBookingModal').classList.remove('active');
};
