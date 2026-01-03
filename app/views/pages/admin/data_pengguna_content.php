<?php

?>

<!-- HERO / HEADER -->
<div style="background: linear-gradient(135deg, #122E4F 0%, #1F45AC 100%); padding: 2.5rem; border-radius: 12px; margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1); gap: 1rem;">
    <div>
        <h1 style="color: white; font-size: 2rem; font-weight: 800; margin: 0 0 0.5rem 0;">Data Pengguna</h1>
        <p style="color: rgba(255,255,255,0.9); font-size: 1rem; margin:0;">Kelola pengguna berdasarkan kategori: Internal, Eksternal, dan Admin.</p>
    </div>

    <button type="button" onclick="openUserModal('add')"
        style="background-color: white; color: #1F45AC; padding: 0.75rem 1.25rem; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); white-space: nowrap;">
        <i class="fas fa-user-plus"></i>
        Tambah Pengguna
    </button>
</div>

<!-- FILTER + SEARCH -->
<div style="background:#fff;border-radius:12px;padding:1rem 1.25rem;box-shadow:0 1px 3px rgba(0,0,0,0.08);display:flex;gap:1rem;align-items:center;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;">
    <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
        <button class="u-filter-btn active" data-filter="semua" type="button">Semua</button>
        <button class="u-filter-btn" data-filter="eksternal" type="button">Eksternal</button>
        <button class="u-filter-btn" data-filter="internal" type="button">Internal</button>
        <button class="u-filter-btn" data-filter="admin" type="button">Admin</button>
    </div>

    <div style="min-width:280px; max-width:420px; flex:1; display:flex; justify-content:flex-end;">
        <div style="position:relative;width:100%;max-width:420px;">
            <i class="fas fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9ca3af;"></i>
            <input id="uSearchInput" type="text" placeholder="Cari nama / email / jabatan..."
                style="width:100%;padding:10px 12px 10px 36px;border:1px solid #e5e7eb;border-radius:10px;outline:none;">
        </div>
    </div>
</div>

<!-- STATS -->
<div style="display:grid;grid-template-columns:repeat(4,minmax(200px,1fr));gap:1rem;margin-bottom:1.5rem;">
    <div class="u-stat-card">
        <div class="u-stat-left">
            <div class="u-stat-title">Total Ditampilkan</div>
            <div class="u-stat-number" id="uCountTotal">0</div>
        </div>
        <div class="u-stat-icon" style="background:#e0e7ff;color:#1F45AC;"><i class="fas fa-users"></i></div>
    </div>

    <div class="u-stat-card">
        <div class="u-stat-left">
            <div class="u-stat-title">Eksternal Ditampilkan</div>
            <div class="u-stat-number" id="uCountEksternal">0</div>
        </div>
        <div class="u-stat-icon" style="background:#dbeafe;color:#2563eb;"><i class="fas fa-globe"></i></div>
    </div>

    <div class="u-stat-card">
        <div class="u-stat-left">
            <div class="u-stat-title">Internal Ditampilkan</div>
            <div class="u-stat-number" id="uCountInternal">0</div>
        </div>
        <div class="u-stat-icon" style="background:#dcfce7;color:#059669;"><i class="fas fa-building"></i></div>
    </div>

    <div class="u-stat-card">
        <div class="u-stat-left">
            <div class="u-stat-title">Admin Ditampilkan</div>
            <div class="u-stat-number" id="uCountAdmin">0</div>
        </div>
        <div class="u-stat-icon" style="background:#ffedd5;color:#f97316;"><i class="fas fa-user-shield"></i></div>
    </div>
</div>

<!-- TABLE -->
<div style="background:#fff;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.08);overflow:hidden;">
    <div style="padding:1rem 1.25rem;border-bottom:1px solid #eef2f7;display:flex;justify-content:space-between;align-items:center;">
        <div style="font-weight:800;color:#0f172a;">Daftar Pengguna</div>
        <div style="color:#475569;font-size:0.9rem;font-weight:600;">Filter: <span id="uFilterLabel">Semua</span></div>
    </div>

    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;min-width:900px;">
            <thead style="background:#f8fafc;">
                <tr>
                    <th style="text-align:left;padding:12px 14px;font-size:12px;color:#64748b;font-weight:800;letter-spacing:.04em;">No</th>
                    <th style="text-align:left;padding:12px 14px;font-size:12px;color:#64748b;font-weight:800;letter-spacing:.04em;">Nama</th>
                    <th style="text-align:left;padding:12px 14px;font-size:12px;color:#64748b;font-weight:800;letter-spacing:.04em;">Email</th>
                    <th style="text-align:left;padding:12px 14px;font-size:12px;color:#64748b;font-weight:800;letter-spacing:.04em;">Jabatan</th>
                    <th style="text-align:left;padding:12px 14px;font-size:12px;color:#64748b;font-weight:800;letter-spacing:.04em;">Kategori</th>
                    <th style="text-align:left;padding:12px 14px;font-size:12px;color:#64748b;font-weight:800;letter-spacing:.04em;">Status</th>
                    <th style="text-align:right;padding:12px 14px;font-size:12px;color:#64748b;font-weight:800;letter-spacing:.04em;">Aksi</th>
                </tr>
            </thead>
            <tbody id="uTableBody"></tbody>
        </table>
    </div>
</div>

<!-- MODAL -->
<div id="uModalOverlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center; padding:2rem;">
    <div style="background:#fff;border-radius:12px;width:100%;max-width:720px;max-height:90vh;overflow:auto;box-shadow:0 20px 60px rgba(0,0,0,0.3);">
        <div style="padding:18px 22px;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;">
            <h2 id="uModalTitle" style="margin:0;font-size:18px;font-weight:800;color:#0f172a;">Tambah Pengguna Baru</h2>
            <button type="button" onclick="closeUserModal()" style="border:none;background:transparent;font-size:28px;line-height:1;color:#94a3b8;cursor:pointer;">&times;</button>
        </div>

        <form id="uUserForm" style="padding:22px;" autocomplete="off">
            <input type="hidden" id="uEditIndex" value="">

            <div class="u-form-group">
                <label>Nama Lengkap <span class="u-req">*</span></label>
                <input type="text" id="namaLengkap" placeholder="Contoh: Dr. Ahmad Rahman, M.Kom" required>
                <small>Masukkan nama lengkap beserta gelar akademik</small>
            </div>

            <div class="u-form-group">
                <label>Email <span class="u-req">*</span></label>
                <input type="email" id="email" placeholder="email@domain.ac.id" required>
                <small>Email institusi untuk komunikasi</small>
            </div>

            <div class="u-form-group">
                <label>Jabatan <span class="u-req">*</span></label>
                <input type="text" id="jabatan" placeholder="Contoh: Dosen - TI / Asisten Lab - IoT" required>
                <small>Sebutkan jabatan dan unit kerja</small>
            </div>

            <div class="u-form-group">
                <label>Role <span class="u-req">*</span></label>
                <select id="role" required>
                    <option value="">Pilih Role</option>
                    <option value="eksternal">Eksternal</option>
                    <option value="internal">Internal</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                <div class="u-form-group">
                    <label>Username <span class="u-req">*</span></label>
                    <input type="text" id="username" placeholder="username" required>
                    <small>Untuk login ke sistem</small>
                </div>

                <div class="u-form-group">
                    <label>Password <span class="u-req" id="uPassReq">*</span></label>
                    <input type="password" id="password" placeholder="••••••••" required>
                    <small id="uPassHelp">Min. 8 karakter</small>
                </div>
            </div>

            <div style="background:#eff6ff;border-left:3px solid #3b82f6;padding:12px 14px;border-radius:10px;margin-top:6px;">
                <div style="display:flex;gap:10px;align-items:flex-start;color:#1e40af;font-size:13px;">
                    <i class="fas fa-info-circle" style="margin-top:2px;"></i>
                    <div><strong>Informasi:</strong> Username dan Password diberikan ke user untuk login. Pastikan mencatatnya.</div>
                </div>
            </div>

            <div style="padding-top:16px;margin-top:16px;border-top:1px solid #e5e7eb;display:flex;justify-content:flex-end;gap:10px;">
                <button type="button" onclick="closeUserModal()" class="u-btn u-btn-cancel">Batal</button>
                <button type="submit" class="u-btn u-btn-primary" id="uSubmitBtn">Simpan Pengguna</button>
            </div>
        </form>
    </div>
</div>

<style>
    .u-filter-btn{
        padding:8px 14px;border:1px solid #e5e7eb;background:#fff;border-radius:999px;
        cursor:pointer;font-size:13px;font-weight:700;color:#0f172a;
    }
    .u-filter-btn.active{ background:#0f172a; color:#fff; border-color:#0f172a; }

    .u-stat-card{
        background:#fff;border-radius:12px;padding:14px 16px;
        box-shadow:0 1px 3px rgba(0,0,0,0.08);
        display:flex;align-items:center;justify-content:space-between;gap:10px;
    }
    .u-stat-left{ display:flex; flex-direction:column; gap:4px; }
    .u-stat-title{ font-size:12px; color:#64748b; font-weight:800; }
    .u-stat-number{ font-size:28px; color:#0f172a; font-weight:900; line-height:1; }
    .u-stat-icon{ width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:18px; }

    .u-badge{
        display:inline-flex; align-items:center; justify-content:center;
        padding:4px 10px; border-radius:999px; font-size:12px; font-weight:800;
    }
    .u-b-eksternal{ background:#dbeafe; color:#2563eb; }
    .u-b-internal{ background:#dcfce7; color:#059669; }
    .u-b-admin{ background:#ffedd5; color:#f97316; }
    .u-b-aktif{ background:#dcfce7; color:#059669; }
    .u-b-nonaktif{ background:#fee2e2; color:#dc2626; }

    .u-actions{ display:flex; gap:8px; justify-content:flex-end; }
    .u-act{
        padding:6px 12px; border:none; border-radius:8px;
        font-size:12px; font-weight:800; cursor:pointer;
        display:inline-flex; align-items:center; gap:6px;
    }
    .u-edit{ background:#3b82f6; color:#fff; }
    .u-del{ background:#ef4444; color:#fff; }

    .u-form-group{ margin-bottom:14px; }
    .u-form-group label{ display:block; font-size:14px; font-weight:800; color:#0f172a; margin-bottom:6px; }
    .u-req{ color:#dc2626; margin-left:4px; }
    .u-form-group input, .u-form-group select{
        width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; outline:none;
    }
    .u-form-group input:focus, .u-form-group select:focus{ border-color:#3b82f6; }
    .u-form-group small{ display:block; margin-top:6px; color:#64748b; font-size:12px; }

    .u-btn{ padding:10px 14px; border-radius:10px; font-weight:900; cursor:pointer; }
    .u-btn-cancel{ background:#fff; border:1px solid #e5e7eb; color:#334155; }
    .u-btn-primary{ background:#3b82f6; border:none; color:#fff; }
</style>

<script>
(function(){
    // ===== DATA =====
    const users = [
        { name:'AKP Ahmad Kurniawan', email:'ahmad.kurniawan@polri.go.id', instansi:'Polda Metro Jaya', role:'eksternal', tanggal:'15 Des 2025', status:'aktif', username:'ahmad.kurniawan' },
        { name:'IPDA Budi Santoso', email:'budi.santoso@polri.go.id', instansi:'Polres Jakarta Selatan', role:'eksternal', tanggal:'14 Des 2025', status:'aktif', username:'budi.santoso' },
        { name:'Admin Utama', email:'admin@iclabs.ac.id', instansi:'Admin Sistem', role:'admin', tanggal:'01 Des 2025', status:'aktif', username:'admin.utama' },
        { name:'Dinda Lestari', email:'dinda@external.com', instansi:'Mitra Eksternal', role:'eksternal', tanggal:'12 Des 2025', status:'aktif', username:'dinda.lestari' },
        { name:'Bima Saputra', email:'bima@iclabs.ac.id', instansi:'Dosen', role:'internal', tanggal:'10 Des 2025', status:'aktif', username:'bima.saputra' },
        { name:'Eka Pratama', email:'eka@iclabs.ac.id', instansi:'Asisten', role:'internal', tanggal:'09 Des 2025', status:'aktif', username:'eka.pratama' }
    ];

    let currentFilter = 'semua';
    let searchTerm = '';
    let editIndex = -1;

    // ===== HELPERS =====
    const el = (id) => document.getElementById(id);

    function roleLabel(role){
        if(role === 'eksternal') return 'Eksternal';
        if(role === 'internal') return 'Internal';
        if(role === 'admin') return 'Admin';
        return role;
    }
    function roleBadgeClass(role){
        if(role === 'eksternal') return 'u-b-eksternal';
        if(role === 'internal') return 'u-b-internal';
        if(role === 'admin') return 'u-b-admin';
        return '';
    }
    function statusBadge(status){
        return status === 'aktif'
            ? `<span class="u-badge u-b-aktif">Aktif</span>`
            : `<span class="u-badge u-b-nonaktif">Nonaktif</span>`;
    }

    function getFilteredUsers(){
        return users.filter(u => {
            const matchesFilter = currentFilter === 'semua' || u.role === currentFilter;
            const s = searchTerm.toLowerCase();
            const matchesSearch =
                u.name.toLowerCase().includes(s) ||
                u.email.toLowerCase().includes(s) ||
                (u.instansi || '').toLowerCase().includes(s);
            return matchesFilter && matchesSearch;
        });
    }

    function updateStats(filtered){
        const total = filtered.length;
        const eksternal = filtered.filter(u => u.role === 'eksternal').length;
        const internal = filtered.filter(u => u.role === 'internal').length;
        const admin = filtered.filter(u => u.role === 'admin').length;

        el('uCountTotal').textContent = total;
        el('uCountEksternal').textContent = eksternal;
        el('uCountInternal').textContent = internal;
        el('uCountAdmin').textContent = admin;
    }

    function renderTable(){
        const tbody = el('uTableBody');
        if(!tbody) return;

        const filtered = getFilteredUsers();
        tbody.innerHTML = filtered.map((u, idx) => {
            const originalIndex = users.indexOf(u);
            return `
                <tr style="border-top:1px solid #f1f5f9;">
                    <td style="padding:14px;font-weight:800;color:#0f172a;">${idx+1}</td>
                    <td style="padding:14px;font-weight:800;color:#0f172a;">${u.name}</td>
                    <td style="padding:14px;color:#334155;font-weight:700;">${u.email}</td>
                    <td style="padding:14px;color:#334155;font-weight:700;">${u.instansi || '-'}</td>
                    <td style="padding:14px;">
                        <span class="u-badge ${roleBadgeClass(u.role)}">${roleLabel(u.role)}</span>
                    </td>
                    <td style="padding:14px;">
                        ${statusBadge(u.status)}
                    </td>
                    <td style="padding:14px;">
                        <div class="u-actions">
                            <button type="button" class="u-act u-edit" onclick="openUserModal('edit', ${originalIndex})">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button type="button" class="u-act u-del" onclick="deleteUser(${originalIndex})">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');

        el('uFilterLabel').textContent = roleLabel(currentFilter === 'semua' ? 'Semua' : currentFilter);
        updateStats(filtered);
    }

    // ===== MODAL =====
    window.openUserModal = function(mode, index = -1){
        const overlay = el('uModalOverlay');
        const title = el('uModalTitle');
        const submit = el('uSubmitBtn');
        const form = el('uUserForm');
        const pass = el('password');
        const passReq = el('uPassReq');
        const passHelp = el('uPassHelp');

        if(!overlay || !form) return;

        form.reset();
        overlay.style.display = 'flex';
        editIndex = index;

        if(mode === 'add'){
            title.textContent = 'Tambah Pengguna Baru';
            submit.textContent = 'Simpan Pengguna';
            el('uEditIndex').value = '';

            // password wajib saat add
            pass.required = true;
            pass.placeholder = '••••••••';
            passHelp.textContent = 'Min. 8 karakter';
            passReq.style.display = 'inline';
            return;
        }

        if(mode === 'edit' && index >= 0 && users[index]){
            const u = users[index];
            title.textContent = 'Edit Pengguna';
            submit.textContent = 'Update Pengguna';
            el('uEditIndex').value = String(index);

            el('namaLengkap').value = u.name || '';
            el('email').value = u.email || '';
            el('jabatan').value = u.instansi || '';
            el('role').value = u.role || '';
            el('username').value = u.username || '';

            // password opsional saat edit
            pass.required = false;
            pass.value = '';
            pass.placeholder = 'Kosongkan jika tidak ingin mengubah';
            passHelp.textContent = 'Kosongkan jika tidak mengubah password';
            passReq.style.display = 'none';
        }
    }

    window.closeUserModal = function(){
        const overlay = el('uModalOverlay');
        if(overlay) overlay.style.display = 'none';
        editIndex = -1;

        // reset aturan password
        const pass = el('password');
        const passReq = el('uPassReq');
        const passHelp = el('uPassHelp');
        if(pass){
            pass.required = true;
            pass.placeholder = '••••••••';
        }
        if(passReq) passReq.style.display = 'inline';
        if(passHelp) passHelp.textContent = 'Min. 8 karakter';
    }

    window.deleteUser = function(index){
        if(!users[index]) return;
        if(confirm(`Hapus pengguna "${users[index].name}"?`)){
            users.splice(index, 1);
            renderTable();
        }
    }

    // ===== SUBMIT =====
    el('uUserForm')?.addEventListener('submit', function(e){
        e.preventDefault();

        const idxVal = el('uEditIndex')?.value ?? '';
        const isEdit = idxVal !== '';
        const idx = isEdit ? parseInt(idxVal, 10) : -1;

        const name = el('namaLengkap').value.trim();
        const email = el('email').value.trim();
        const instansi = el('jabatan').value.trim();
        const role = el('role').value;
        const username = el('username').value.trim();
        const password = el('password').value;

        // validasi minimal
        if(!name || !email || !instansi || !role || !username){
            alert('Lengkapi field wajib.');
            return;
        }
        if(!isEdit && (!password || password.length < 8)){
            alert('Password minimal 8 karakter.');
            return;
        }
        if(isEdit && password && password.length < 8){
            alert('Password minimal 8 karakter (atau kosongkan).');
            return;
        }

        const tanggal = new Date().toLocaleDateString('id-ID', { day:'2-digit', month:'short', year:'numeric' }).replace('.', '');
        const payload = {
            name, email, instansi, role, username,
            tanggal: isEdit ? (users[idx]?.tanggal ?? tanggal) : tanggal,
            status: isEdit ? (users[idx]?.status ?? 'aktif') : 'aktif'
        };

        if(!isEdit){
            users.push(payload);
        }else if(!Number.isNaN(idx) && users[idx]){
            // password tidak disimpan di demo ini; hanya validasi UI
            users[idx] = { ...users[idx], ...payload };
        }

        closeUserModal();
        renderTable();
    });

    // ===== FILTER BUTTONS =====
    document.querySelectorAll('.u-filter-btn').forEach(btn => {
        btn.addEventListener('click', function(){
            document.querySelectorAll('.u-filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.getAttribute('data-filter') || 'semua';
            renderTable();
        });
    });

    // ===== SEARCH =====
    el('uSearchInput')?.addEventListener('input', function(){
        searchTerm = this.value || '';
        renderTable();
    });

    // close modal when click outside
    el('uModalOverlay')?.addEventListener('click', function(e){
        if(e.target === this) closeUserModal();
    });

    // init
    renderTable();
})();
</script>