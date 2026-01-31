<?php
// views/admin/jadwal/script.php
?>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // ===== LAB LIST =====
    // Use data from Controller
    const LABS = {};
    <?php foreach ($data['labs'] as $lab): ?>
      LABS[<?= $lab['id'] ?>] = "<?= $lab['nama_ruangan'] ?>";
    <?php endforeach; ?>

    const HARI_LIST = {
      senin: "Senin",
      selasa: "Selasa",
      rabu: "Rabu",
      kamis: "Kamis",
      jumat: "Jumat",
      sabtu: "Sabtu",
      minggu: "Minggu"
    };

    // ===== DATA FROM SERVER =====
    // Use data from Controller
    const schedules = <?= json_encode($data['schedules']); ?>;

    // ===== MODAL FUNCTIONS =====
    // Expose global functions needed by buttons
    window.openScheduleModal = function () {
      const modal = document.getElementById('pScheduleModal');
      const form = document.getElementById('pScheduleForm');
      const title = document.getElementById('scheduleModalTitle');

      form.reset();
      form.action.value = 'create';
      document.getElementById('scheduleEditIndex').value = '';
      title.textContent = 'Tambah Jadwal Praktikum Tetap';

      modal.classList.add('active');
    };

    window.closeScheduleModal = function () {
      document.getElementById('pScheduleModal').classList.remove('active');
    };

    window.saveJadwalPraktikum = function (event) {
      const form = event.target;
      const jamMulai = form.jamMulai.value;
      const jamSelesai = form.jamSelesai.value;

      if (jamMulai >= jamSelesai) {
        Swal.fire({
          icon: 'error',
          title: 'Validasi Gagal',
          text: 'Jam Selesai harus lebih besar dari Jam Mulai.'
        });
        event.preventDefault();
        return false;
      }
      return true;
    };

    window.editJadwal = function (id) {
      const item = schedules.find(s => s.id == id);
      if (!item) return;

      const modal = document.getElementById('pScheduleModal');
      const form = document.getElementById('pScheduleForm');
      const title = document.getElementById('scheduleModalTitle');

      form.action.value = 'update';
      document.getElementById('scheduleEditIndex').value = item.id;
      document.getElementById('scheduleHari').value = item.hari;
      document.getElementById('scheduleLab').value = item.lab_id;
      document.getElementById('scheduleJamMulai').value = item.jam_mulai;
      document.getElementById('scheduleJamSelesai').value = item.jam_selesai;
      document.getElementById('scheduleMataKuliah').value = item.matakuliah_id;
      document.getElementById('scheduleFrekuensi').value = item.frekuensi || '';
      document.getElementById('scheduleKelas').value = item.kelas_id;

      title.textContent = 'Edit Jadwal Praktikum';
      modal.classList.add('active');
    };

    window.hapusJadwal = function (id) {
      Swal.fire({
        title: 'Hapus Jadwal?',
        text: "Apakah Anda yakin ingin menghapus jadwal ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          const form = document.createElement('form');
          form.method = 'POST';
          form.action = '<?= BASE_URL ?>/jadwal';

          const inputAction = document.createElement('input');
          inputAction.type = 'hidden';
          inputAction.name = 'action';
          inputAction.value = 'delete';

          const inputId = document.createElement('input');
          inputId.type = 'hidden';
          inputId.name = 'id';
          inputId.value = id;

          form.appendChild(inputAction);
          form.appendChild(inputId);
          document.body.appendChild(form);
          form.submit();
        }
      });
    };

    // Export function also exposed
    window.exportJadwalReport = function () {
      if (schedules.length === 0) {
        Swal.fire({
          icon: 'info',
          title: 'Info',
          text: 'Tidak ada data jadwal untuk diexport.'
        });
        return;
      }

      const headerInfo = [
        ['LAPORAN JADWAL PRAKTIKUM TETAP'],
        ['IC-LABS - Innovation Center Laboratories'],
        [`Tanggal Export: ${new Date().toLocaleDateString('id-ID', {
          day: 'numeric',
          month: 'long',
          year: 'numeric'
        })}`],
        [],
        ['No', 'Hari', 'Laboratorium', 'Jam Mulai', 'Jam Selesai', 'Mata Kuliah', 'Kelas']
      ];

      const dataRows = schedules.map((item, index) => [
        index + 1,
        HARI_LIST[item.hari],
        LABS[item.lab_id],
        item.jam_mulai,
        item.jam_selesai,
        item.mata_kuliah,
        item.kelas
      ]);

      const fullData = [...headerInfo, ...dataRows];
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(fullData);
      XLSX.utils.book_append_sheet(wb, ws, 'Jadwal Praktikum');
      XLSX.writeFile(wb, `Jadwal_Praktikum_${new Date().toISOString().split('T')[0]}.xlsx`);
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: 'Download laporan dimulai',
        timer: 1500,
        showConfirmButton: false
      });
    };

    // Event listener for modal click
    document.getElementById('pScheduleModal')?.addEventListener('click', e => {
      if (e.target === e.currentTarget) closeScheduleModal();
    });

  });
</script>