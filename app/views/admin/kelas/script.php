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
</script>