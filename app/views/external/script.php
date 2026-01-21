<!-- Bootstrap JS Bundle (Required for Modals) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // ==========================================
    // 1. INISIALISASI VARIABEL GLOBAL
    // ==========================================
    let modalTambah, modalEdit, modalDetail, modalRestriction, modalDeleteConfirm;

    // Inisialisasi Modal saat halaman selesai dimuat
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🚀 Script external loaded!');
        
        // Pastikan elemen ID ini ada di file modal.php Anda
        const elTambah = document.getElementById('modalTambah');
        const elEdit = document.getElementById('modalEdit');
        const elDetail = document.getElementById('modalDetail');
        const elRestriction = document.getElementById('modalRestriction');
        const elDelete = document.getElementById('modalDeleteConfirm');

        // Cek agar tidak error jika elemen belum ter-render
        if (elTambah) {
            modalTambah = new bootstrap.Modal(elTambah);
            console.log('✅ Modal Tambah initialized');
        }
        if (elEdit) {
            modalEdit = new bootstrap.Modal(elEdit);
            console.log('✅ Modal Edit initialized');
        }
        if (elDetail) {
            modalDetail = new bootstrap.Modal(elDetail);
            console.log('✅ Modal Detail initialized');
        }
        if (elRestriction) {
            modalRestriction = new bootstrap.Modal(elRestriction);
            console.log('✅ Modal Restriction initialized');
        }
        if (elDelete) {
            modalDeleteConfirm = new bootstrap.Modal(elDelete);
            console.log('✅ Modal Delete initialized');
        }
    });

    // ==========================================
    // 2. FUNGSI UTAMA: BUKA MODAL TAMBAH
    // ==========================================
    function openAddModal() {
        console.log('📝 openAddModal called');
        if (modalTambah) {
            modalTambah.show();
        } else {
            console.error('❌ Modal Tambah not initialized');
        }
    }

    // ==========================================
    // 3. FUNGSI LIHAT DETAIL
    // ==========================================
    function openDetail(button) {
        console.log('👁️ openDetail called');
        try {
            // Ambil data JSON aman dari tombol
            const data = JSON.parse(button.getAttribute('data-item'));
            console.log('Data:', data);

            // Isi Text ke dalam elemen Modal
            document.getElementById('det_nama').innerText = data.nama;
            document.getElementById('det_email').innerText = data.email;
            document.getElementById('det_telepon').innerText = data.telepon;
            document.getElementById('det_kegiatan').innerText = data.kegiatan;
            document.getElementById('det_peserta').innerText = data.peserta + ' Orang';
            document.getElementById('det_mulai').innerText = data.mulai;
            document.getElementById('det_selesai').innerText = data.selesai;
            
            // Logika Tombol Proposal (Cek ekstensi file)
            const btnProposal = document.getElementById('det_proposal');
            // Validasi sederhana jika file ada dan memiliki ekstensi dokumen
            if(data.proposal && (data.proposal.includes('.pdf') || data.proposal.includes('.doc') || data.proposal.includes('.docx'))) {
                btnProposal.href = data.proposal;
                btnProposal.target = "_blank"; 
                btnProposal.classList.remove('disabled', 'btn-secondary');
                btnProposal.classList.add('btn-dark');
                btnProposal.innerHTML = '<i class="bi bi-file-earmark-pdf me-2"></i> Lihat / Download Proposal';
            } else {
                btnProposal.href = '#';
                btnProposal.removeAttribute('target'); 
                btnProposal.classList.add('disabled', 'btn-secondary');
                btnProposal.classList.remove('btn-dark');
                btnProposal.innerHTML = 'Proposal Tidak Tersedia';
            }

            // Reset Tampilan Alert Status (Sembunyikan semua dulu)
            document.querySelectorAll('.detail-section').forEach(el => el.style.display = 'none');

            // Tampilkan Alert sesuai Status
            if (data.status === 'Disetujui') {
                document.getElementById('view_success').style.display = 'block';
                const footerWarning = document.getElementById('warning_footer');
                if(footerWarning) footerWarning.style.display = 'flex';
            } else if (data.status === 'Ditolak') {
                document.getElementById('view_reject').style.display = 'block';
                document.getElementById('text_alasan').innerText = data.alasan;
            } else if (data.status === 'Menunggu Interview') {
                document.getElementById('view_interview').style.display = 'block';
            } else {
                // Default: Menunggu Konfirmasi
                document.getElementById('view_wait').style.display = 'block';
            }

            if (modalDetail) {
                modalDetail.show();
            } else {
                console.error('❌ Modal Detail not initialized');
            }
        } catch (error) {
            console.error('❌ Error in openDetail:', error);
            alert('Terjadi kesalahan saat membuka detail. Silakan refresh halaman.');
        }
    }

    // ==========================================
    // 4. FUNGSI EDIT (DENGAN VALIDASI)
    // ==========================================
    function tryEdit(button) {
        console.log('✏️ tryEdit called');
        try {
            const data = JSON.parse(button.getAttribute('data-item'));
            console.log('Data:', data);

            // Cek Status: Jika sudah final, blokir edit
            if (data.status === 'Disetujui' || data.status === 'Ditolak') {
                document.getElementById('restrictionMsg').innerHTML = `Maaf, pengajuan ini sudah <strong>${data.status}</strong>.<br>Anda tidak dapat mengubah data lagi.`;
                if (modalRestriction) {
                    modalRestriction.show();
                } else {
                    console.error('❌ Modal Restriction not initialized');
                }
            } else {
                // Jika aman, isi form edit
                document.getElementById('edit_id').value = data.id;
                document.getElementById('edit_kegiatan').value = data.kegiatan;
                document.getElementById('edit_peserta').value = data.peserta;
                // Gunakan raw_mulai / raw_selesai (format YYYY-MM-DD) untuk input date HTML5
                document.getElementById('edit_mulai').value = data.raw_mulai;
                document.getElementById('edit_selesai').value = data.raw_selesai;
                
                if (modalEdit) {
                    modalEdit.show();
                } else {
                    console.error('❌ Modal Edit not initialized');
                }
            }
        } catch (error) {
            console.error('❌ Error in tryEdit:', error);
            alert('Terjadi kesalahan saat membuka form edit. Silakan refresh halaman.');
        }
    }

    // ==========================================
    // 5. FUNGSI DELETE (DENGAN KONFIRMASI)
    // ==========================================
    function tryDelete(id, status) {
        console.log('🗑️ tryDelete called with id:', id, 'status:', status);
        try {
            // 1. Cek Status: Blokir jika sudah selesai/diproses
            if (status === 'Disetujui' || status === 'Ditolak') {
                document.getElementById('restrictionMsg').innerHTML = `Maaf, pengajuan ini sudah <strong>${status}</strong>.<br>Anda tidak dapat membatalkannya.`;
                if (modalRestriction) {
                    modalRestriction.show();
                } else {
                    console.error('❌ Modal Restriction not initialized');
                }
            } else {
                // 2. Status Aman -> Persiapkan Modal Konfirmasi Hapus
                
                const btnConfirm = document.getElementById('btnConfirmDelete');
                
                // Set URL Hapus menggunakan BASE_URL dari PHP
                const baseUrl = '<?= BASE_URL; ?>';
                btnConfirm.href = baseUrl + '/external/hapus/' + id;
                
                console.log('Delete URL:', btnConfirm.href);
                
                // Buka Modal Konfirmasi
                if (modalDeleteConfirm) {
                    modalDeleteConfirm.show();
                } else {
                    console.error('❌ Modal Delete not initialized');
                }
            }
        } catch (error) {
            console.error('❌ Error in tryDelete:', error);
            alert('Terjadi kesalahan. Silakan refresh halaman.');
        }
    }
</script>