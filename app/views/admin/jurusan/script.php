<?php
// app/views/pages/admin/jurusan/script.php
?>
<script>
    function prepareModal(mode, data = null) {
        const form = document.getElementById('jurusanForm');
        const title = document.getElementById('modalTitle');
        const btn = document.getElementById('submitBtn');
        const action = document.getElementById('formAction');
        const editId = document.getElementById('editId');

        form.reset();

        if (mode === 'add') {
            title.textContent = 'Tambah Jurusan';
            btn.textContent = 'Simpan';
            action.value = 'create';
            editId.value = '';
        } else {
            title.textContent = 'Edit Jurusan';
            btn.textContent = 'Update';
            action.value = 'update';
            editId.value = data.id;

            document.getElementById('nama_jurusan').value = data.nama_jurusan;
            document.getElementById('singkatan').value = data.singkatan;
        }
    }
</script>