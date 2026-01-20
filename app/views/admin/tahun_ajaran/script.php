<?php
// app/views/pages/admin/tahun_ajaran/script.php
?>
<script>
    function prepareModal(mode, data = null) {
        const form = document.getElementById('taForm');
        const title = document.getElementById('modalTitle');
        const btn = document.getElementById('submitBtn');
        const action = document.getElementById('formAction');
        const editId = document.getElementById('editId');

        form.reset();

        if (mode === 'add') {
            title.textContent = 'Tambah Tahun Ajaran';
            btn.textContent = 'Simpan';
            action.value = 'create';
            editId.value = '';
        } else {
            title.textContent = 'Edit Tahun Ajaran';
            btn.textContent = 'Update';
            action.value = 'update';
            editId.value = data.id;

            document.getElementById('nama').value = data.nama;
            // Status removed from UI
            // document.getElementById('status').value = data.status;
        }
    }
</script>