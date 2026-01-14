<?php
// app/views/internal/booking/script.php
// JavaScript untuk internal booking
?>

<script>
// ===== MODAL 1: Schedule Modal (Custom) =====
function openScheduleModal() {
    document.getElementById('scheduleModal').classList.add('active');
}

function closeScheduleModal() {
    document.getElementById('scheduleModal').classList.remove('active');
}

// ===== MODAL 2: Booking Form Modal (Bootstrap) =====
let bookingModalInstance = null;

function openBookingModal(labName, jamMulai, jamSelesai) {
    // Set form values
    document.getElementById('bookingLab').value = labName;
    document.getElementById('jamMulai').value = jamMulai.replace(':', '.');
    document.getElementById('jamSelesai').value = jamSelesai.replace(':', '.');
    document.getElementById('slotInfoText').textContent = 'Slot kosong: ' + jamMulai + '-' + jamSelesai;
    
    // Get or create Bootstrap modal instance
    const bookingModalEl = document.getElementById('bookingModal');
    
    if (!bookingModalInstance) {
        bookingModalInstance = new bootstrap.Modal(bookingModalEl, {
            backdrop: 'static',
            keyboard: false
        });
    }
    
    // Show the modal
    bookingModalInstance.show();
}

function changeDate(newDate) {
    window.location.href = '<?= BASE_URL ?>internal/booking?date=' + newDate;
}

function submitBooking() {
    const formData = {
        tanggal: document.getElementById('bookingDate').value,
        lab: document.getElementById('bookingLab').value,
        jamMulai: document.getElementById('jamMulai').value,
        jamSelesai: document.getElementById('jamSelesai').value,
        peminjam: document.getElementById('namaPeminjam').value,
        kegiatan: document.getElementById('namaKegiatan').value
    };
    
    // Validate
    if (!formData.peminjam || !formData.kegiatan) {
        alert('Mohon isi nama peminjam dan nama kegiatan!');
        return;
    }
    
    console.log('Booking Data:', formData);
    alert('Peminjaman berhasil disimpan!\n\nLab: ' + formData.lab + '\nTanggal: ' + formData.tanggal + '\nWaktu: ' + formData.jamMulai + ' - ' + formData.jamSelesai);
    
    // Close Bootstrap modal
    if (bookingModalInstance) {
        bookingModalInstance.hide();
    }
    
    document.getElementById('bookingForm').reset();
}

// IMPORTANT: Only close schedule modal when clicking exactly on the modal backdrop (the semi-transparent area)
// NOT when clicking inside the modal card or when booking modal opens
document.getElementById('scheduleModal').addEventListener('click', function(e) {
    // Only close if clicking directly on the backdrop (the p-modal element itself)
    // Not on any child elements
    if (e.target === this) {
        closeScheduleModal();
    }
});

// Close schedule modal on Escape key (only if booking modal is not open)
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const bookingModal = document.getElementById('bookingModal');
        // Only close schedule modal if booking modal is not visible
        if (!bookingModal.classList.contains('show')) {
            closeScheduleModal();
        }
    }
});
</script>
