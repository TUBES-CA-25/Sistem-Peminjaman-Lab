<?php
// app/views/pages/admin/data_ruangan_content.php

/** 
 * $data['ruangan'] sudah tersedia dari RuanganController via dashboard.php 
 */
$labs = $data['ruangan'] ?? [];
?>

<!-- Content Header -->
<div
    style="background: linear-gradient(135deg, #122E4F 0%, #1F45AC 100%); padding: 2.5rem; border-radius: 12px; margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1); gap: 1rem;">
    <div>
        <h1 style="color: white; font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">Data Ruangan Laboratorium
        </h1>
        <p style="color: rgba(255,255,255,0.9); font-size: 1rem;">Kelola informasi ruangan, fasilitas, dan status
            laboratorium.</p>
    </div>

    <button type="button" onclick="openModal('add')"
        style="background-color: white; color: #1F45AC; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.3s; white-space: nowrap;">
        <i class="fas fa-plus"></i>
        Tambah Ruangan
    </button>
</div>

<!-- Notification -->
<?php if (isset($_GET['status']) && isset($_GET['msg'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert"
        style="border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <i class="fas fa-check-circle me-2"></i> Data berhasil <strong><?= htmlspecialchars($_GET['msg']) ?>!</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Lab Cards Grid -->
<div id="labGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem;">
    <?php foreach ($labs as $lab): ?>
        <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: transform 0.3s, box-shadow 0.3s;"
            onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.15)'"
            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)'">

            <div style="position: relative;">
                <?php
                // Handle image: Check if it's base64 or path
                $imgSrc = $lab['gambar'] ? $lab['gambar'] : '../../public/img/default-lab.jpg';
                ?>
                <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($lab['nama_ruangan']) ?>"
                    style="width: 100%; height: 200px; object-fit: cover;">
                <span
                    style="position: absolute; top: 12px; right: 12px; background: <?= $lab['status'] ? '#10b981' : '#EF4444' ?>; color: white; padding: 0.375rem 0.875rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 600; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    <?= $lab['status'] ? 'Tersedia' : 'Tidak Tersedia' ?>
                </span>
            </div>

            <div style="padding: 1.5rem;">
                <h3 style="font-size: 1.25rem; font-weight: 700; color: #122E4F; margin-bottom: 1rem;">
                    <?= htmlspecialchars($lab['nama_ruangan']) ?></h3>

                <div
                    style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 1.25rem; color: #6b7280; font-size: 0.875rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-users" style="width: 20px; color: #1F45AC;"></i>
                        <span>Kapasitas: <?= htmlspecialchars($lab['kapasitas']) ?> orang</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-map-marker-alt" style="width: 20px; color: #1F45AC;"></i>
                        <span><?= htmlspecialchars($lab['lokasi']) ?></span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-user-tie" style="width: 20px; color: #1F45AC;"></i>
                        <span><?= htmlspecialchars($lab['pic']) ?></span>
                    </div>
                </div>

                <div style="display: flex; gap: 0.75rem;">
                    <button type="button" onclick='editLab(<?= json_encode($lab) ?>)' class="btn btn-primary flex-grow-1"
                        style="background-color: #3B82F6; border:none; font-weight:600;">
                        <i class="fas fa-edit me-1"></i> Edit
                    </button>

                    <form action="dashboard.php?page=ruangan" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus ruangan ini?');"
                        style="flex:1; display:flex;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $lab['id'] ?>">
                        <button type="submit" class="btn btn-danger w-100"
                            style="background-color: #EF4444; border:none; font-weight:600;">
                            <i class="fas fa-trash me-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Modal Tambah/Edit -->
<div id="modalOverlay"
    style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 2rem;">
    <div
        style="background: white; border-radius: 8px; width: 100%; max-width: 1100px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">

        <!-- Pastikan action ke page yang benar -->
        <form id="labForm" action="dashboard.php?page=ruangan" method="POST" style="padding: 2.5rem 3rem;">
            <input type="hidden" name="action" id="formAction" value="create">
            <input type="hidden" name="id" id="editIndex" value="">

            <!-- Header -->
            <div style="margin-bottom: 2rem;">
                <h2 id="modalTitle" style="font-size: 1.75rem; font-weight: 700; color: #1a1a1a; margin: 0 0 0.5rem 0;">
                    Informasi Ruangan</h2>
                <p style="color: #6b7280; font-size: 0.95rem; margin: 0;">Lengkapi semua informasi ruangan laboratorium
                </p>
            </div>

            <!-- Upload Foto -->
            <div style="margin-bottom: 2rem;">
                <label
                    style="display: block; margin-bottom: 0.75rem; font-weight: 600; color: #1a1a1a; font-size: 0.9rem;">
                    Foto Ruangan <span style="color: #EF4444;">*</span>
                </label>

                <div id="uploadArea"
                    style="border: 2px dashed #d1d5db; border-radius: 8px; padding: 3rem 2rem; text-align: center; background: #fafafa; position: relative; transition: all 0.3s; cursor: pointer;"
                    onclick="document.getElementById('labImageFile').click()"
                    ondragover="event.preventDefault(); this.style.borderColor='#3B82F6'; this.style.background='#eff6ff';"
                    ondragleave="this.style.borderColor='#d1d5db'; this.style.background='#fafafa';"
                    ondrop="event.preventDefault(); this.style.borderColor='#d1d5db'; this.style.background='#fafafa'; handleFileDrop(event);">

                    <div id="imagePreview" style="display: none;">
                        <img id="previewImg" src="" alt="preview"
                            style="max-width: 100%; max-height: 200px; border-radius: 8px; object-fit: cover; margin-bottom: 1rem;">
                        <p id="fileName" style="color: #6b7280; font-size: 0.875rem; margin: 0;"></p>
                    </div>

                    <div id="uploadPlaceholder">
                        <i class="fas fa-cloud-upload-alt"
                            style="font-size: 3rem; color: #d1d5db; margin-bottom: 1rem;"></i>
                        <p style="color: #374151; margin: 0 0 0.5rem 0; font-weight: 500;">Klik untuk upload atau drag
                            &amp; drop</p>
                        <p style="color: #9ca3af; margin: 0; font-size: 0.875rem;">PNG, JPG, JPEG (Max. 5MB)</p>
                    </div>

                    <input type="file" id="labImageFile" accept="image/png,image/jpeg,image/jpg" style="display: none;"
                        onchange="handleFileSelect(event)">
                    <!-- Hidden field to store Base64 string -->
                    <input type="hidden" name="gambar_base64" id="labImage">

                    <button type="button" id="changePhotoBtn"
                        style="display: none; position: absolute; top: 1rem; right: 1rem; background: #122E4F; color: white; padding: 0.625rem 1.25rem; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 0.875rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"
                        onclick="event.stopPropagation(); document.getElementById('labImageFile').click();">
                        <i class="fas fa-edit"></i> Ubah Foto
                    </button>
                </div>
            </div>

            <!-- Grid 2 Kolom: Nama & Kapasitas -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label
                        style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1a1a1a; font-size: 0.9rem;">
                        Nama Ruangan <span style="color: #EF4444;">*</span>
                    </label>
                    <input type="text" name="nama_ruangan" id="labName" required
                        style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; outline: none;"
                        placeholder="Laboratorium Startup">
                </div>

                <div>
                    <label
                        style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1a1a1a; font-size: 0.9rem;">
                        Kapasitas <span style="color: #EF4444;">*</span>
                    </label>
                    <input type="number" name="kapasitas" id="labCapacity" required min="1"
                        style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; outline: none;"
                        placeholder="36">
                </div>
            </div>

            <!-- Grid 2 Kolom: Lokasi & Koordinator -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label
                        style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1a1a1a; font-size: 0.9rem;">
                        Lokasi <span style="color: #EF4444;">*</span>
                    </label>
                    <input type="text" name="lokasi" id="labLocation" required
                        style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; outline: none;"
                        placeholder="Gedung F Lantai 3">
                </div>

                <div>
                    <label
                        style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1a1a1a; font-size: 0.9rem;">
                        Koordinator Lab <span style="color: #EF4444;">*</span>
                    </label>
                    <select name="pic" id="labPIC" required
                        style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; cursor: pointer; outline: none; background: white; white-space: nowrap;">
                        <option value="">Pilih Koordinator</option>
                        <option value="Dr. Budi Santoso">Dr. Budi Santoso</option>
                        <option value="Siti Aminah, M.Kom">Siti Aminah, M.Kom</option>
                    </select>
                </div>
            </div>

            <!-- Email Koordinator -->
            <div style="margin-bottom: 1.5rem;">
                <label
                    style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1a1a1a; font-size: 0.9rem;">
                    Email Koordinator <span style="color: #EF4444;">*</span>
                </label>
                <input type="email" name="email_pic" id="labEmail" required
                    style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; outline: none;"
                    placeholder="koordinator@iclabs.ac.id">
            </div>

            <!-- Fasilitas -->
            <div style="margin-bottom: 1.5rem;">
                <label
                    style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1a1a1a; font-size: 0.9rem;">
                    Fasilitas
                </label>
                <textarea name="fasilitas" id="labFacilities" rows="3"
                    style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; outline: none; resize: vertical; font-family: 'Inter', sans-serif;"
                    placeholder="Proyektor, Whiteboard, AC, Komputer dll"></textarea>
            </div>

            <!-- Deskripsi -->
            <div style="margin-bottom: 2rem;">
                <label
                    style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1a1a1a; font-size: 0.9rem;">
                    Deskripsi Ruangan
                </label>
                <textarea name="deskripsi" id="labDescription" rows="4"
                    style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; outline: none; resize: vertical; font-family: 'Inter', sans-serif;"
                    placeholder="Deskripsi singkat ruangan..."></textarea>
            </div>

            <!-- Status Ketersediaan -->
            <div style="margin-bottom: 2.5rem;">
                <label
                    style="display: block; margin-bottom: 0.75rem; font-weight: 600; color: #1a1a1a; font-size: 0.9rem;">
                    Status Ketersediaan
                </label>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <label
                        style="position: relative; display: inline-block; width: 48px; height: 26px; cursor: pointer;">
                        <input type="checkbox" name="status" id="labStatus" checked
                            style="opacity: 0; width: 0; height: 0;">
                        <span id="toggleSlider"
                            style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: #10b981; border-radius: 26px; transition: 0.3s;"></span>
                        <span id="toggleCircle"
                            style="position: absolute; left: 3px; bottom: 3px; width: 20px; height: 20px; background: white; border-radius: 50%; transition: 0.3s;"></span>
                    </label>
                    <span id="statusText" style="color: #1a1a1a; font-size: 0.9rem; font-weight: 500;">Ruangan tersedia
                        untuk dipinjam</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div
                style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                <button type="button" onclick="closeModal()"
                    style="padding: 0.75rem 1.5rem; border: 1px solid #d1d5db; background: white; color: #374151; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-times"></i> Batal
                </button>

                <button type="submit"
                    style="padding: 0.75rem 1.5rem; border: none; background: #3B82F6; color: white; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-check"></i> <span id="submitBtnText">Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    #labStatus:checked+#toggleSlider {
        background: #10b981;
    }

    #labStatus:not(:checked)+#toggleSlider {
        background: #d1d5db;
    }

    #labStatus:checked~#toggleCircle {
        transform: translateX(22px);
    }
</style>

<script>
    function $(id) { return document.getElementById(id); }

    /** Upload handlers (Membaca file jadi Base64 untuk input hidden) */
    function handleFileSelect(event) {
        const file = event?.target?.files?.[0];
        if (!file) return;

        if (!file.type.match('image/(png|jpeg|jpg)')) { alert('Format file harus PNG, JPG, atau JPEG!'); return; }
        if (file.size > 5 * 1024 * 1024) { alert('Ukuran file maksimal 5MB!'); return; }

        const reader = new FileReader();
        reader.onload = function (e) {
            const previewImg = $('previewImg');
            const imagePreview = $('imagePreview');
            const uploadPlaceholder = $('uploadPlaceholder');
            const changePhotoBtn = $('changePhotoBtn');
            const fileName = $('fileName');
            const labImage = $('labImage'); // INPUT HIDDEN

            if (previewImg) previewImg.src = e.target.result;
            if (imagePreview) imagePreview.style.display = 'block';
            if (uploadPlaceholder) uploadPlaceholder.style.display = 'none';
            if (changePhotoBtn) changePhotoBtn.style.display = 'block';
            if (fileName) fileName.textContent = file.name;
            if (labImage) labImage.value = e.target.result; // Set Base64 string
        };
        reader.readAsDataURL(file);
    }

    function handleFileDrop(event) {
        const file = event?.dataTransfer?.files?.[0];
        if (!file) return;

        const input = $('labImageFile');
        if (input) input.files = event.dataTransfer.files;
        handleFileSelect({ target: { files: [file] } });
    }

    function resetUploadArea() {
        const imagePreview = $('imagePreview');
        const uploadPlaceholder = $('uploadPlaceholder');
        const changePhotoBtn = $('changePhotoBtn');
        const fileInput = $('labImageFile');
        const labImage = $('labImage');

        if (imagePreview) imagePreview.style.display = 'none';
        if (uploadPlaceholder) uploadPlaceholder.style.display = 'block';
        if (changePhotoBtn) changePhotoBtn.style.display = 'none';
        if (fileInput) fileInput.value = '';
        if (labImage) labImage.value = '';
    }

    /** Modal Logic */
    function openModal(mode, data = null) {
        const overlay = $('modalOverlay');
        const form = $('labForm');
        const submitBtnText = $('submitBtnText');
        const title = $('modalTitle');
        const formAction = $('formAction');
        const editIndex = $('editIndex');
        const fileInput = $('labImageFile');

        if (!overlay || !form) return;

        overlay.style.display = 'flex';

        if (mode === 'add') {
            title.textContent = 'Informasi Ruangan';
            submitBtnText.textContent = 'Tambah Ruangan';
            formAction.value = 'create';
            editIndex.value = '';
            form.reset();
            resetUploadArea();

            if ($('labStatus')) $('labStatus').checked = true;
            if (fileInput) fileInput.required = true;
            updateToggleStatus();
            return;
        }

        if (mode === 'edit' && data) {
            title.textContent = 'Edit Ruangan';
            submitBtnText.textContent = 'Simpan Perubahan';
            formAction.value = 'update';
            editIndex.value = data.id;

            $('labName').value = data.nama_ruangan;
            $('labCapacity').value = data.kapasitas;
            $('labLocation').value = data.lokasi;
            $('labPIC').value = data.pic;
            $('labEmail').value = data.email_pic;
            $('labFacilities').value = data.fasilitas;
            $('labDescription').value = data.deskripsi;
            $('labImage').value = data.gambar;

            // Status Checkbox
            $('labStatus').checked = (data.status == 1);

            if (fileInput) fileInput.required = false;

            if (data.gambar) {
                $('previewImg').src = data.gambar;
                $('imagePreview').style.display = 'block';
                $('uploadPlaceholder').style.display = 'none';
                $('changePhotoBtn').style.display = 'block';
                $('fileName').textContent = 'Gambar saat ini';
            } else {
                resetUploadArea();
            }

            updateToggleStatus();
        }
    }

    function closeModal() {
        const overlay = $('modalOverlay');
        if (overlay) overlay.style.display = 'none';
    }

    function editLab(data) { openModal('edit', data); }

    /** Toggle status UI */
    function updateToggleStatus() {
        const checkbox = $('labStatus');
        const slider = $('toggleSlider');
        const statusText = $('statusText');
        if (!checkbox || !slider || !statusText) return;

        if (checkbox.checked) {
            slider.style.background = '#10b981';
            statusText.textContent = 'Ruangan tersedia untuk dipinjam';
        } else {
            slider.style.background = '#d1d5db';
            statusText.textContent = 'Ruangan tidak tersedia';
        }
    }

    // Bind events
    const statusEl = $('labStatus');
    if (statusEl) statusEl.addEventListener('change', updateToggleStatus);

    const overlay = $('modalOverlay');
    if (overlay) overlay.addEventListener('click', function (e) {
        if (e.target === this) closeModal();
    });
</script>