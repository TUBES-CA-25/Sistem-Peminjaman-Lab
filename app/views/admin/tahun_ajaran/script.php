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

    window.hapusTahunAjaran = function (id) {
        Swal.fire({
            title: 'Hapus Tahun Ajaran?',
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
                form.action = '<?= BASE_URL ?>/tahun_ajaran';
                const act = document.createElement('input'); act.type = 'hidden'; act.name = 'action'; act.value = 'delete';
                const inp = document.createElement('input'); inp.type = 'hidden'; inp.name = 'id'; inp.value = id;
                form.appendChild(act); form.appendChild(inp);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>