<?php
// app/views/internal/profile/script.php
// Logic JavaScript untuk halaman profil (Internal)
?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Element Selectors
    const btnEdit = document.getElementById('btnEdit');
    const btnCancel = document.getElementById('btnCancel');
    const actionButtons = document.getElementById('actionButtons');
    
    // Input Fields
    const inputs = [
        document.getElementById('inputNama'),
        document.getElementById('inputTelepon'),
        document.getElementById('inputEmail'),
        document.getElementById('inputPassword')
    ];

    // Simpan data asli untuk restore saat klik 'Batal'
    const originalData = {
        nama: document.getElementById('originalNama') ? document.getElementById('originalNama').value : '',
        telepon: document.getElementById('originalTelepon') ? document.getElementById('originalTelepon').value : '',
        email: document.getElementById('originalEmail') ? document.getElementById('originalEmail').value : ''
    };

    // Validasi Input Nama (Hanya Huruf & Karakter tertentu, wajib diawali huruf)
    const inputNama = document.getElementById('inputNama');
    if (inputNama) {
        inputNama.addEventListener('input', function(e) {
            // 1. Hapus karakter yang dilarang (izinkan huruf, spasi, ', ., -, dan koma)
            let val = this.value.replace(/[^a-zA-Z\s'.,-]/g, '');
            
            // 2. Wajib diawali huruf (hapus karakter non-huruf di awal)
            val = val.replace(/^[^a-zA-Z]+/, '');
            
            this.value = val;
        });
        // Beri tooltip validasi HTML5: Diawali huruf (A-Z/a-z) diikuti karakter yang diizinkan
        inputNama.setAttribute('pattern', "[A-Za-z][A-Za-z\\s'\\.\\,\\-]*");
        inputNama.setAttribute('title', "Nama harus diawali dengan huruf. Karakter selanjutnya boleh huruf, spasi, tanda petik satu ('), titik (.), tanda hubung (-), atau koma (,).");
    }

    // Validasi Input Telepon (Hanya Angka)
    const inputTelepon = document.getElementById('inputTelepon');
    if (inputTelepon) {
        inputTelepon.addEventListener('input', function(e) {
            // Hapus semua karakter selain angka dan batasi maksimal 13 digit
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 13);
        });
        // Tambahkan tooltip validasi HTML5
        inputTelepon.setAttribute('pattern', "[0-9]{8,13}");
        inputTelepon.setAttribute('title', "Masukkan nomor telepon dalam angka (0-9), maksimal 13 digit.");
    }

    // Masuk ke MODE EDIT
    if (btnEdit) {
        btnEdit.addEventListener('click', function() {
            inputs.forEach(input => {
                if(input) {
                    input.removeAttribute('readonly');
                    input.classList.add('editing');
                }
            });
            if(inputs[0]) inputs[0].focus(); // Focus ke nama
            
            // Tampilkan badge kamera untuk edit foto
            const badge = document.getElementById('avatarEditBadge');
            if(badge) badge.classList.remove('d-none');
            
            // Toggle tombol
            btnEdit.classList.add('d-none');
            if(actionButtons) {
                actionButtons.classList.remove('d-none');
                actionButtons.classList.add('d-flex', 'gap-2');
            }
        });
    }

    // Kembali ke MODE VIEW (Batal)
    if (btnCancel) {
        btnCancel.addEventListener('click', function() {
            inputs.forEach(input => {
                if(input) {
                    input.setAttribute('readonly', true);
                    input.classList.remove('editing');
                }
            });
            
            // Sembunyikan badge kamera
            const badge = document.getElementById('avatarEditBadge');
            if(badge) badge.classList.add('d-none');
            
            // Reset nilai input ke data asli
            if(inputs[0]) inputs[0].value = originalData.nama;
            if(inputs[1]) inputs[1].value = originalData.telepon;
            if(inputs[2]) inputs[2].value = originalData.email;
            if(inputs[3]) inputs[3].value = ''; // Password kosongkan lagi

            // Toggle tombol
            if(actionButtons) {
                actionButtons.classList.add('d-none');
                actionButtons.classList.remove('d-flex');
            }
            if(btnEdit) btnEdit.classList.remove('d-none');
        });
    }

    // ----------------------------------------------------
    // CROPPER.JS IMAGE UPLOAD LOGIC
    // ----------------------------------------------------
    const inputFoto = document.getElementById('inputFoto');
    const imageToCrop = document.getElementById('imageToCrop');
    const btnCrop = document.getElementById('btnCrop');
    const croppedImageInput = document.getElementById('croppedImageInput'); // Hidden input untuk kirim ke server
    let cropper; // Instance CropperJS
    let cropperModal;

    // Inisialisasi Modal
    const modalEl = document.getElementById('cropperModal');
    if (modalEl) {
        cropperModal = new bootstrap.Modal(modalEl);
        
        // 2. Saat modal tampil sepenuhnya -> Inisialisasi Cropper
        modalEl.addEventListener('shown.bs.modal', function() {
            if(imageToCrop) {
                cropper = new Cropper(imageToCrop, {
                    aspectRatio: 1,      // Paksa rasio 1:1 (Kotak/Bulat)
                    viewMode: 2,         // Batasi crop box agar tidak keluar dari gambar
                    autoCropArea: 1,     // Otomatis pilih area maksimal
                    dragMode: 'move',    // Bisa geser gambar
                    guides: true,        // Garis bantu
                    center: true,
                    highlight: false,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                });
            }
        });

        // 3. Saat modal ditutup -> Hancurkan instance cropper (Cleanup memory)
        modalEl.addEventListener('hidden.bs.modal', function() {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            if(inputFoto) inputFoto.value = ''; // Reset input file agar bisa pilih file yang sama lagi
        });
    }
    
    // 1. Saat user pilih file gambar
    if (inputFoto) {
        inputFoto.addEventListener('change', function() {
            const files = this.files;
            if (files && files.length > 0) {
                const file = files[0];
                const reader = new FileReader(); // Baca file secara lokal
                
                reader.onload = function(e) {
                    if(imageToCrop) {
                        imageToCrop.src = e.target.result; // Set gambar di modal
                        if(cropperModal) cropperModal.show(); // Tampilkan modal
                    }
                };
                
                reader.readAsDataURL(file);
            }
        });
    }

    // 4. Tombol "Potong & Simpan" diklik
    if (btnCrop) {
        btnCrop.addEventListener('click', function() {
            if (cropper) {
                // UI Loading state
                const btn = this;
                const spinner = btn.querySelector('.spinner-border');
                const btnText = btn.querySelector('.btn-text');
                
                btn.disabled = true;
                if(spinner) spinner.classList.remove('d-none');
                if(btnText) btnText.textContent = 'Memproses...';

                // Beri sedikit delay agar UI loading sempat render
                setTimeout(() => {
                    try {
                        // Ambil hasil crop sebagai Canvas
                        const canvas = cropper.getCroppedCanvas({
                            width: 500,     // Resize ke 500x500 px
                            height: 500,
                            imageSmoothingEnabled: true,
                            imageSmoothingQuality: 'high',
                        });
                        
                        // Konversi Canvas ke Base64 (JPG quality 0.9)
                        const croppedDataUrl = canvas.toDataURL('image/jpeg', 0.9);
                        
                        // Update Preview Avatar di halaman
                        const avatarContainer = document.getElementById('profileAvatar');
                        if(avatarContainer) {
                            avatarContainer.innerHTML = `<img src="${croppedDataUrl}" alt="Profile">`;
                        }
                        
                        // Masukkan data base64 ke hidden input untuk dikirim ke server
                        if(croppedImageInput) croppedImageInput.value = croppedDataUrl;
                        
                        if(cropperModal) cropperModal.hide(); // Tutup modal
                    } catch (e) {
                        console.error(e);
                        Swal.fire({
                            title: 'Gagal',
                            text: 'Gagal memproses gambar. Silakan coba lagi.',
                            icon: 'error',
                            confirmButtonColor: '#1e3a8a'
                        });
                    } finally {
                        // Reset tombol
                        btn.disabled = false;
                        if(spinner) spinner.classList.add('d-none');
                        if(btnText) btnText.textContent = 'Potong & Simpan';
                    }
                }, 100);
            }
        });
    }

    // ----------------------------------------------------
    // FORM SUBMIT HANDLER (AJAX)
    // ----------------------------------------------------
    const formProfile = document.getElementById('formProfile');
    const btnSubmit = document.getElementById('btnSubmitProfile');
    const emailVerificationModal = new bootstrap.Modal(document.getElementById('modalVerifikasiEmail'));
    
    function resetSubmitButton() {
        const icon = btnSubmit.querySelector('.icon-default');
        const spinner = btnSubmit.querySelector('.spinner-border');
        const btnText = btnSubmit.querySelector('.btn-text');
        
        btnSubmit.disabled = false;
        if (icon) icon.classList.remove('d-none');
        if (spinner) spinner.classList.add('d-none');
        if (btnText) btnText.textContent = 'Simpan Perubahan';
    }

    if (formProfile && btnSubmit) {
        formProfile.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const icon = btnSubmit.querySelector('.icon-default');
            const spinner = btnSubmit.querySelector('.spinner-border');
            const btnText = btnSubmit.querySelector('.btn-text');
            
            btnSubmit.disabled = true;
            if (icon) icon.classList.add('d-none');
            if (spinner) spinner.classList.remove('d-none');
            if (btnText) btnText.textContent = 'Memproses...';

            const formData = new FormData(formProfile);
            
            // Sertakan header X-Requested-With agar controller tahu ini AJAX
            fetch(formProfile.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.requires_verification) {
                    requestVerification(data.new_email);
                } else if (data.success) {
                    location.reload();
                } else {
                    Swal.fire({
                        title: 'Gagal',
                        text: data.message || 'Terjadi kesalahan sistem',
                        icon: 'error',
                        confirmButtonColor: '#1e3a8a'
                    });
                    resetSubmitButton();
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire({
                    title: 'Kesalahan',
                    text: 'Terjadi kesalahan koneksi ke server',
                    icon: 'error',
                    confirmButtonColor: '#1e3a8a'
                });
                resetSubmitButton();
            });
        });
    }

    function requestVerification(email) {
        const formData = new FormData();
        formData.append('email', email);

        fetch('<?= BASE_URL; ?>/internal/requestEmailVerification', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                emailVerificationModal.show();
                resetSubmitButton();
            } else {
                Swal.fire({
                    title: 'Gagal',
                    text: data.message,
                    icon: 'error',
                    confirmButtonColor: '#1e3a8a'
                });
                resetSubmitButton();
            }
        });
    }

    // Handle OTP Confirmation
    const formVerifikasi = document.getElementById('formVerifikasiEmail');
    if (formVerifikasi) {
        formVerifikasi.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('btnConfirmEmailChange');
            const spinner = btn.querySelector('.spinner-border');
            const btnText = btn.querySelector('.btn-text');
            const otpCode = document.getElementById('fullOtpCode').value;

            if (!otpCode || otpCode.length < 6) {
                Swal.fire({
                    title: 'Peringatan',
                    text: 'Masukkan 6 digit kode verifikasi dengan lengkap',
                    icon: 'warning',
                    confirmButtonColor: '#1e3a8a'
                });
                return;
            }

            btn.disabled = true;
            if (spinner) spinner.classList.remove('d-none');
            
            const formData = new FormData();
            formData.append('otp', otpCode);

            fetch('<?= BASE_URL; ?>/internal/verifyEmailChange', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    Swal.fire({
                        title: 'Kode Salah',
                        text: data.message,
                        icon: 'error',
                        confirmButtonColor: '#1e3a8a'
                    });
                    btn.disabled = false;
                    if (spinner) spinner.classList.add('d-none');
                }
            })
            .catch(err => {
                Swal.fire({
                    title: 'Gagal',
                    text: 'Konfirmasi gagal, silakan coba lagi',
                    icon: 'error',
                    confirmButtonColor: '#1e3a8a'
                });
                btn.disabled = false;
                if (spinner) spinner.classList.add('d-none');
            });
        });
    }

    // Handle Resend
    const resendBtn = document.getElementById('btnResendEmailToken');
    if (resendBtn) {
        resendBtn.addEventListener('click', function() {
            const email = document.getElementById('inputEmail').value;
            requestVerification(email);
            Swal.fire({
                title: 'Terkirim!',
                text: 'Kode baru sedang dikirim ke email Anda',
                icon: 'info',
                timer: 2000,
                showConfirmButton: false
            });
        });
    }
});
</script>
