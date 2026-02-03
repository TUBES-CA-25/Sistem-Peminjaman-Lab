<?php
// app/views/pages/admin/ruangan/script.php
?>
<script>
    const BASE_URL = "<?= BASE_URL ?>";

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
            if (!file.type.match('image.*')) {
                Swal.fire({ icon: 'error', title: 'Format Salah', text: 'Hanya gambar (JPG, PNG) yang diperbolehkan!' });
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                Swal.fire({ icon: 'error', title: 'File Terlalu Besar', text: 'Maksimum ukuran file adalah 5MB!' });
                return;
            }


            const reader = new FileReader();
            reader.onload = function (e) {
                $('previewImg').src = e.target.result;
                $('fileName').textContent = file.name;
                $('imagePreview').style.display = 'block';
                $('uploadPlaceholder').style.display = 'none';
                $('changePhotoBtn').style.display = 'block';
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
            $('fileName').textContent = '';
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
            } else {
                $('modalTitle').textContent = 'Edit Ruangan';
                $('submitBtnText').textContent = 'Simpan Perubahan';
                $('formAction').value = 'update';
                $('editIndex').value = data.id;

                $('labName').value = data.nama_ruangan;
                $('labCapacity').value = data.kapasitas;
                $('labPIC').value = data.pic;
                $('labEmail').value = data.email_pic;
                $('labImage').value = data.gambar;
                $('labImageFile').required = false;

                if (data.gambar) {
                    let imgSrc = data.gambar;
                    // Replicate PHP logic for image path
                    if (imgSrc.startsWith('/')) {
                        imgSrc = '<?= BASE_URL ?>' + imgSrc;
                    } else if (imgSrc.startsWith('public/')) {
                        imgSrc = '<?= BASE_URL ?>/' + imgSrc;
                    } else {
                        imgSrc = '<?= BASE_URL ?>/public/storage/images/' + imgSrc;
                    }

                    $('previewImg').src = imgSrc;
                    $('fileName').textContent = data.gambar.split('/').pop(); // Show filename from path
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

        window.hapusRuangan = function (id) {
            Swal.fire({
                title: 'Hapus Ruangan?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '<?= BASE_URL ?>/ruangan';
                    const act = document.createElement('input'); act.type = 'hidden'; act.name = 'action'; act.value = 'delete';
                    const inp = document.createElement('input'); inp.type = 'hidden'; inp.name = 'id'; inp.value = id;
                    form.appendChild(act); form.appendChild(inp);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

    });
</script>
