<script>
let modalDetail, modalEdit;

document.addEventListener('DOMContentLoaded', function () {
    modalDetail = new bootstrap.Modal(document.getElementById('modalDetail'));
    modalEdit   = new bootstrap.Modal(document.getElementById('modalEdit'));
});

// ================= VIEW DETAIL =================
function openDetailModal(button) {

    const data = JSON.parse(button.getAttribute('data-item'));

    document.getElementById('view_nama').innerText     = data.nama ?? '-';
    document.getElementById('view_email').innerText    = data.email ?? '-';
    document.getElementById('view_telepon').innerText  = data.telepon ?? '-';
    document.getElementById('view_kegiatan').innerText = data.kegiatan ?? '-';
    document.getElementById('view_peserta').innerText  = (data.peserta ?? '0') + ' Orang';
    document.getElementById('view_mulai').innerText    = data.mulai_fmt ?? '-';
    document.getElementById('view_selesai').innerText  = data.selesai_fmt ?? '-';
    document.getElementById('view_status').innerText   = data.status ?? '-';

    // ===== PROPOSAL =====
    const btnProposal = document.getElementById('view_proposal');

    if (data.proposal && data.proposal.includes('uploads/')) {
        btnProposal.href = data.proposal;
        btnProposal.target = "_blank";
        btnProposal.classList.remove('disabled', 'btn-secondary');
        btnProposal.classList.add('btn-danger');
        btnProposal.innerHTML = '<i class="fas fa-file-pdf me-2"></i> Download Proposal';
    } else {
        btnProposal.href = '#';
        btnProposal.removeAttribute('target');
        btnProposal.classList.add('disabled', 'btn-secondary');
        btnProposal.classList.remove('btn-danger');
        btnProposal.innerHTML = 'File Tidak Tersedia';
    }

    // ===== ALASAN PENOLAKAN =====
    const rowAlasan = document.getElementById('row_view_alasan');
    if (data.status === 'Ditolak') {
        rowAlasan.style.display = 'table-row';
        document.getElementById('view_alasan').innerText = data.alasan ?? '-';
    } else {
        rowAlasan.style.display = 'none';
    }

    modalDetail.show();
}

// ================= TOGGLE ALASAN ADMIN =================
function toggleAlasanAdmin() {
    const status = document.getElementById('edit_status_select').value;
    const box    = document.getElementById('box_alasan_admin');
    const txt    = document.getElementById('edit_alasan');

    if (status === 'Ditolak') {
        box.style.display = 'block';
        txt.setAttribute('required', 'required');
    } else {
        box.style.display = 'none';
        txt.removeAttribute('required');
    }
}




function openEditModal(button) {

    const data = JSON.parse(button.getAttribute('data-item'));

    // ID
    document.getElementById('edit_id').value = data.id;

    // ✅ PAKAI KEY YANG BENAR
    document.getElementById('edit_mulai').value   = data.raw_mulai;
    document.getElementById('edit_selesai').value = data.raw_selesai;

    // Status
    document.getElementById('edit_status_select').value = data.status;

    // Alasan (opsional)
    document.getElementById('edit_alasan').value = data.alasan ?? '';

    // Toggle alasan
    toggleAlasanAdmin();

    // Show modal
    modalEdit.show();
}

</script>
