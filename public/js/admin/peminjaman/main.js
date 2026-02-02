/**
 * PeminjamanApp.Main
 * Entry point and Initialization
 */

window.PeminjamanApp = window.PeminjamanApp || {};

window.PeminjamanApp.init = () => {
    // 1. Initialize Config & Data from Code injection (script.php)
    window.PeminjamanApp.Config = window.PeminjamanConfig || {
        baseUrl: '',
        dayNames: ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'],
        dayRange: { start: "07:00", end: "18:20" }
    };

    window.PeminjamanApp.Data = window.PeminjamanData || {
        labs: [],
        bookings: [],
        fixedSchedule: {}
    };

    const UI = window.PeminjamanApp.UI;

    // 2. Render Initial UI
    if (UI && UI.renderTable) {
        UI.renderTable();
    }

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

// Initialize when DOM Ready
document.addEventListener('DOMContentLoaded', window.PeminjamanApp.init);

// Expose Globals for HTML onclick attributes
// This is necessary because onclick="hapusPeminjaman(...)" looks for global functions
window.hapusPeminjaman = (id) => window.PeminjamanApp.Actions.delete(id);
window.approvePeminjaman = (id) => window.PeminjamanApp.Actions.approve(id);
window.editPeminjamanEksternal = (id) => window.PeminjamanApp.Actions.openExternalEdit(id);
window.exportReport = () => window.PeminjamanApp.Actions.exportReport();
window.savePeminjamanEksternal = (e) => window.PeminjamanApp.Actions.saveExternal(e);
window.savePeminjaman = (e) => window.PeminjamanApp.Actions.saveInternal(e);
window.handleSingleLabEdit = function () { };

// Modal Globals
window.openBookingModal = function () {
    document.getElementById('pBookingModal').classList.add('active');
    const d = document.getElementById('pBookingDate');
    if (d && !d.value) d.value = new Date().toISOString().split('T')[0];
    if (window.PeminjamanApp.UI) window.PeminjamanApp.UI.renderSchedule();
};
window.closeBookingModal = function () {
    document.getElementById('pBookingModal').classList.remove('active');
};
window.openExternalBookingModal = (d) => window.PeminjamanApp.UI.initExternalModal(d);
window.closeExternalBookingModal = function () {
    document.getElementById('pExternalBookingModal').classList.remove('active');
};
window.closeDetailedBookingModal = function () {
    document.getElementById('pDetailedBookingModal').classList.remove('active');
};
