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

    // ----------------------------------------------------
    // VISUAL STATE MANAGEMENT (Edit vs View Mode)
    // ----------------------------------------------------

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
                        alert('Gagal memproses gambar. Silakan coba lagi.');
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
    // FORM SUBMIT HANDLER
    // ----------------------------------------------------
    const formProfile = document.getElementById('formProfile');
    const btnSubmit = document.getElementById('btnSubmitProfile');
    
    if (formProfile && btnSubmit) {
        formProfile.addEventListener('submit', function() {
            // Tampilkan loading spinner pada tombol simpan
            const icon = btnSubmit.querySelector('.icon-default');
            const spinner = btnSubmit.querySelector('.spinner-border');
            const btnText = btnSubmit.querySelector('.btn-text');
            
            btnSubmit.disabled = true;
            if (icon) icon.classList.add('d-none');
            if (spinner) spinner.classList.remove('d-none');
            if (btnText) btnText.textContent = 'Menyimpan...';
        });
    }
});
</script>
