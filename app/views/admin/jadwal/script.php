<?php
// views/admin/jadwal/script.php
?>

<script>
  (function () {
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

    // ===== RENDER TABLE =====
    // ===== RENDER TABLE =====
    function renderTable() {
      const tbody = document.getElementById('jadwalTableBody');

      if (!tbody) return;
      tbody.innerHTML = '';

      schedules.forEach(item => {
        const tr = document.createElement('tr');

        tr.innerHTML = `
        <td class="px-4 fw-bold text-dark">
          ${HARI_LIST[item.hari]}
        </td>
        <td class="fw-bold text-dark">
          ${LABS[item.lab_id] || item.lab_id}
        </td>
        <td>
          <div class="small">
             <span class="fw-bold text-secondary"><i class="far fa-clock me-1"></i></span> ${item.jam_mulai.substring(0, 5)} - ${item.jam_selesai.substring(0, 5)}
          </div>
        </td>
        <td class="fw-bold text-dark">
          ${item.nama_matakuliah}
        </td>
        <td>
          <span class="badge bg-secondary-subtle text-secondary-emphasis border">
             ${item.frekuensi || 'Mingguan'}
          </span>
        </td>
        <td>
           <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3">
             ${item.nama_kelas}
           </span>
        </td>
        <td class="text-end px-4">
          <div class="d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-sm btn-primary fw-bold" title="Edit" onclick="editJadwal(${item.id})">
              <i class="fas fa-edit"></i>
            </button>
            <button type="button" class="btn btn-sm btn-danger fw-bold" title="Hapus" onclick="hapusJadwal(${item.id})">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </td>
      `;

        tbody.appendChild(tr);
      });

      // Init Simple DataTables
      if (typeof simpleDatatables !== 'undefined') {
        const tableEl = document.getElementById('jadwalTable');
        if (tableEl) {
          if (window.jadwalTableInstance) {
            window.jadwalTableInstance.destroy();
          }
          window.jadwalTableInstance = new simpleDatatables.DataTable(tableEl, { perPage: 10, perPageSelect: [10, 20, 50] });
        }
      }
    }

    // ===== MODAL FUNCTIONS =====

    // Open Schedule Modal
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

    // Note: Form submission is handled normally via action attribute to Controller, 
    // but if we want validation before submit, we can keep using saveJadwalPraktikum
    // But here we rely on standard POST. 
    // Just ensure the form method="POST" and action="" is set correctly in modal.php
    // We'll update saveJadwalPraktikum to just validate and let submitting happen.

    // Save Jadwal Praktikum (Client Validation)
    window.saveJadwalPraktikum = function (event) {
      // event.preventDefault(); // Remove this to allow form submit
      const form = event.target;
      // Basic validation...
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

      // Server will handle conflict check and redirect.
      return true;
    };

    // Edit Jadwal
    window.editJadwal = function (id) {
      // Find item
      const item = schedules.find(s => s.id == id);
      if (!item) return;

      const modal = document.getElementById('pScheduleModal');
      const form = document.getElementById('pScheduleForm');
      const title = document.getElementById('scheduleModalTitle');

      // Set form values
      form.action.value = 'update';
      document.getElementById('scheduleEditIndex').value = item.id; // Map to ID hidden input
      document.getElementById('scheduleHari').value = item.hari;
      document.getElementById('scheduleLab').value = item.lab_id; // Make sure Select value matches ID
      document.getElementById('scheduleJamMulai').value = item.jam_mulai;
      document.getElementById('scheduleJamSelesai').value = item.jam_selesai;
      document.getElementById('scheduleMataKuliah').value = item.matakuliah_id;
      document.getElementById('scheduleFrekuensi').value = item.frekuensi || ''; // Populate Frequency
      document.getElementById('scheduleKelas').value = item.kelas_id;

      title.textContent = 'Edit Jadwal Praktikum';
      modal.classList.add('active');
    };

    // Hapus Jadwal
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
          // Create a form to submit delete request
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

    // ===== EXPORT REPORT =====
    // Reuse existing logic but with 'schedules' var
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

    // ===== EVENT LISTENERS =====
    document.getElementById('pScheduleModal')?.addEventListener('click', e => {
      if (e.target === e.currentTarget) closeScheduleModal();
    });

    // ===== INIT =====
    renderTable();

  })();
</script>