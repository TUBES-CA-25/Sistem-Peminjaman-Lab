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

    // Role Change Listener
    document.getElementById('role')?.addEventListener('change', function () {
        const statusGroup = document.getElementById('statusGroup');
        const posisi = document.getElementById('posisi');
        const telepon = document.getElementById('telepon');
        const telReq = document.getElementById('telReq');

        if (this.value === 'internal') {
            statusGroup.style.display = 'block';
            posisi.required = true;

            // Internal: Phone Not Required
            telepon.required = false;
            if (telReq) telReq.style.display = 'none';

        } else {
            statusGroup.style.display = 'none';
            posisi.required = false;
            posisi.value = ''; // Reset selection

            // External: Phone Required
            telepon.required = true;
            if (telReq) telReq.style.display = 'inline';
        }
    });

    // Modal Logic
    function prepareModal(mode, data = null) {
        const form = document.getElementById('uUserForm');
        const title = document.getElementById('modalTitle');
        const btn = document.getElementById('submitBtn');
        const action = document.getElementById('formAction');
        const editId = document.getElementById('editId');

        // Fields
        const role = document.getElementById('role');
        const posisi = document.getElementById('posisi');
        const statusGroup = document.getElementById('statusGroup');

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

            // Default View
            statusGroup.style.display = 'none';

            pass.required = true;
            passReq.style.display = 'inline';
            passHelp.textContent = 'Min. 8 karakter (Wajib)';
        } else {
            title.textContent = 'Edit Pengguna';
            btn.textContent = 'Update Pengguna';
            action.value = 'update';
            editId.value = data.id;

            document.getElementById('nama').value = data.nama;
            document.getElementById('email').value = data.email;
            document.getElementById('telepon').value = data.telepon || data.nomor_hp || ''; // Support both keys

            // Set Role & Status
            role.value = data.role;
            const telepon = document.getElementById('telepon');
            const telReq = document.getElementById('telReq');

            if (data.role === 'internal') {
                statusGroup.style.display = 'block';
                posisi.required = true;
                posisi.value = data.status;
                
                // Internal: Phone Not Required
                telepon.required = false;
                if(telReq) telReq.style.display = 'none';
            } else {
                statusGroup.style.display = 'none';
                posisi.required = false;
                
                // External: Phone Required
                telepon.required = true;
                if(telReq) telReq.style.display = 'inline';
            }

            pass.required = false;
            passReq.style.display = 'none';
            passHelp.textContent = 'Kosongkan jika tidak ingin mengubah password';
        }
    }

    window.hapusUser = function (id) {
        Swal.fire({
            title: 'Hapus Pengguna?',
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
                form.action = '<?= BASE_URL ?>/user';
                const act = document.createElement('input'); act.type = 'hidden'; act.name = 'action'; act.value = 'delete';
                const inp = document.createElement('input'); inp.type = 'hidden'; inp.name = 'id'; inp.value = id;
                form.appendChild(act); form.appendChild(inp);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

</script>