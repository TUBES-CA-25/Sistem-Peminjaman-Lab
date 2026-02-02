/**
 * PeminjamanApp.Utils
 * Helper functions for Peminjaman module
 */

window.PeminjamanApp = window.PeminjamanApp || {};

window.PeminjamanApp.Utils = {
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
