<?php
// views/admin/kelas/script.php
?>

<script>
    // Handle Edit Parameter Population
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('edit_id').value = this.dataset.id;
            document.getElementById('edit_nama_kelas').value = this.dataset.nama;
            document.getElementById('edit_jurusan_id').value = this.dataset.jurusan;
            document.getElementById('edit_angkatan').value = this.dataset.angkatan;
        });
    });

    window.hapusKelas = function (id) {
        Swal.fire({
            title: 'Hapus Kelas?',
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
                form.action = '<?= BASE_URL ?>/kelas/delete/' + id;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
