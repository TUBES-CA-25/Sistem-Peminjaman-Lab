<?php
// app/views/internal/booking/script.php
// JavaScript untuk internal booking
?>

<script>
    /**
     * =================================================================
     * INTERNAL BOOKING - JAVASCRIPT (AJAX VERSION)
     * =================================================================
     * 
     * Fitur Utama:
     * - Manajemen Modal Jadwal & Booking
     * - AJAX Updates untuk pergantian tanggal & post-booking (Smooth Transition)
     * - Validasi Form
     */

    // =================================================================
    // MANAJEMEN MODAL - Schedule Modal
    // =================================================================

    // Variable global untuk menyimpan ID lab yang sedang dibuka modalnya
    let currentOpenLabId = null;

    /**
     * Buka modal jadwal untuk lab tertentu
     * Flow: 
     * 1. Render data awal dari cache DOM (Instant)
     * 2. Trigger AJAX silent update untuk memastikan data fresh
     * 
     * @param {number} labId - ID lab yang akan ditampilkan
     * @param {string} labName - Nama lab untuk judul
     */
    function openScheduleModal(labId, labName) {
        currentOpenLabId = labId; 

        const scheduleModal = document.getElementById('scheduleModal');
        const modalCard = scheduleModal.querySelector('.p-modal-card');
        const allLabsData = document.getElementById('allLabsData');
        const singleLabGrid = document.getElementById('singleLabGrid');

        // 1. TAMPILKAN DATA CACHED DULU (INSTANT)
        singleLabGrid.innerHTML = '';
        const labDataElements = allLabsData.querySelectorAll('.lab-data');
        labDataElements.forEach(function (labData) {
            if (parseInt(labData.getAttribute('data-lab-id')) === labId) {
                const labCard = labData.querySelector('.p-lab-card').cloneNode(true);
                singleLabGrid.appendChild(labCard);
            }
        });

        // Set styling untuk view single lab
        singleLabGrid.style.gridTemplateColumns = '1fr';
        singleLabGrid.style.maxWidth = 'none';
        singleLabGrid.style.margin = '0';
        modalCard.style.maxWidth = '480px';

        // Tampilkan modal
        scheduleModal.classList.add('active');

        // 2. FETCH DATA TERBARU VIA AJAX (SILENT UPDATE)
        const currentDate = document.getElementById('scheduleDate') ? document.getElementById('scheduleDate').value : '<?= $data["selected_date"] ?>';
        refreshScheduleContent(labId, currentDate);
    }

    /**
     * Helper: Refresh konten jadwal via AJAX dengan transisi halus (Opacity)
     * Tidak merusak layout karena tidak me-remove konten lama sebelum konten baru siap.
     */
    function refreshScheduleContent(labId, dateStr) {
        const singleLabGrid = document.getElementById('singleLabGrid');
        const slotListContainer = singleLabGrid.querySelector('.p-slot-list');
        
        if (!slotListContainer) return;

        // Visual Feedback: Redupkan konten lama (jangan dihapus)
        slotListContainer.style.transition = 'opacity 0.2s ease';
        slotListContainer.style.opacity = '0.5';
        slotListContainer.style.pointerEvents = 'none'; // Cegah klik

        // Fetch
        fetch('<?= BASE_URL ?>/internal/getLabSlots?lab_id=' + labId + '&date=' + dateStr)
            .then(response => response.text())
            .then(html => {
                // Update HTML
                slotListContainer.innerHTML = html;
                
                // Kembalikan Opacity (terang kembali)
                slotListContainer.style.opacity = '1';
                slotListContainer.style.pointerEvents = 'auto';
            })
            .catch(err => {
                console.error('Failed to refresh schedule:', err);
                // Jika error, kembalikan opacity (tetap tampilkan data lama)
                slotListContainer.style.opacity = '1';
                slotListContainer.style.pointerEvents = 'auto';
            });
    }

    /**
     * Tutup modal jadwal
     */
    function closeScheduleModal() {
        currentOpenLabId = null;
        const scheduleModal = document.getElementById('scheduleModal');
        const modalCard = scheduleModal.querySelector('.p-modal-card');
        
        // Reset styling
        modalCard.style.maxWidth = '';
        
        // Hide modal
        scheduleModal.classList.remove('active');
        
        // Hapus param URL agar bersih
        const url = new URL(window.location.href);
        url.searchParams.delete('open_lab_id');
        window.history.pushState({}, '', url);
    }

    // =================================================================
    // MANAJEMEN MODAL - Form Booking Modal
    // =================================================================

    let bookingModalInstance = null;

    function getIndonesianDay(dateStr) {
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const date = new Date(dateStr);
        return days[date.getDay()];
    }

    function openBookingModal(labName, jamMulai, jamSelesai) {
        // Sync tanggal dari modal jadwal
        const scheduleDateInput = document.getElementById('scheduleDate');
        const bookingDateInput = document.getElementById('bookingDate');
        const bookingDayInput = document.getElementById('bookingDay');

        if (scheduleDateInput) {
             const selectedDate = scheduleDateInput.value;
             bookingDateInput.value = selectedDate;
             
             // UPDATE HARI SECARA DINAMIS
             bookingDayInput.value = getIndonesianDay(selectedDate).toUpperCase();
        }

        // Isi form lainnya
        document.getElementById('bookingLab').value = labName;
        document.getElementById('jamMulai').value = jamMulai;
        document.getElementById('jamSelesai').value = jamSelesai;
        document.getElementById('slotInfoText').textContent = 'Slot kosong: ' + jamMulai + '-' + jamSelesai;

        // Buka modal
        const bookingModalEl = document.getElementById('bookingModal');
        if (!bookingModalInstance) {
            bookingModalInstance = new bootstrap.Modal(bookingModalEl, {
                backdrop: 'static',
                keyboard: false
            });
        }
        bookingModalInstance.show();
    }

    // =================================================================
    // VALIDATION HELPERS
    // =================================================================
    
    function validateRequiredFields(formData) {
        if (!formData.peminjam || !formData.kegiatan) return { valid: false, message: 'Mohon isi nama peminjam dan nama kegiatan!' };
        return { valid: true, message: '' };
    }

    function validateTimeRange(startTime, endTime) {
        if (startTime >= endTime) return { valid: false, message: 'Jam mulai harus lebih awal dari jam selesai!' };
        return { valid: true, message: '' };
    }

    function validateMinimumDuration(startTime, endTime) {
        const [startHour] = startTime.split(':');
        const [endHour] = endTime.split(':');
        if (parseInt(endHour) - parseInt(startHour) < 1) return { valid: false, message: 'Durasi peminjaman minimal 1 jam!' };
        return { valid: true, message: '' };
    }

    // =================================================================
    // API CALLS - Submit Booking
    // =================================================================

    function submitBooking() {
        const formData = {
            tanggal: document.getElementById('bookingDate').value,
            lab: document.getElementById('bookingLab').value,
            jamMulai: document.getElementById('jamMulai').value,
            jamSelesai: document.getElementById('jamSelesai').value,
            peminjam: document.getElementById('namaPeminjam').value,
            kegiatan: document.getElementById('namaKegiatan').value
        };

        // Validasi
        const v1 = validateRequiredFields(formData);
        if (!v1.valid) { Swal.fire({ icon: 'error', title: 'Data Tidak Lengkap', text: v1.message, confirmButtonColor: '#3b82f6' }); return; }
        
        const v2 = validateTimeRange(formData.jamMulai, formData.jamSelesai);
        if (!v2.valid) { Swal.fire({ icon: 'error', title: 'Waktu Tidak Valid', text: v2.message, confirmButtonColor: '#3b82f6' }); return; }
        
        const v3 = validateMinimumDuration(formData.jamMulai, formData.jamSelesai);
        if (!v3.valid) { Swal.fire({ icon: 'warning', title: 'Durasi Terlalu Singkat', text: v3.message, confirmButtonColor: '#3b82f6' }); return; }

        // Loading
        const submitButton = event.target;
        const originalButtonText = submitButton.textContent;
        submitButton.disabled = true;
        submitButton.textContent = 'Menyimpan...';
        submitButton.style.opacity = '0.6';

        // Submit AJAX
        fetch('<?= BASE_URL ?>/internal/submitBooking', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                tanggal: formData.tanggal,
                lab: formData.lab,
                jamMulai: formData.jamMulai,
                jamSelesai: formData.jamSelesai,
                namaPeminjam: formData.peminjam,
                namaKegiatan: formData.kegiatan
            })
        })
            .then(async response => {
                const text = await response.text();
                try { return JSON.parse(text); } catch (e) { throw new Error("Server Error: " + text); }
            })
            .then(data => {
                if (data.success) {
                    if (bookingModalInstance) bookingModalInstance.hide();
                    document.getElementById('bookingForm').reset();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        // AJAX REFRESH: Smooth update tanpa reload
                        if (currentOpenLabId) {
                            refreshScheduleContent(currentOpenLabId, formData.tanggal);
                        } else {
                            // Fallback
                            window.location.reload(); 
                        }
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: data.message, confirmButtonColor: '#3b82f6' });
                }
            })
            .catch(error => {
                console.error('submitBooking error:', error);
                Swal.fire({ icon: 'error', title: 'Terjadi Kesalahan', text: 'Silakan coba lagi.', confirmButtonColor: '#3b82f6' });
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = originalButtonText;
                submitButton.style.opacity = '1';
            });
    }

    // =================================================================
    // NAVIGASI TANGGAL
    // =================================================================

    /**
     * Navigasi ke tanggal yang berbeda
     */
    function changeDate(newDate) {
        // Update display nama hari di modal
        const displayDay = document.getElementById('displayDayName');
        if (displayDay) {
            displayDay.textContent = getIndonesianDay(newDate).toUpperCase();
        }

        if (currentOpenLabId) {
            // Update URL bar
            const newUrl = '<?= BASE_URL ?>/internal/booking?date=' + newDate + '&open_lab_id=' + currentOpenLabId;
            window.history.pushState({path: newUrl}, '', newUrl);
            
            // Trigger smooth refresh
            refreshScheduleContent(currentOpenLabId, newDate);
            
            return;
        }
        
        window.location.href = '<?= BASE_URL ?>/internal/booking?date=' + newDate;
    }

    /**
     * Auto-open logic
     */
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const openLabId = urlParams.get('open_lab_id');
        
        if (openLabId) {
            const labId = parseInt(openLabId);
            const allLabsData = document.getElementById('allLabsData');
            const labDataElements = allLabsData.querySelectorAll('.lab-data');
            let labName = '';
            
            labDataElements.forEach(function (labData) {
                if (parseInt(labData.getAttribute('data-lab-id')) === labId) {
                    labName = labData.getAttribute('data-lab-name');
                }
            });
            
            if (labName) {
                openScheduleModal(labId, labName);
            }
        }
    });

    // =================================================================
    // EVENT LISTENERS
    // =================================================================

    document.getElementById('scheduleModal').addEventListener('click', function (e) {
        if (e.target === this) {
            closeScheduleModal();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            const bookingModal = document.getElementById('bookingModal');
            if (!bookingModal.classList.contains('show')) {
                closeScheduleModal();
            }
        }
    });
</script>