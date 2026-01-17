<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Variabel Modal Global
    let modalTambah, modalEdit, modalDetail, modalRestriction, modalDeleteConfirm;

    // Inisialisasi Modal saat halaman selesai dimuat
    document.addEventListener('DOMContentLoaded', function() {
        modalTambah = new bootstrap.Modal(document.getElementById('modalTambah'));
        modalEdit = new bootstrap.Modal(document.getElementById('modalEdit'));
        modalDetail = new bootstrap.Modal(document.getElementById('modalDetail'));
        modalRestriction = new bootstrap.Modal(document.getElementById('modalRestriction'));
        
        // Inisialisasi Modal Hapus Baru
        modalDeleteConfirm = new bootstrap.Modal(document.getElementById('modalDeleteConfirm'));
    });

    // --- FUNGSI UTAMA: BUKA MODAL TAMBAH ---
    function openAddModal() {
        modalTambah.show();
    }

    // --- FUNGSI 1: DETAIL ---
    function openDetail(button) {
        const data = JSON.parse(button.getAttribute('data-item'));

        document.getElementById('det_nama').innerText = data.nama;
        document.getElementById('det_email').innerText = data.email;
        document.getElementById('det_telepon').innerText = data.telepon;
        document.getElementById('det_kegiatan').innerText = data.kegiatan;
        document.getElementById('det_peserta').innerText = data.peserta + ' Orang';
        document.getElementById('det_mulai').innerText = data.mulai;
        document.getElementById('det_selesai').innerText = data.selesai;
        
        const btnProposal = document.getElementById('det_proposal');
        if(data.proposal && (data.proposal.includes('.pdf') || data.proposal.includes('.doc') || data.proposal.includes('.docx'))) {
            btnProposal.href = data.proposal;
            btnProposal.target = "_blank"; 
            btnProposal.classList.remove('disabled', 'btn-secondary');
            btnProposal.classList.add('btn-dark');
            btnProposal.innerHTML = '<i class="bi bi-eye-fill me-2"></i> Lihat / Download Proposal';
        } else {
            btnProposal.href = '#';
            btnProposal.removeAttribute('target'); 
            btnProposal.classList.add('disabled', 'btn-secondary');
            btnProposal.classList.remove('btn-dark');
            btnProposal.innerHTML = 'Proposal Tidak Tersedia';
        }

        document.querySelectorAll('.detail-section').forEach(el => el.style.display = 'none');

        if (data.status === 'Disetujui') {
            document.getElementById('view_success').style.display = 'block';
            if(document.getElementById('warning_footer')) document.getElementById('warning_footer').style.display = 'flex';
        } else if (data.status === 'Ditolak') {
            document.getElementById('view_reject').style.display = 'block';
            document.getElementById('text_alasan').innerText = data.alasan;
        } else if (data.status === 'Menunggu Interview') {
            document.getElementById('view_interview').style.display = 'block';
        } else {
            document.getElementById('view_wait').style.display = 'block';
        }

        modalDetail.show();
    }

    // --- FUNGSI 2: EDIT ---
    function tryEdit(button) {
        const data = JSON.parse(button.getAttribute('data-item'));

        if (data.status === 'Disetujui' || data.status === 'Ditolak') {
            document.getElementById('restrictionMsg').innerHTML = `Maaf, pengajuan ini sudah <strong>${data.status}</strong>.<br>Anda tidak dapat mengedit data lagi.`;
            modalRestriction.show();
        } else {
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_kegiatan').value = data.kegiatan;
            document.getElementById('edit_peserta').value = data.peserta;
            document.getElementById('edit_mulai').value = data.raw_mulai;
            document.getElementById('edit_selesai').value = data.raw_selesai;
            modalEdit.show();
        }
    }

    // --- FUNGSI 3: DELETE (Updated dengan Pop-Up Modal) ---
    function tryDelete(id, status) {
        // 1. Cek Status: Blokir jika sudah selesai
        if (status === 'Disetujui' || status === 'Ditolak') {
            document.getElementById('restrictionMsg').innerHTML = `Maaf, pengajuan ini sudah <strong>${status}</strong>.<br>Anda tidak dapat menghapusnya.`;
            modalRestriction.show();
        } else {
            // 2. Status Aman -> TAMPILKAN MODAL HAPUS
            
            // Set Link Href pada tombol "Ya, Hapus" di dalam modal
            const btnConfirm = document.getElementById('btnConfirmDelete');
            
            // Pastikan URL valid dengan slash '/'
            btnConfirm.href = '<?= BASE_URL; ?>/external/hapus/' + id;
            
            // Buka Modal Konfirmasi
            modalDeleteConfirm.show();
        }
    }
</script>