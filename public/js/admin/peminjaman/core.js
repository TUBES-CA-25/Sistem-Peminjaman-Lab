/**
 * PeminjamanApp.Core
 * Business Logic for Peminjaman module
 */

window.PeminjamanApp = window.PeminjamanApp || {};

window.PeminjamanApp.Core = {
    // Check conflict with ANY booking (Internal OR Eksternal)
    getBentrokBooking: (tanggal, labIdKey, jamMulai, jamSelesai) => {
        const Data = window.PeminjamanApp.Data;
        const Utils = window.PeminjamanApp.Utils;

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
        const Data = window.PeminjamanApp.Data;
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
                title: (item.instansi ? item.instansi + ' - ' : '') + item.name
            }));
    },

    // Compute free intervals
    computeFreeIntervals: (dayKey, labIdKey, dateStr) => {
        const Data = window.PeminjamanApp.Data;
        const Config = window.PeminjamanApp.Config;
        const Utils = window.PeminjamanApp.Utils;

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
