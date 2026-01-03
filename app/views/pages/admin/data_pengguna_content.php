<?php
// app/views/pages/admin/data_pengguna_content.php

/**
 * $data['pengguna'] provided by PenggunaController via dashboard.php
 */
$users = $data['pengguna'] ?? [];
?>

<!-- HERO / HEADER -->
<div
    style="background: linear-gradient(135deg, #122E4F 0%, #1F45AC 100%); padding: 2.5rem; border-radius: 12px; margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1); gap: 1rem;">
    <div>
        <h1 style="color: white; font-size: 2rem; font-weight: 800; margin: 0 0 0.5rem 0;">Data Pengguna</h1>
        <p style="color: rgba(255,255,255,0.9); font-size: 1rem; margin:0;">Kelola pengguna berdasarkan kategori:
            Internal, Eksternal, dan Admin.</p>
    </div>

    <button type="button" onclick="openUserModal('add')"
        style="background-color: white; color: #1F45AC; padding: 0.75rem 1.25rem; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); white-space: nowrap;">
        <i class="fas fa-user-plus"></i>
        Tambah Pengguna
    </button>
</div>

<!-- Notification -->
<?php if (isset($_GET['status']) && isset($_GET['msg'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert"
        style="border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <i class="fas fa-check-circle me-2"></i> <strong><?= htmlspecialchars($_GET['msg']) ?>!</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- FILTER + SEARCH (Client-side implementation preserved for simplicity) -->
<div
    style="background:#fff;border-radius:12px;padding:1rem 1.25rem;box-shadow:0 1px 3px rgba(0,0,0,0.08);display:flex;gap:1rem;align-items:center;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;">
    <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
        <button class="u-filter-btn active" data-filter="semua" type="button">Semua</button>
        <button class="u-filter-btn" data-filter="eksternal" type="button">Eksternal</button>
        <button class="u-filter-btn" data-filter="internal" type="button">Internal</button>
        <button class="u-filter-btn" data-filter="admin" type="button">Admin</button>
    </div>

    <div style="min-width:280px; max-width:420px; flex:1; display:flex; justify-content:flex-end;">
        <div style="position:relative;width:100%;max-width:420px;">
            <i class="fas fa-search"
                style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9ca3af;"></i>
            <input id="uSearchInput" type="text" placeholder="Cari nama / email / jabatan..."
                style="width:100%;padding:10px 12px 10px 36px;border:1px solid #e5e7eb;border-radius:10px;outline:none;">
        </div>
    </div>
</div>

<!-- TABLE -->
<div style="background:#fff;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.08);overflow:hidden;">
    <div
        style="padding:1rem 1.25rem;border-bottom:1px solid #eef2f7;display:flex;justify-content:space-between;align-items:center;">
        <div style="font-weight:800;color:#0f172a;">Daftar Pengguna</div>
        <div style="color:#475569;font-size:0.9rem;font-weight:600;">Total: <?= count($users) ?> Pengguna</div>
    </div>

    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;min-width:900px;" id="usersTable">
            <thead style="background:#f8fafc;">
                <tr>
                    <th style="padding:12px 14px;text-align:left;">No</th>
                    <th style="padding:12px 14px;text-align:left;">Nama</th>
                    <th style="padding:12px 14px;text-align:left;">Email</th>
                    <th style="padding:12px 14px;text-align:left;">Jabatan</th>
                    <th style="padding:12px 14px;text-align:left;">Role</th>
                    <th style="padding:12px 14px;text-align:left;">Status</th>
                    <th style="padding:12px 14px;text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $index => $u): ?>
                    <tr class="user-row" data-role="<?= $u['role'] ?>">
                        <td style="padding:14px;font-weight:800;color:#0f172a;"><?= $index + 1 ?></td>
                        <td style="padding:14px;font-weight:800;color:#0f172a;" class="u-name">
                            <?= htmlspecialchars($u['nama']) ?></td>
                        <td style="padding:14px;color:#334155;" class="u-email"><?= htmlspecialchars($u['email']) ?></td>
                        <td style="padding:14px;color:#334155;" class="u-instansi"><?= htmlspecialchars($u['instansi']) ?>
                        </td>
                        <td style="padding:14px;">
                            <?php
                            $badgeClass = '';
                            if ($u['role'] == 'eksternal')
                                $badgeClass = 'u-b-eksternal';
                            elseif ($u['role'] == 'internal')
                                $badgeClass = 'u-b-internal';
                            elseif ($u['role'] == 'admin')
                                $badgeClass = 'u-b-admin';
                            ?>
                            <span class="u-badge <?= $badgeClass ?>"><?= ucfirst($u['role']) ?></span>
                        </td>
                        <td style="padding:14px;">
                            <?php if ($u['status'] == 'aktif'): ?>
                                <span class="u-badge u-b-aktif">Aktif</span>
                            <?php else: ?>
                                <span class="u-badge u-b-nonaktif">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding:14px; text-align:right;">
                            <div class="u-actions">
                                <button type="button" class="u-act u-edit"
                                    onclick='openUserModal("edit", <?= json_encode($u) ?>)'>
                                    <i class="fas fa-edit"></i> Edit
                                </button>

                                <form action="dashboard.php?page=pengguna" method="POST"
                                    onsubmit="return confirm('Hapus pengguna ini?');" style="display:inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                    <button type="submit" class="u-act u-del">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL -->
<div id="uModalOverlay"
    style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center; padding:2rem;">
    <div
        style="background:#fff;border-radius:12px;width:100%;max-width:720px;max-height:90vh;overflow:auto;box-shadow:0 20px 60px rgba(0,0,0,0.3);">
        <div
            style="padding:18px 22px;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;">
            <h2 id="uModalTitle" style="margin:0;font-size:18px;font-weight:800;color:#0f172a;">Tambah Pengguna Baru
            </h2>
            <button type="button" onclick="closeUserModal()"
                style="border:none;background:transparent;font-size:28px;line-height:1;color:#94a3b8;cursor:pointer;">&times;</button>
        </div>

        <form id="uUserForm" action="dashboard.php?page=pengguna" method="POST" style="padding:22px;"
            autocomplete="off">
            <input type="hidden" name="action" id="formAction" value="create">
            <input type="hidden" name="id" id="uEditId" value="">

            <div class="u-form-group">
                <label>Nama Lengkap <span class="u-req">*</span></label>
                <input type="text" name="nama" id="namaLengkap" placeholder="Contoh: Dr. Ahmad Rahman, M.Kom" required>
                <small>Masukkan nama lengkap beserta gelar akademik</small>
            </div>

            <div class="u-form-group">
                <label>Email <span class="u-req">*</span></label>
                <input type="email" name="email" id="email" placeholder="email@domain.ac.id" required>
                <small>Email institusi untuk komunikasi</small>
            </div>

            <div class="u-form-group">
                <label>Jabatan <span class="u-req">*</span></label>
                <input type="text" name="instansi" id="jabatan" placeholder="Contoh: Dosen - TI / Asisten Lab - IoT"
                    required>
                <small>Sebutkan jabatan dan unit kerja</small>
            </div>

            <div class="u-form-group">
                <label>Role <span class="u-req">*</span></label>
                <select name="role" id="role" required>
                    <option value="">Pilih Role</option>
                    <option value="eksternal">Eksternal</option>
                    <option value="internal">Internal</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                <div class="u-form-group">
                    <label>Username <span class="u-req">*</span></label>
                    <input type="text" name="username" id="username" placeholder="username" required>
                </div>

                <div class="u-form-group">
                    <label>Password <span class="u-req" id="uPassReq">*</span></label>
                    <input type="password" name="password" id="password" placeholder="••••••••">
                    <small id="uPassHelp">Min. 8 karakter (Wajib saat tambah)</small>
                </div>
            </div>

            <!-- Status Checkbox -->
            <div class="u-form-group">
                <label style="display:inline-flex; align-items:center; gap:8px;">
                    <input type="checkbox" name="status" id="statusCheckbox" value="aktif" checked style="width:auto;">
                    Status Aktif
                </label>
            </div>

            <div
                style="padding-top:16px;margin-top:16px;border-top:1px solid #e5e7eb;display:flex;justify-content:flex-end;gap:10px;">
                <button type="button" onclick="closeUserModal()" class="u-btn u-btn-cancel">Batal</button>
                <button type="submit" class="u-btn u-btn-primary" id="uSubmitBtn">Simpan Pengguna</button>
            </div>
        </form>
    </div>
</div>

<style>
    .u-filter-btn {
        padding: 8px 14px;
        border: 1px solid #e5e7eb;
        background: #fff;
        border-radius: 999px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
    }

    .u-filter-btn.active {
        background: #0f172a;
        color: #fff;
        border-color: #0f172a;
    }

    .u-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
    }

    .u-b-eksternal {
        background: #dbeafe;
        color: #2563eb;
    }

    .u-b-internal {
        background: #dcfce7;
        color: #059669;
    }

    .u-b-admin {
        background: #ffedd5;
        color: #f97316;
    }

    .u-b-aktif {
        background: #dcfce7;
        color: #059669;
    }

    .u-b-nonaktif {
        background: #fee2e2;
        color: #dc2626;
    }

    .u-actions {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }

    .u-act {
        padding: 6px 12px;
        border: none;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 800;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .u-edit {
        background: #3b82f6;
        color: #fff;
    }

    .u-del {
        background: #ef4444;
        color: #fff;
    }

    .u-form-group {
        margin-bottom: 14px;
    }

    .u-form-group label {
        display: block;
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .u-req {
        color: #dc2626;
        margin-left: 4px;
    }

    .u-form-group input,
    .u-form-group select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        outline: none;
    }

    .u-form-group input:focus,
    .u-form-group select:focus {
        border-color: #3b82f6;
    }

    .u-form-group small {
        display: block;
        margin-top: 6px;
        color: #64748b;
        font-size: 12px;
    }

    .u-btn {
        padding: 10px 14px;
        border-radius: 10px;
        font-weight: 900;
        cursor: pointer;
    }

    .u-btn-cancel {
        background: #fff;
        border: 1px solid #e5e7eb;
        color: #334155;
    }

    .u-btn-primary {
        background: #3b82f6;
        border: none;
        color: #fff;
    }
</style>

<script>
    const el = (id) => document.getElementById(id);

    // Client-side Filter Logic
    document.querySelectorAll('.u-filter-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.u-filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const filter = this.getAttribute('data-filter') || 'semua';

            document.querySelectorAll('.user-row').forEach(row => {
                if (filter === 'semua' || row.getAttribute('data-role') === filter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });

    // Client-side Search Logic
    el('uSearchInput')?.addEventListener('input', function () {
        const term = this.value.toLowerCase();
        document.querySelectorAll('.user-row').forEach(row => {
            const name = row.querySelector('.u-name').textContent.toLowerCase();
            const email = row.querySelector('.u-email').textContent.toLowerCase();
            const instansi = row.querySelector('.u-instansi').textContent.toLowerCase();

            if (name.includes(term) || email.includes(term) || instansi.includes(term)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Modal Logic
    window.openUserModal = function (mode, data = null) {
        const overlay = el('uModalOverlay');
        const title = el('uModalTitle');
        const submit = el('uSubmitBtn');
        const form = el('uUserForm');
        const action = el('formAction');
        const editId = el('uEditId');

        const pass = el('password');
        const passReq = el('uPassReq');
        const passHelp = el('uPassHelp');

        if (!overlay) return;
        overlay.style.display = 'flex';
        form.reset();

        if (mode === 'add') {
            title.textContent = 'Tambah Pengguna Baru';
            submit.textContent = 'Simpan Pengguna';
            action.value = 'create';
            editId.value = '';

            pass.required = true;
            passReq.style.display = 'inline';
            passHelp.textContent = 'Min. 8 karakter (Wajib)';
        }
        else if (mode === 'edit' && data) {
            title.textContent = 'Edit Pengguna';
            submit.textContent = 'Update Pengguna';
            action.value = 'update';
            editId.value = data.id;

            el('namaLengkap').value = data.nama;
            el('email').value = data.email;
            el('jabatan').value = data.instansi;
            el('role').value = data.role;
            el('username').value = data.username;
            el('statusCheckbox').checked = (data.status === 'aktif');

            pass.required = false;
            passReq.style.display = 'none';
            passHelp.textContent = 'Kosongkan jika tidak ingin mengubah password';
        }
    }

    window.closeUserModal = function () {
        const overlay = el('uModalOverlay');
        if (overlay) overlay.style.display = 'none';
    }

    el('uModalOverlay')?.addEventListener('click', function (e) {
        if (e.target === this) closeUserModal();
    });
</script>