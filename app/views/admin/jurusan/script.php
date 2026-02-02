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

    window.hapusJurusan = function (id) {
        Swal.fire({
            title: 'Hapus Jurusan?',
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
                form.action = '<?= BASE_URL ?>/jurusan';
                const act = document.createElement('input'); act.type = 'hidden'; act.name = 'action'; act.value = 'delete';
                const inp = document.createElement('input'); inp.type = 'hidden'; inp.name = 'id'; inp.value = id;
                form.appendChild(act); form.appendChild(inp);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
