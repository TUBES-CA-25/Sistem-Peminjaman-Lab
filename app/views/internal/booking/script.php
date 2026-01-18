<?php
// app/views/internal/booking/script.php
// JavaScript untuk internal booking
?>

<script>
    /**
     * =================================================================
     * INTERNAL BOOKING - JAVASCRIPT
     * =================================================================
     * 
     * File ini menghandle:
     * 1. Manajemen modal (schedule, form booking, view all)
     * 2. Validasi form (required fields, time range, duration)
     * 3. API calls untuk submit booking
     * 
     * Tipe modal:
     * - MODAL 1: Schedule Modal (view per-lab via custom modal)
     * - MODAL 2: Booking Form (Bootstrap modal)
     * - MODAL 3: View All Schedules (read-only custom modal)
     */

    // =================================================================
    // MANAJEMEN MODAL - Schedule Modal
    // =================================================================

    /**
     * Buka modal jadwal untuk lab tertentu
     * Menampilkan card jadwal untuk satu lab saja
     * 
     * @param {number} labId - ID lab yang akan ditampilkan
     * @param {string} labName - Nama lab untuk judul
     */
    function openScheduleModal(labId, labName) {
        const scheduleModal = document.getElementById('scheduleModal');
        const modalCard = scheduleModal.querySelector('.p-modal-card');
        const allLabsData = document.getElementById('allLabsData');
        const singleLabGrid = document.getElementById('singleLabGrid');

        // Bersihkan konten sebelumnya
        singleLabGrid.innerHTML = '';

        // Cari dan clone data jadwal lab yang spesifik
        const labDataElements = allLabsData.querySelectorAll('.lab-data');
        labDataElements.forEach(function (labData) {
            if (parseInt(labData.getAttribute('data-lab-id')) === labId) {
                // Clone seluruh card lab (termasuk grid jadwal)
                const labCard = labData.querySelector('.p-lab-card').cloneNode(true);
                singleLabGrid.appendChild(labCard);
            }
        });

        // Sesuaikan styling untuk view satu lab
        singleLabGrid.style.gridTemplateColumns = '1fr';
        singleLabGrid.style.maxWidth = 'none';
        singleLabGrid.style.margin = '0';
        modalCard.style.maxWidth = '480px';  // Lebih sempit untuk satu lab

        // Tampilkan modal
        scheduleModal.classList.add('active');
    }

    /**
     * Tutup modal jadwal dan reset styling
     */
    function closeScheduleModal() {
        const scheduleModal = document.getElementById('scheduleModal');
        const modalCard = scheduleModal.querySelector('.p-modal-card');

        // Reset lebar modal ke default
        modalCard.style.maxWidth = '';

        // Sembunyikan modal
        scheduleModal.classList.remove('active');
    }

    // =================================================================
    // MANAJEMEN MODAL - Form Booking Modal
    // =================================================================

    // Variable global untuk menyimpan instance Bootstrap modal
    let bookingModalInstance = null;

    /**
     * Buka modal form booking dengan time slot yang sudah di-prefill
     * 
     * @param {string} labName - Nama lab yang akan dibooking
     * @param {string} jamMulai - Jam mulai (HH:MM)
     * @param {string} jamSelesai - Jam selesai (HH:MM)
     */
    function openBookingModal(labName, jamMulai, jamSelesai) {
        // Isi field form dengan data awal
        document.getElementById('bookingLab').value = labName;
        document.getElementById('jamMulai').value = jamMulai;
        document.getElementById('jamSelesai').value = jamSelesai;
        document.getElementById('slotInfoText').textContent = 'Slot kosong: ' + jamMulai + '-' + jamSelesai;

        // Buat instance Bootstrap modal jika belum ada
        const bookingModalEl = document.getElementById('bookingModal');
        if (!bookingModalInstance) {
            bookingModalInstance = new bootstrap.Modal(bookingModalEl, {
                backdrop: 'static',  // Cegah tutup modal saat klik backdrop
                keyboard: false      // Cegah tutup modal dengan ESC key
            });
        }

        // Tampilkan modal
        bookingModalInstance.show();
    }

    // =================================================================
    // VALIDATION HELPERS - Function Bantuan Validasi
    // =================================================================

    /**
     * Validasi field booking yang required
     * 
     * @param {Object} formData - Object data form
     * @returns {Object} {valid: boolean, message: string}
     */
    function validateRequiredFields(formData) {
        if (!formData.peminjam || !formData.kegiatan) {
            return {
                valid: false,
                message: 'Mohon isi nama peminjam dan nama kegiatan!'
            };
        }
        return { valid: true, message: '' };
    }

    /**
     * Validasi rentang waktu (mulai harus lebih awal dari selesai)
     * 
     * @param {string} startTime - Jam mulai (HH:MM)
     * @param {string} endTime - Jam selesai (HH:MM)
     * @returns {Object} {valid: boolean, message: string}
     */
    function validateTimeRange(startTime, endTime) {
        if (startTime >= endTime) {
            return {
                valid: false,
                message: 'Jam mulai harus lebih awal dari jam selesai!'
            };
        }
        return { valid: true, message: '' };
    }

    /**
     * Validasi durasi minimum booking (1 jam)
     * 
     * @param {string} startTime - Jam mulai (HH:MM)
     * @param {string} endTime - Jam selesai (HH:MM)
     * @returns {Object} {valid: boolean, message: string}
     */
    function validateMinimumDuration(startTime, endTime) {
        const [startHour] = startTime.split(':');
        const [endHour] = endTime.split(':');
        
        // Cek selisih jam sederhana (asumsi booking di hari yang sama)
        if (parseInt(endHour) - parseInt(startHour) < 1) {
            return {
                valid: false,
                message: 'Durasi peminjaman minimal 1 jam!'
            };
        }
        return { valid: true, message: '' };
    }

    // =================================================================
    // API CALLS - Submit Booking
    // =================================================================

    /**
     * Submit form booking ke server
     * Validasi input, panggil API, handle response
     */
    function submitBooking() {
        // Kumpulkan data dari form
        const formData = {
            tanggal: document.getElementById('bookingDate').value,
            lab: document.getElementById('bookingLab').value,
            jamMulai: document.getElementById('jamMulai').value,
            jamSelesai: document.getElementById('jamSelesai').value,
            peminjam: document.getElementById('namaPeminjam').value,
            kegiatan: document.getElementById('namaKegiatan').value
        };

        // Validasi 1: Field yang required
        const requiredValidation = validateRequiredFields(formData);
        if (!requiredValidation.valid) {
            Swal.fire({
                icon: 'error',
                title: 'Data Tidak Lengkap',
                text: requiredValidation.message,
                confirmButtonColor: '#3b82f6'
            });
            return;
        }
        
        // Validasi 2: Rentang waktu
        const timeValidation = validateTimeRange(formData.jamMulai, formData.jamSelesai);
        if (!timeValidation.valid) {
            Swal.fire({
                icon: 'error',
                title: 'Waktu Tidak Valid',
                text: timeValidation.message,
                confirmButtonColor: '#3b82f6'
            });
            return;
        }
        
        // Validasi 3: Durasi minimum
        const durationValidation = validateMinimumDuration(formData.jamMulai, formData.jamSelesai);
        if (!durationValidation.valid) {
            Swal.fire({
                icon: 'warning',
                title: 'Durasi Terlalu Singkat',
                text: durationValidation.message,
                confirmButtonColor: '#3b82f6'
            });
            return;
        }

        // Dapatkan referensi tombol submit untuk loading state
        const submitButton = event.target;
        const originalButtonText = submitButton.textContent;
        
        // Tampilkan loading state
        submitButton.disabled = true;
        submitButton.textContent = 'Menyimpan...';
        submitButton.style.opacity = '0.6';

        // API call untuk submit booking
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
                // Parse response sebagai text dulu untuk handle error
                const text = await response.text();
                try {
                    return JSON.parse(text);
                } catch (e) {
                    throw new Error("Server Error: " + text);
                }
            })
            .then(data => {
                if (data.success) {
                    // Berhasil: Tutup modal, reset form, tampilkan pesan sukses
                    if (bookingModalInstance) bookingModalInstance.hide();
                    document.getElementById('bookingForm').reset();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        confirmButtonColor: '#3b82f6'
                    }).then(() => {
                        // Reload halaman untuk tampilkan booking baru
                        window.location.reload();
                    });
                } else {
                    // Gagal: Tampilkan pesan error (konflik, error validasi, dll.)
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message,
                        confirmButtonColor: '#3b82f6'
                    });
                }
            })
            .catch(error => {
                // Network error atau server crash
                console.error('submitBooking error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'Silakan coba lagi atau hubungi administrator.',
                    confirmButtonColor: '#3b82f6'
                });
            })
            .finally(() => {
                // Re-enable tombol submit
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
     * Reload halaman dengan parameter tanggal baru
     * 
     * @param {string} newDate - Tanggal dalam format Y-m-d
     */
    function changeDate(newDate) {
        window.location.href = '<?= BASE_URL ?>/internal/booking?date=' + newDate;
    }

    /**
     * Navigasi ke tanggal berbeda dari view modal
     * 
     * @param {string} newDate - Tanggal dalam format Y-m-d
     */
    function changeViewDate(newDate) {
        window.location.href = '<?= BASE_URL ?>/internal/booking?date=' + newDate;
    }

    // =================================================================
    // MANAJEMEN MODAL - View All Schedules Modal
    // =================================================================

    /**
     * Buka modal view semua jadwal (read-only)
     * Menampilkan jadwal untuk semua lab sekaligus
     */
    function openViewScheduleModal() {
        document.getElementById('viewScheduleModal').classList.add('active');
    }

    /**
     * Tutup modal view semua jadwal
     */
    function closeViewScheduleModal() {
        document.getElementById('viewScheduleModal').classList.remove('active');
    }

    // =================================================================
    // EVENT LISTENERS - Interaksi Modal
    // =================================================================

    /**
     * Tutup modal jadwal ketika klik backdrop (bukan kontennya)
     * PENTING: Hanya tutup ketika klik background semi-transparan
     */
    document.getElementById('scheduleModal').addEventListener('click', function (e) {
        // e.target === this artinya klik langsung di backdrop, bukan child elements
        if (e.target === this) {
            closeScheduleModal();
        }
    });

    /**
     * Tutup modal dengan tombol ESC
     * Hanya tutup jika tidak ada modal lain yang sedang terbuka
     */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            const bookingModal = document.getElementById('bookingModal');
            const viewModal = document.getElementById('viewScheduleModal');

            // Tutup schedule modal hanya jika booking modal tidak visible
            if (!bookingModal.classList.contains('show')) {
                closeScheduleModal();
            }

            // Tutup view modal jika sedang terbuka
            if (viewModal && viewModal.classList.contains('active')) {
                closeViewScheduleModal();
            }
        }
    });

    /**
     * Tutup modal view jadwal ketika klik backdrop
     */
    document.getElementById('viewScheduleModal').addEventListener('click', function (e) {
        if (e.target === this) {
            closeViewScheduleModal();
        }
    });
</script>