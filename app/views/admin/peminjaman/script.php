<?php
// views/admin/peminjaman/script.php
// This file simply injects PHP data into JS Window Object
// and loads the external JS file.
?>

<script>
  // 1. Define Config
  window.PeminjamanConfig = {
    baseUrl: '<?= BASE_URL ?>',
    dayNames: ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'],
    dayRange: { start: "07:00", end: "18:20" }
  };

  // 2. Define Data Container
  window.PeminjamanData = {
    labs: [],
    bookings: [],
    fixedSchedule: {}
  };

  // 3. Inject Data from PHP
  <?php foreach ($data['labs'] as $lab): ?>
    window.PeminjamanData.labs.push({ key: "<?= $lab['id'] ?>", name: "<?= $lab['nama_ruangan'] ?>" });
  <?php endforeach; ?>

  <?php foreach ($data['bookings'] as $booking): ?>
    window.PeminjamanData.bookings.push({
      id: "<?= $booking['id'] ?>",
      name: "<?= $booking['nama_peminjam'] ?: ($booking['user_nama'] ?? '-') ?>",
      // email: "<?= $booking['user_email'] ?? '-' ?>",
      instansi: "<?= $booking['kegiatan'] ?>",
      role: "<?= $booking['tipe'] ?>",
      tanggal: "<?= $booking['tanggal'] ?? $booking['tanggal_peminjaman'] ?? '' ?>",
      status: "<?= $booking['status'] == 'menunggu' ? 'nonaktif' : 'aktif' ?>",
      lab: "<?= $booking['lab_nama'] ?>",
      labId: "<?= $booking['lab_id'] ?>",
      waktuMulai: "<?= substr($booking['jam_mulai'], 0, 5) ?>",
      waktuSelesai: "<?= substr($booking['jam_selesai'], 0, 5) ?>",
      statusPeminjaman: "<?= ucfirst($booking['status']) ?>",
      tipe: "<?= ucfirst($booking['tipe']) ?>"
    });
  <?php endforeach; ?>

  const rawFixed = <?= json_encode($data['fixed_schedules']); ?>;

  // Process Fixed Schedule
  window.PeminjamanConfig.dayNames.forEach(d => {
    window.PeminjamanData.fixedSchedule[d] = {};
    window.PeminjamanData.labs.forEach(l => window.PeminjamanData.fixedSchedule[d][l.key] = []);
  });

  rawFixed.forEach(item => {
    const d = (item.hari || '').toLowerCase();
    const l = item.lab_id;
    if (window.PeminjamanData.fixedSchedule[d] && window.PeminjamanData.fixedSchedule[d][l]) {
      window.PeminjamanData.fixedSchedule[d][l].push({
        start: item.jam_mulai.substring(0, 5),
        end: item.jam_selesai.substring(0, 5),
        title: (item.nama_matakuliah || '-') + ' (' + (item.nama_kelas || '-') + ')'
      });
    }
  });

</script>

<!-- Load External Script -->
<!-- Load External Script with Cache Buster -->
<script src="<?= BASE_URL ?>/public/js/admin/peminjaman.js?v=<?= time() ?>"></script>