/**
 * PeminjamanApp.Actions
 * User interactions and API calls
 */

window.PeminjamanApp = window.PeminjamanApp || {};

window.PeminjamanApp.Actions = {
    approve: function (id) {
        const Config = window.PeminjamanApp.Config;
        const Utils = window.PeminjamanApp.Utils;

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
        const Config = window.PeminjamanApp.Config;
        const Utils = window.PeminjamanApp.Utils;

        Utils.showConfirm('Hapus Booking?', `Anda yakin ingin menghapus data?`, 'Ya, Hapus!')
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
        const Config = window.PeminjamanApp.Config;
        const Utils = window.PeminjamanApp.Utils;
        const UI = window.PeminjamanApp.UI;

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
        const Config = window.PeminjamanApp.Config;
        const Utils = window.PeminjamanApp.Utils;

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
                    Utils.showError('Gagal: ' + (data.message || 'Slot tidak tersedia'));
                }
            })
            .catch(e => Utils.showError('Error: ' + e.message));
        return false;
    },

    saveExternal: function (event) {
        const Config = window.PeminjamanApp.Config;
        const Utils = window.PeminjamanApp.Utils;
        const Data = window.PeminjamanApp.Data;

        event.preventDefault();
        const form = event.target;

        // --- Core Logic ---
        let tanggalMulai = form.externalTanggalMulai.value;
        let tanggalSelesai = form.externalTanggalSelesai.value;
        let namaPeminjam = form.instansiKegiatan.value.trim();
        let namaKegiatan = form.namaKegiatan.value.trim();

        if (tanggalMulai > tanggalSelesai) { Utils.showError('Tanggal Mulai melebihi Tanggal Selesai'); return false; }
        if (!namaPeminjam) { Utils.showError('Nama Peminjam wajib diisi'); return false; }
        if (!namaKegiatan) { Utils.showError('Nama Kegiatan wajib diisi'); return false; }

        // Retrieve Existing Map (for Updates/Deletes)
        const existingLabMap = form.dataset.existingLabMap ? JSON.parse(form.dataset.existingLabMap) : {};
        const action = form.elements['action']?.value || 'create';

        // Collect active labs to book (Update or Create)
        let labsToBook = [];
        Data.labs.forEach(lab => {
            const aktif = form[`aktif_${lab.key}`].checked;
            const jamMulai = form[`mulai_${lab.key}`].value;
            const jamSelesai = form[`selesai_${lab.key}`].value;

            // VALIDASI JAM OPERASIONAL CLIENT-SIDE
            if (aktif) {
                if (jamMulai < "07:00" || jamSelesai > "18:20") {
                    Utils.showError(`Lab ${lab.name}: Jam harus 07:00 - 18:20`);
                    throw new Error('Validation Error'); // Stop processing
                }
                if (jamMulai >= jamSelesai) {
                    Utils.showError(`Lab ${lab.name}: Jam mulai harus < selesai`);
                    throw new Error('Validation Error');
                }

                labsToBook.push({
                    labId: lab.key,
                    mulai: jamMulai,
                    selesai: jamSelesai
                });
            }
        });

        if (!labsToBook.length) { Utils.showError('Pilih minimal satu laboratorium'); return false; }

        const processBooking = async () => {
            let requestItems = [];
            let curr = new Date(tanggalMulai + 'T00:00:00');
            let end = new Date(tanggalSelesai + 'T00:00:00');

            // 1. Identify Deletions (Unchecked labs that were previously part of the group)
            // Only relevant if action is 'update' and we have a map
            const processedLabIds = labsToBook.map(l => l.labId.toString());
            const idsToDelete = [];
            if (action === 'update') {
                for (const [labId, bookingId] of Object.entries(existingLabMap)) {
                    if (!processedLabIds.includes(labId.toString())) {
                        idsToDelete.push(bookingId);
                    }
                }
            }

            // 2. Prepare Create/Update Requests
            while (curr <= end) {
                const y = curr.getFullYear();
                const m = String(curr.getMonth() + 1).padStart(2, '0');
                const dt = String(curr.getDate()).padStart(2, '0');
                const d = `${y}-${m}-${dt}`;

                labsToBook.forEach(l => {
                    let itemAction = 'create';
                    let itemId = '';

                    if (existingLabMap[l.labId] && d === tanggalMulai) { // Only map ID if date matches start
                        itemAction = 'update';
                        itemId = existingLabMap[l.labId];
                    }

                    requestItems.push({
                        realAction: itemAction,
                        id: itemId,
                        tanggal: d, lab: l.labId, jamMulai: l.mulai, jamSelesai: l.selesai,
                        kegiatan: namaKegiatan,
                        tipe: form.tipe.value, nama_peminjam: namaPeminjam
                    });
                });
                curr.setDate(curr.getDate() + 1);
            }

            const send = (item, override) => {
                const fd = new FormData();
                fd.append('action', item.realAction);
                if (item.realAction === 'update' || item.realAction === 'delete') fd.append('id', item.id);

                fd.append('ajax', '1');
                if (override) fd.append('override', '1');

                if (item.realAction !== 'delete') {
                    fd.append('lab', item.lab);
                    fd.append('tanggal', item.tanggal);
                    fd.append('jamMulai', item.jamMulai);
                    fd.append('jamSelesai', item.jamSelesai);
                    fd.append('kegiatan', item.kegiatan);
                    fd.append('tipe', item.tipe);
                    fd.append('nama_peminjam', item.nama_peminjam);
                }

                return fetch(Config.baseUrl + '/peminjaman', { method: 'POST', body: fd })
                    .then(r => r.json())
                    .then(d => ({ item, success: d.success, message: d.message || '' }))
                    .catch(e => ({ item, success: false, message: 'Conn Error' }));
            };

            // 2.5 Execute Deletions First
            if (idsToDelete.length > 0) {
                const deleteReqs = idsToDelete.map(delId => ({ realAction: 'delete', id: delId }));
                await Promise.all(deleteReqs.map(i => send(i, false)));
            }

            // 3. Send all Create/Updates
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
                const msg = failures[0]?.message || `Gagal ${failures.length} booking. Cek validasi.`;
                Utils.showError(msg);
            }
        };

        try {
            processBooking();
        } catch (err) {
            // Validation error caught here
        }
        return false;
    },

    exportReport: function () {
        const Data = window.PeminjamanApp.Data;
        const Utils = window.PeminjamanApp.Utils;

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

window.PeminjamanApp.UiActions = {
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
