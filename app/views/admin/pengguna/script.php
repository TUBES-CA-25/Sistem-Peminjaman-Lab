<?php
// app/views/pages/admin/pengguna/script.php
?>
<script>
    // Filter Logic
    document.querySelectorAll('#filterButtons button').forEach(btn => {
        btn.addEventListener('click', function () {
            // UI Update
            document.querySelectorAll('#filterButtons button').forEach(b => {
                b.classList.remove('btn-dark', 'active');
                b.classList.add('btn-outline-secondary');
            });
            this.classList.remove('btn-outline-secondary');
            this.classList.add('btn-dark', 'active');

            // Filtering
            const filter = this.dataset.filter;
            document.querySelectorAll('.user-row').forEach(row => {
                const role = row.dataset.role;
                if (filter === 'semua' || role === filter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });

    // Search Logic
    document.getElementById('searchInput')?.addEventListener('input', function () {
        const term = this.value.toLowerCase();
        document.querySelectorAll('.user-row').forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(term) ? '' : 'none';
        });
    });

    // Modal Logic
    function prepareModal(mode, data = null) {
        const form = document.getElementById('uUserForm');
        const title = document.getElementById('modalTitle');
        const btn = document.getElementById('submitBtn');
        const action = document.getElementById('formAction');
        const editId = document.getElementById('editId');

        // Pass Field Logic
        const pass = document.getElementById('password');
        const passReq = document.getElementById('passReq');
        const passHelp = document.getElementById('passHelp');

        form.reset();

        if (mode === 'add') {
            title.textContent = 'Tambah Pengguna Baru';
            btn.textContent = 'Simpan Pengguna';
            action.value = 'create';
            editId.value = '';

            pass.required = true;
            passReq.style.display = 'inline';
            passHelp.textContent = 'Min. 8 karakter (Wajib)';
            document.getElementById('status').checked = true;
        } else {
            title.textContent = 'Edit Pengguna';
            btn.textContent = 'Update Pengguna';
            action.value = 'update';
            editId.value = data.id;

            document.getElementById('nama').value = data.nama;
            document.getElementById('email').value = data.email;
            document.getElementById('posisi').value = data.posisi;
            document.getElementById('role').value = data.role;
            document.getElementById('username').value = data.username;
            document.getElementById('status').checked = (data.status === 'aktif');

            pass.required = false;
            passReq.style.display = 'none';
            passHelp.textContent = 'Kosongkan jika tidak ingin mengubah password';
        }
    }
</script>