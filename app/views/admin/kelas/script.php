<?php
// views/admin/kelas/script.php
?>

<script>
    // Handle Edit Parameter Population
    // Handle Edit Parameter Population using Event Delegation
    document.addEventListener('click', function (e) {
        const button = e.target.closest('.btn-edit');
        if (button) {
            document.getElementById('edit_id').value = button.dataset.id;
            document.getElementById('edit_nama_kelas').value = button.dataset.nama;
            document.getElementById('edit_jurusan_id').value = button.dataset.jurusan;
            document.getElementById('edit_angkatan').value = button.dataset.angkatan;
        }
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
