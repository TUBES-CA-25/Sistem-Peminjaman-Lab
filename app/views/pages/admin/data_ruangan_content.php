<?php

?>

<!-- Content Header -->
<div style="background: linear-gradient(135deg, #122E4F 0%, #1F45AC 100%); padding: 2.5rem; border-radius: 12px; margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1); gap: 1rem;">
    <div>
        <h1 style="color: white; font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">Data Ruangan Laboratorium</h1>
        <p style="color: rgba(255,255,255,0.9); font-size: 1rem;">Kelola informasi ruangan, fasilitas, dan status laboratorium.</p>
    </div>

    <button type="button"
            onclick="openModal('add')"
            style="background-color: white; color: #1F45AC; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.3s; white-space: nowrap;">
        <i class="fas fa-plus"></i>
        Tambah Ruangan
    </button> 
</div>

<!-- Lab Cards Grid -->
<div id="labGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem;">
    <!-- Cards di-render oleh JavaScript -->
</div>

<!-- Modal Tambah/Edit -->
<div id="modalOverlay"
     style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 2rem;">
    <div style="background: white; border-radius: 8px; width: 100%; max-width: 1100px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">

        <form id="labForm" style="padding: 2.5rem 3rem;">
            <input type="hidden" id="editIndex" value="">

            <!-- Header -->
            <div style="margin-bottom: 2rem;">
                <h2 id="modalTitle" style="font-size: 1.75rem; font-weight: 700; color: #1a1a1a; margin: 0 0 0.5rem 0;">Informasi Ruangan</h2>
                <p style="color: #6b7280; font-size: 0.95rem; margin: 0;">Lengkapi semua informasi ruangan laboratorium</p>
            </div>

            <!-- Upload Foto -->
            <div style="margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 0.75rem; font-weight: 600; color: #1a1a1a; font-size: 0.9rem;">
                    Foto Ruangan <span style="color: #EF4444;">*</span>
                </label>

                <div id="uploadArea"
                     style="border: 2px dashed #d1d5db; border-radius: 8px; padding: 3rem 2rem; text-align: center; background: #fafafa; position: relative; transition: all 0.3s; cursor: pointer;"
                     onclick="document.getElementById('labImageFile').click()"
                     ondragover="event.preventDefault(); this.style.borderColor='#3B82F6'; this.style.background='#eff6ff';"
                     ondragleave="this.style.borderColor='#d1d5db'; this.style.background='#fafafa';"
                     ondrop="event.preventDefault(); this.style.borderColor='#d1d5db'; this.style.background='#fafafa'; handleFileDrop(event);">

                    <div id="imagePreview" style="display: none;">
                        <img id="previewImg" src="" alt="preview" style="max-width: 100%; max-height: 200px; border-radius: 8px; object-fit: cover; margin-bottom: 1rem;">
                        <p id="fileName" style="color: #6b7280; font-size: 0.875rem; margin: 0;"></p>
                    </div>

                    <div id="uploadPlaceholder">
                        <i class="fas fa-cloud-upload-alt" style="font-size: 3rem; color: #d1d5db; margin-bottom: 1rem;"></i>
                        <p style="color: #374151; margin: 0 0 0.5rem 0; font-weight: 500;">Klik untuk upload atau drag &amp; drop</p>
                        <p style="color: #9ca3af; margin: 0; font-size: 0.875rem;">PNG, JPG, JPEG (Max. 5MB)</p>
                    </div>

                    <input type="file" id="labImageFile" accept="image/png,image/jpeg,image/jpg" style="display: none;" onchange="handleFileSelect(event)">
                    <input type="hidden" id="labImage">

                    <button type="button" id="changePhotoBtn"
                            style="display: none; position: absolute; top: 1rem; right: 1rem; background: #122E4F; color: white; padding: 0.625rem 1.25rem; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 0.875rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"
                            onclick="event.stopPropagation(); document.getElementById('labImageFile').click();">
                        <i class="fas fa-edit"></i> Ubah Foto
                    </button>
                </div>

                <small style="color: #6b7280; font-size: 0.8rem; display: block; margin-top: 0.5rem;">
                    Catatan: ini front-end. Foto hanya disimpan di memori browser (data URL), bukan ke server.
                </small>
            </div>

            <!-- Grid 2 Kolom: Nama & Kapasitas -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1a1a1a; font-size: 0.9rem;">
                        Nama Ruangan <span style="color: #EF4444;">*</span>
                    </label>
                    <input type="text" id="labName" required
                           style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; outline: none;"
                           placeholder="Laboratorium Startup">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1a1a1a; font-size: 0.9rem;">
                        Kapasitas <span style="color: #EF4444;">*</span>
                    </label>
                    <input type="number" id="labCapacity" required min="1"
                           style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; outline: none;"
                           placeholder="36">
                    <small style="color: #6b7280; font-size: 0.8rem; display: block; margin-top: 0.25rem;">Jumlah maksimal orang yang dapat menempati ruangan</small>
                </div>
            </div>

            <!-- Grid 2 Kolom: Lokasi & Koordinator -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1a1a1a; font-size: 0.9rem;">
                        Lokasi <span style="color: #EF4444;">*</span>
                    </label>
                    <input type="text" id="labLocation" required
                           style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; outline: none;"
                           placeholder="Gedung F Lantai 3">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1a1a1a; font-size: 0.9rem;">
                        Koordinator Lab <span style="color: #EF4444;">*</span>
                    </label>
                    <select id="labPIC" required
                            style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; cursor: pointer; outline: none; appearance: none; background: white url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%23374151%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22%3e%3cpolyline points=%226 9 12 15 18 9%22%3e%3c/polyline%3e%3c/svg%3e') no-repeat right 0.75rem center; background-size: 1.25rem;">
                        <option value="">Pilih Koordinator</option>
                        <option value="Dr. Budi Santoso">Adam Adnan</option>
                    </select>
                </div>
            </div>

            <!-- Email Koordinator -->
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1a1a1a; font-size: 0.9rem;">
                    Email Koordinator <span style="color: #EF4444;">*</span>
                </label>
                <input type="email" id="labEmail" required
                       style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; outline: none;"
                       placeholder="koordinator@iclabs.ac.id">
            </div>

            <!-- Fasilitas -->
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1a1a1a; font-size: 0.9rem;">
                    Fasilitas
                </label>
                <textarea id="labFacilities" rows="3"
                          style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; outline: none; resize: vertical; font-family: 'Inter', sans-serif;"
                          placeholder="Proyektor, Whiteboard, AC, Komputer dll"></textarea>
                <small style="color: #6b7280; font-size: 0.8rem; display: block; margin-top: 0.25rem;">Contoh: Proyektor, Whiteboard, AC, Komputer, dll.</small>
            </div>

            <!-- Deskripsi -->
            <div style="margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1a1a1a; font-size: 0.9rem;">
                    Deskripsi Ruangan
                </label>
                <textarea id="labDescription" rows="4"
                          style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; outline: none; resize: vertical; font-family: 'Inter', sans-serif;"
                          placeholder="Laboratorium yang dilengkapi dengan peralatan modern untuk mendukung pembelajaran."></textarea>
            </div>

            <!-- Status Ketersediaan -->
            <div style="margin-bottom: 2.5rem;">
                <label style="display: block; margin-bottom: 0.75rem; font-weight: 600; color: #1a1a1a; font-size: 0.9rem;">
                    Status Ketersediaan
                </label>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <label style="position: relative; display: inline-block; width: 48px; height: 26px; cursor: pointer;">
                        <input type="checkbox" id="labStatus" checked style="opacity: 0; width: 0; height: 0;">
                        <span id="toggleSlider" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: #10b981; border-radius: 26px; transition: 0.3s;"></span>
                        <span id="toggleCircle" style="position: absolute; left: 3px; bottom: 3px; width: 20px; height: 20px; background: white; border-radius: 50%; transition: 0.3s;"></span>
                    </label>
                    <span id="statusText" style="color: #1a1a1a; font-size: 0.9rem; font-weight: 500;">Ruangan tersedia untuk dipinjam</span>
                </div>
            </div>

            <!-- Action Buttons -->

            <div style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
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
    #labStatus:checked + #toggleSlider { background: #10b981; }
    #labStatus:not(:checked) + #toggleSlider { background: #d1d5db; }
    #labStatus:checked ~ #toggleCircle { transform: translateX(22px); }
</style>

<script>

function $(id) { return document.getElementById(id); }

/** Labs lengkap 8 item */
let labs = [
  { name:"Laboratorium Startup", capacity:36, location:"Gedung F, Lantai 3", pic:"Dr. Budi Santoso", email:"koordinator@iclabs.ac.id", facilities:"Proyektor, Whiteboard, AC, Komputer dll", description:"Laboratorium yang dilengkapi dengan peralatan modern untuk mendukung pembelajaran startup dan kewirausahaan teknologi.", image:"../../public/img/StartUp.jpg", status:true },
  { name:"Laboratorium Internet of Things", capacity:36, location:"Gedung F, Lantai 3", pic:"Dr. Budi Santoso", email:"koordinator@iclabs.ac.id", facilities:"Proyektor, Whiteboard, AC, Komputer dll", description:"Laboratorium dengan fasilitas IoT lengkap untuk pembelajaran teknologi terkini.", image:"../../public/img/IoT.jpg", status:true },
  { name:"Laboratorium Multimedia", capacity:36, location:"Gedung F, Lantai 3", pic:"Dr. Budi Santoso", email:"koordinator@iclabs.ac.id", facilities:"Proyektor, Whiteboard, AC, Komputer dll", description:"Ruangan multimedia dengan peralatan editing video dan audio profesional.", image:"../../public/img/Mulmed.jpg", status:true },
  { name:"Laboratorium Computer Networking", capacity:36, location:"Gedung F, Lantai 3", pic:"Dr. Budi Santoso", email:"koordinator@iclabs.ac.id", facilities:"Proyektor, Whiteboard, AC, Komputer dll", description:"Lab jaringan komputer dengan infrastruktur lengkap untuk praktikum.", image:"../../public/img/comnet.png", status:true },

  { name:"Laboratorium Data Science", capacity:36, location:"Gedung F, Lantai 3", pic:"Dr. Budi Santoso", email:"koordinator@iclabs.ac.id", facilities:"Proyektor, Whiteboard, AC, Komputer dll", description:"Laboratorium data science dengan workstation untuk analisis data dan machine learning.", image:"../../public/img/DS.jpg", status:true },
  { name:"Laboratorium Computer Vision", capacity:36, location:"Gedung F, Lantai 3", pic:"Dr. Budi Santoso", email:"koordinator@iclabs.ac.id", facilities:"Proyektor, Whiteboard, AC, Komputer dll", description:"Lab computer vision dengan perangkat kamera, sensor, dan komputasi untuk eksperimen visi komputer.", image:"../../public/img/CV.jpg", status:true },
  { name:"Laboratorium Microcontroller", capacity:36, location:"Gedung F, Lantai 3", pic:"Dr. Budi Santoso", email:"koordinator@iclabs.ac.id", facilities:"Proyektor, Whiteboard, AC, Komputer dll", description:"Lab microcontroller dengan kit Arduino, ESP32, dan perangkat embedded lainnya.", image:"../../public/img/Micro.jpg", status:true },
  { name:"Riset 2", capacity:36, location:"Gedung F, Lantai 3", pic:"Dr. Budi Santoso", email:"koordinator@iclabs.ac.id", facilities:"Proyektor, Whiteboard, AC, Komputer dll", description:"Ruangan riset dengan fasilitas lengkap untuk penelitian dan pengembangan.", image:"../../public/img/Riset.jpg", status:true }
];

function renderLabs() {
    const grid = $('labGrid');
    if (!grid) return;

    grid.innerHTML = labs.map((lab, index) => `
        <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: transform 0.3s, box-shadow 0.3s;"
             onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.15)'"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)'">

            <div style="position: relative;">
                <img src="${lab.image}" alt="${lab.name}" style="width: 100%; height: 200px; object-fit: cover;">
                <span style="position: absolute; top: 12px; right: 12px; background: ${lab.status ? '#10b981' : '#EF4444'}; color: white; padding: 0.375rem 0.875rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 600; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    ${lab.status ? 'Tersedia' : 'Tidak Tersedia'}
                </span>
            </div>

            <div style="padding: 1.5rem;">
                <h3 style="font-size: 1.25rem; font-weight: 700; color: #122E4F; margin-bottom: 1rem;">${lab.name}</h3>

                <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 1.25rem; color: #6b7280; font-size: 0.875rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-users" style="width: 20px; color: #1F45AC;"></i>
                        <span>Kapasitas: ${lab.capacity} orang</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-map-marker-alt" style="width: 20px; color: #1F45AC;"></i>
                        <span>${lab.location}</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-user-tie" style="width: 20px; color: #1F45AC;"></i>
                        <span>${lab.pic}</span>
                    </div>
                </div>

                <div style="display: flex; gap: 0.75rem;">
                    <button type="button"
                            onclick="editLab(${index})"
                            style="flex: 1; background: #3B82F6; color: white; padding: 0.625rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: background 0.3s;"
                            onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3B82F6'">
                        <i class="fas fa-edit"></i> Edit
                    </button>

                    <button type="button"
                            onclick="deleteLab(${index})"
                            style="flex: 1; background: #EF4444; color: white; padding: 0.625rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: background 0.3s;"
                            onmouseover="this.style.background='#dc2626'" onmouseout="this.style.background='#EF4444'">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

/** Upload handlers */
function handleFileSelect(event) {
    const file = event?.target?.files?.[0];
    if (!file) return;

    if (!file.type.match('image/(png|jpeg|jpg)')) { alert('Format file harus PNG, JPG, atau JPEG!'); return; }
    if (file.size > 5 * 1024 * 1024) { alert('Ukuran file maksimal 5MB!'); return; }

    const reader = new FileReader();
    reader.onload = function(e) {
        const previewImg = $('previewImg');
        const imagePreview = $('imagePreview');
        const uploadPlaceholder = $('uploadPlaceholder');
        const changePhotoBtn = $('changePhotoBtn');
        const fileName = $('fileName');
        const labImage = $('labImage');

        if (previewImg) previewImg.src = e.target.result;
        if (imagePreview) imagePreview.style.display = 'block';
        if (uploadPlaceholder) uploadPlaceholder.style.display = 'none';
        if (changePhotoBtn) changePhotoBtn.style.display = 'block';
        if (fileName) fileName.textContent = file.name;
        if (labImage) labImage.value = e.target.result;
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

/** Modal */
function openModal(mode, index = null) {
    const overlay = $('modalOverlay');
    const form = $('labForm');
    const submitBtnText = $('submitBtnText');
    const title = $('modalTitle');
    const fileInput = $('labImageFile');

    if (!overlay || !form || !submitBtnText || !title) return;

    overlay.style.display = 'flex';

    if (mode === 'add') {
        title.textContent = 'Informasi Ruangan';
        submitBtnText.textContent = 'Tambah Ruangan';
        form.reset();
        resetUploadArea();
        $('editIndex').value = '';
        if ($('labStatus')) $('labStatus').checked = true;
        if (fileInput) fileInput.required = true;
        updateToggleStatus();
        return;
    }

    if (mode === 'edit' && index !== null && labs[index]) {
        title.textContent = 'Informasi Ruangan';
        submitBtnText.textContent = 'Simpan Perubahan';

        const lab = labs[index];
        $('editIndex').value = index;

        $('labName').value = lab.name;
        $('labCapacity').value = lab.capacity;
        $('labLocation').value = lab.location;
        $('labPIC').value = lab.pic;
        $('labEmail').value = lab.email;
        $('labFacilities').value = lab.facilities;
        $('labDescription').value = lab.description;
        $('labImage').value = lab.image;
        $('labStatus').checked = lab.status;

        if (fileInput) fileInput.required = false;

        if (lab.image) {
            $('previewImg').src = lab.image;
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

function editLab(index) { openModal('edit', index); }

/** Hapus hanya dari kartu (bukan dari modal) */
function deleteLab(index) {
    if (!labs[index]) return;
    if (confirm(`Apakah Anda yakin ingin menghapus "${labs[index].name}"?`)) {
        labs.splice(index, 1);
        renderLabs();
        showNotification('Laboratorium berhasil dihapus!', 'success');
    }
}

/** Toggle status */
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

/** Notification */
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed; top: 90px; right: 20px;
        background: ${type === 'success' ? '#10b981' : '#EF4444'};
        color: white; padding: 1rem 1.5rem; border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        z-index: 10000; animation: slideIn 0.3s ease-out;
    `;
    notification.innerHTML = `
        <div style="display:flex;align-items:center;gap:0.75rem;">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}" style="font-size:1.25rem;"></i>
            <span style="font-weight:600;">${message}</span>
        </div>
    `;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

/** Animations style once */
(function ensureAnimStyles(){
    const id = 'notif-anim-styles';
    if (document.getElementById(id)) return;
    const style = document.createElement('style');
    style.id = id;
    style.textContent = `
        @keyframes slideIn { from { transform: translateX(400px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes slideOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(400px); opacity: 0; } }
    `;
    document.head.appendChild(style);
})();

/** Bind events */
(function init(){
    const statusEl = $('labStatus');
    if (statusEl) statusEl.addEventListener('change', updateToggleStatus);

    const form = $('labForm');
    if (form) {
        form.addEventListener('submit', function(e){
            e.preventDefault();

            const editIndex = $('editIndex')?.value ?? '';
            const newLab = {
                name: $('labName')?.value?.trim() ?? '',
                capacity: parseInt($('labCapacity')?.value ?? '0', 10),
                location: $('labLocation')?.value?.trim() ?? '',
                pic: $('labPIC')?.value ?? '',
                email: $('labEmail')?.value?.trim() ?? '',
                facilities: $('labFacilities')?.value?.trim() ?? '',
                description: $('labDescription')?.value?.trim() ?? '',
                image: $('labImage')?.value?.trim() ?? '',
                status: !!$('labStatus')?.checked
            };

            // validation minimal
            if (!newLab.name || !newLab.capacity || !newLab.location || !newLab.pic || !newLab.email) {
                showNotification('Lengkapi field wajib terlebih dahulu.', 'error');
                return;
            }
            if (!newLab.image) {
                showNotification('Foto ruangan wajib diisi.', 'error');
                return;
            }

            if (editIndex === '') {
                labs.push(newLab);
                showNotification('Laboratorium berhasil ditambahkan!', 'success');
            } else {
                const idx = parseInt(editIndex, 10);
                if (!Number.isNaN(idx) && labs[idx]) {
                    labs[idx] = newLab;
                    showNotification('Laboratorium berhasil diperbarui!', 'success');
                }
            }

            renderLabs();
            closeModal();
        });
    }

    const overlay = $('modalOverlay');
    if (overlay) {
        overlay.addEventListener('click', function(e){
            if (e.target === this) closeModal();
        });
    }

    updateToggleStatus();
    renderLabs();
})();
</script>