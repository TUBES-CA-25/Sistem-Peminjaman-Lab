<?php
// views/admin/matakuliah/script.php
?>
<script>
    // Handle Edit Param Population
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('edit_id').value = this.dataset.id;
            document.getElementById('edit_nama_matakuliah').value = this.dataset.nama;
            document.getElementById('edit_kode_matakuliah').value = this.dataset.kode;
            document.getElementById('edit_singkatan').value = this.dataset.singkatan;
            document.getElementById('edit_semester').value = this.dataset.semester;
            document.getElementById('edit_sks').value = this.dataset.sks;
            document.getElementById('edit_jurusan_id').value = this.dataset.jurusan;
        });
    });

    window.hapusMatakuliah = function (id) {
        Swal.fire({
            title: 'Hapus Mata Kuliah?',
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
                form.action = '<?= BASE_URL ?>/matakuliah/delete/' + id;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>