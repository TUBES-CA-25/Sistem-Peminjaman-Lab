<script>
    let modalDetail, modalEdit;

    document.addEventListener('DOMContentLoaded', function () {
        // Cek elemen sebelum inisialisasi untuk mencegah error di console
        const elDetail = document.getElementById('modalDetail');
        const elEdit = document.getElementById('modalEdit');

        if (elDetail) modalDetail = new bootstrap.Modal(elDetail);
        if (elEdit) modalEdit = new bootstrap.Modal(elEdit);

        // H-1 Restriction: Minimal tanggal adalah besok
        const tomorrowDate = new Date();
        tomorrowDate.setDate(tomorrowDate.getDate() + 1);
        const minDate = tomorrowDate.toISOString().split('T')[0];

        const tglMulai = document.getElementById('edit_mulai');
        const tglSelesai = document.getElementById('edit_selesai');

        if (tglMulai) tglMulai.setAttribute('min', minDate);
        if (tglSelesai) tglSelesai.setAttribute('min', minDate);

        // Event listener untuk sinkronisasi min date selesai dengan mulai
        if (tglMulai && tglSelesai) {
            tglMulai.addEventListener('change', function() {
                if (tglMulai.value) {
                    tglSelesai.setAttribute('min', tglMulai.value);
                    if (tglSelesai.value && tglSelesai.value < tglMulai.value) {
                        tglSelesai.value = tglMulai.value;
                    }
                }
            });
        }

        // Validasi saat submit form (Double check)
        const formEdit = elEdit ? elEdit.querySelector('form') : null;
        if (formEdit) {
            formEdit.addEventListener('submit', function(e) {
                const status = document.getElementById('edit_status_select').value;
                if (status === 'Disetujui') {
                    const mulaiVal = tglMulai.value;
                    const selesaiVal = tglSelesai.value;

                    if (mulaiVal < minDate) {
                        e.preventDefault();
                        alert('Jadwal minimal dilakukan H-1 (satu hari sebelum kegiatan)!');
                        return false;
                    }
                    if (selesaiVal < mulaiVal) {
                        e.preventDefault();
                        alert('Tanggal selesai tidak boleh mendahului tanggal mulai!');
                        return false;
                    }
                }
            });
        }
    });

    // ================= VIEW DETAIL =================
    function openDetailModal(button) {
        const data = JSON.parse(button.getAttribute('data-item'));

        // Isi Data Teks
        if (document.getElementById('view_nama')) document.getElementById('view_nama').innerText = data.nama ?? '-';
        if (document.getElementById('view_email')) document.getElementById('view_email').innerText = data.email ?? '-';
        if (document.getElementById('view_telepon')) document.getElementById('view_telepon').innerText = data.telepon ?? '-';
        if (document.getElementById('view_kegiatan')) document.getElementById('view_kegiatan').innerText = data.kegiatan ?? '-';
        if (document.getElementById('view_peserta')) document.getElementById('view_peserta').innerText = (data.peserta ?? '0') + ' Orang';
        if (document.getElementById('view_mulai')) document.getElementById('view_mulai').innerText = data.mulai_fmt ?? '-';
        if (document.getElementById('view_selesai')) document.getElementById('view_selesai').innerText = data.selesai_fmt ?? '-';
        if (document.getElementById('view_status')) document.getElementById('view_status').innerText = data.status ?? '-';

        // ===== PROPOSAL =====
        const btnProposal = document.getElementById('view_proposal');
        if (btnProposal) {
            if (data.proposal && data.proposal !== '#') {
                btnProposal.href = data.proposal;
                btnProposal.classList.remove('disabled', 'btn-secondary');
                btnProposal.classList.add('btn-dark');
                btnProposal.innerHTML = '<i class="fas fa-file-pdf me-2"></i> Download Proposal';
            } else {
                btnProposal.href = '#';
                btnProposal.removeAttribute('target');
                btnProposal.classList.add('disabled', 'btn-secondary');
                btnProposal.classList.remove('btn-dark');
                btnProposal.innerHTML = 'File Tidak Tersedia';
            }
        }

        // ===== ALASAN PENOLAKAN =====
        const rowAlasan = document.getElementById('row_view_alasan');
        const txtAlasan = document.getElementById('view_alasan');

        if (rowAlasan && txtAlasan) {
            if (data.status === 'Ditolak') {
                rowAlasan.style.display = 'block';
                txtAlasan.innerText = data.alasan ?? '-';
            } else {
                rowAlasan.style.display = 'none';
            }
        }

        if (modalDetail) modalDetail.show();
    }

    // ================= TOGGLE ALASAN ADMIN =================
    function toggleAlasanAdmin() {
        const statusEl = document.getElementById('edit_status_select');
        const box = document.getElementById('box_alasan_admin');
        const txt = document.getElementById('edit_alasan');
        const tglMulai = document.getElementById('edit_mulai');
        const tglSelesai = document.getElementById('edit_selesai');

        if (statusEl) {
            const status = statusEl.value;

            // 1. Toggle Alasan Penolakan
            if (box && txt) {
                if (status === 'Ditolak') {
                    box.style.display = 'block';
                    txt.setAttribute('required', 'required');
                } else {
                    box.style.display = 'none';
                    txt.removeAttribute('required');
                }
            }

            // 2. Toggle Tanggal Editability (Hanya bisa diubah jika status Disetujui)
            if (tglMulai && tglSelesai) {
                if (status === 'Disetujui') {
                    // Masuk mode edit
                    tglMulai.readOnly = false;
                    tglSelesai.readOnly = false;
                    tglMulai.style.pointerEvents = 'auto';
                    tglSelesai.style.pointerEvents = 'auto';
                    tglMulai.style.backgroundColor = '#ffffff';
                    tglSelesai.style.backgroundColor = '#ffffff';
                    tglMulai.classList.remove('bg-light');
                    tglSelesai.classList.remove('bg-light');
                } else {
                    // Lock input tapi tetap dikirim via POST
                    tglMulai.readOnly = true;
                    tglSelesai.readOnly = true;
                    tglMulai.style.pointerEvents = 'none';
                    tglSelesai.style.pointerEvents = 'none';
                    tglMulai.style.backgroundColor = '#f8f9fa';
                    tglSelesai.style.backgroundColor = '#f8f9fa';
                    tglMulai.classList.add('bg-light');
                    tglSelesai.classList.add('bg-light');
                }
            }
        }
    }

    // ================= EDIT MODAL =================
    function openEditModal(button) {
        const data = JSON.parse(button.getAttribute('data-item'));

        // ID
        if (document.getElementById('edit_id')) document.getElementById('edit_id').value = data.id;

        // Dates
        if (document.getElementById('edit_mulai')) document.getElementById('edit_mulai').value = data.raw_mulai;
        if (document.getElementById('edit_selesai')) document.getElementById('edit_selesai').value = data.raw_selesai;

        // Status
        if (document.getElementById('edit_status_select')) document.getElementById('edit_status_select').value = data.status;

        // Alasan (opsional)
        if (document.getElementById('edit_alasan')) document.getElementById('edit_alasan').value = data.alasan ?? '';

        // Toggle alasan sesuai status awal
        toggleAlasanAdmin();

        // Show modal
        if (modalEdit) modalEdit.show();
    }
</script>
