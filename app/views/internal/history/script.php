<script>
// views/internal/history/script.php

const BASE_URL = '<?= BASE_URL ?>';

// Data peminjaman dari PHP
const peminjamanData = <?= json_encode($data['peminjaman'] ?? []) ?>;

/**
 * Buka modal edit dengan data dari row
 */
function editPeminjaman(id) {
    const item = peminjamanData.find(p => p.id == id);
    if (!item) {
        Swal.fire('Error', 'Data tidak ditemukan', 'error');
        return;
    }
    
    document.getElementById('editId').value = id;
    document.getElementById('editLab').value = item.nama_ruangan;
    document.getElementById('editTanggal').value = item.tanggal;
    document.getElementById('editJamMulai').value = item.jam_mulai;
    document.getElementById('editJamSelesai').value = item.jam_selesai;
    document.getElementById('editKeterangan').value = item.keterangan;
    
    new bootstrap.Modal(document.getElementById('editModal')).show();
}

/**
 * Simpan perubahan edit
 */
function saveEdit() {
    const id = document.getElementById('editId').value;
    const tanggal = document.getElementById('editTanggal').value;
    const jamMulai = document.getElementById('editJamMulai').value;
    const jamSelesai = document.getElementById('editJamSelesai').value;
    const keterangan = document.getElementById('editKeterangan').value;
    
    if (!tanggal || !jamMulai || !jamSelesai) {
        Swal.fire('Error', 'Tanggal dan waktu harus diisi', 'error');
        return;
    }
    
    // Send update request
    fetch(BASE_URL + '/internal/updatePeminjaman', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            id: id,
            tanggal: tanggal,
            jam_mulai: jamMulai,
            jam_selesai: jamSelesai,
            keterangan: keterangan
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Berhasil', 'Peminjaman berhasil diperbarui', 'success')
                .then(() => location.reload());
        } else {
            Swal.fire('Error', data.message || 'Gagal memperbarui', 'error');
        }
    })
    .catch(err => {
        Swal.fire('Error', 'Terjadi kesalahan', 'error');
    });
}

/**
 * Buka modal konfirmasi hapus
 */
function deletePeminjaman(id) {
    document.getElementById('deleteId').value = id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

/**
 * Konfirmasi dan proses hapus
 */
function confirmDelete() {
    const id = document.getElementById('deleteId').value;
    
    fetch(BASE_URL + '/internal/deletePeminjaman', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Berhasil', 'Peminjaman berhasil dihapus', 'success')
                .then(() => location.reload());
        } else {
            Swal.fire('Error', data.message || 'Gagal menghapus', 'error');
        }
    })
    .catch(err => {
        Swal.fire('Error', 'Terjadi kesalahan', 'error');
    });
}
</script>
