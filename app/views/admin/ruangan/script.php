<?php
// app/views/pages/admin/ruangan/script.php
?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Modal AFTER Bootstrap is loaded
        const labModalEl = document.getElementById('labModal');
        let labModal = null;
        if (labModalEl) {
            labModal = new bootstrap.Modal(labModalEl);
        }

        function $(id) { return document.getElementById(id); }

        // Upload Logic
        window.handleFileSelect = function (event) {
            const file = event?.target?.files?.[0];
            if (!file) return;
            if (!file.type.match('image.*')) { alert('Hanya gambar!'); return; }
            if (file.size > 5 * 1024 * 1024) { alert('Maks 5MB!'); return; }

            const reader = new FileReader();
            reader.onload = function (e) {
                $('previewImg').src = e.target.result;
                $('imagePreview').style.display = 'block';
                $('uploadPlaceholder').style.display = 'none';
                $('changePhotoBtn').style.display = 'block';
                $('labImage').value = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        // Drag & Drop Styling
        const dropArea = $('uploadArea');
        if (dropArea) {
            ['dragenter', 'dragover'].forEach(e => {
                dropArea.addEventListener(e, (evt) => {
                    evt.preventDefault();
                    dropArea.classList.add('dragover');
                });
            });
            ['dragleave', 'drop'].forEach(e => {
                dropArea.addEventListener(e, (evt) => {
                    evt.preventDefault();
                    dropArea.classList.remove('dragover');
                });
            });
            dropArea.addEventListener('drop', (e) => {
                const file = e.dataTransfer.files[0];
                if (file) {
                    const list = new DataTransfer();
                    list.items.add(file);
                    $('labImageFile').files = list.files;
                    handleFileSelect({ target: { files: [file] } });
                }
            });
        }

        // Reset Form Helper
        function resetUpload() {
            $('imagePreview').style.display = 'none';
            $('uploadPlaceholder').style.display = 'block';
            $('changePhotoBtn').style.display = 'none';
            $('labImageFile').value = '';
            $('labImage').value = '';
        }

        // Open Modal Logic
        window.openModal = function (mode, data = null) {
            const form = $('labForm');
            form.reset();

            if (mode === 'add') {
                $('modalTitle').textContent = 'Informasi Ruangan';
                $('submitBtnText').textContent = 'Tambah Ruangan';
                $('formAction').value = 'create';
                $('editIndex').value = '';
                $('labImageFile').required = true;
                resetUpload();
                if ($('labStatus')) $('labStatus').checked = true;
            } else {
                $('modalTitle').textContent = 'Edit Ruangan';
                $('submitBtnText').textContent = 'Simpan Perubahan';
                $('formAction').value = 'update';
                $('editIndex').value = data.id;

                $('labName').value = data.nama_ruangan;
                $('labCapacity').value = data.kapasitas;
                $('labLocation').value = data.lokasi;
                $('labPIC').value = data.pic;
                $('labEmail').value = data.email_pic;
                $('labFacilities').value = data.fasilitas;
                $('labDescription').value = data.deskripsi;
                $('labImage').value = data.gambar;
                if ($('labStatus')) $('labStatus').checked = (data.status == 1);
                $('labImageFile').required = false;

                if (data.gambar) {
                    $('previewImg').src = data.gambar;
                    $('imagePreview').style.display = 'block';
                    $('uploadPlaceholder').style.display = 'none';
                    $('changePhotoBtn').style.display = 'block';
                } else {
                    resetUpload();
                }
            }

            if (labModal) labModal.show();
        }

        window.editLab = function (data) {
            openModal('edit', data);
        }

        // Auto-fill Email
        window.updateCoordinatorEmail = function (select) {
            const selected = select.options[select.selectedIndex];
            const email = selected.getAttribute('data-email');
            $('labEmail').value = email || '';
        }
    });
</script>