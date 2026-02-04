<?php
// app/views/internal/jadwal/index.php
// Halaman Jadwal - Tampilkan semua jadwal lab

// Include helper functions
include __DIR__ . '/../booking/helpers.php';
?>

<!-- HERO SECTION (sama dengan Data Peminjaman) -->
<div
    style="background: linear-gradient(135deg, #122E4F 0%, #1F45AC 100%); padding: 2.5rem; border-radius: 12px; margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1); gap: 1.5rem; flex-wrap: wrap;">
    <div style="flex: 1; min-width: 250px;">
        <h1 style="color: white; font-size: 2rem; font-weight: 800; margin: 0 0 0.5rem 0;">Dashboard Internal</h1>
        <p style="color: rgba(255,255,255,0.9); font-size: 1rem; margin:0;">Monitoring jadwal praktikum dan peminjaman
            semua laboratorium</p>
    </div>
</div>


<!-- Jadwal Content -->
<section class="labs-section py-2">
    <div class="container-fluid px-0">
        <!-- Date Picker -->
        <div class="mb-4">
            <div class="p-date-picker">
                <label for="jadwalDate" style="font-weight: 700; color: #334155; margin-right: 10px;">Tanggal:</label>
                <input type="date" id="jadwalDate" value="<?= htmlspecialchars($data['selected_date']) ?>"
                    onchange="changeJadwalDate(this.value)"
                    style="padding: 8px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;" />
            </div>
        </div>

        <!-- Labs Grid (layout asli) -->
        <div class="p-labs-grid">
            <?php foreach ($data['labs'] as $lab):
                $jadwalLab = getJadwalLab($data['jadwal_tetap'], $lab['id'], $data['selected_day']);
                $peminjamanLab = getPeminjamanLab($data['peminjaman'], $lab['id'], $data['selected_date']);
                $slotKosong = getSlotKosong($jadwalLab, $peminjamanLab);
            ?>
                <div class="p-lab-card">
                    <h3><?= htmlspecialchars($lab['short_name']) ?></h3>
                    <div class="p-slot-list">
                        <?php
                        // Gunakan helper function untuk menggabungkan dan mengurutkan slot
                        $allSlots = getSortedSlots($jadwalLab, $peminjamanLab, $slotKosong);
                        ?>

                        <?php foreach ($allSlots as $slot): ?>

                            <?php if ($slot['type'] == 'praktikum' || $slot['type'] == 'tergeser'): $j = $slot['data']; ?>
                                <div class="p-slot <?= $slot['type'] ?>">
                                    <span class="p-slot-label"><?= $slot['type'] == 'tergeser' ? 'Tergeser' : 'Praktikum' ?>: <?= $j['jam_mulai'] ?>-<?= $j['jam_selesai'] ?></span>
                                    <span class="p-slot-sub"><?= htmlspecialchars($j['matkul']) ?> (<?= $j['kelas'] ?>)<?php if ($slot['type'] == 'tergeser') echo "- Digeser oleh " . htmlspecialchars($slot['overridden_by']); ?></span>
                                </div>

                            <?php elseif ($slot['type'] == 'peminjaman'): $p = $slot['data']; ?>
                                <?php
                                $slotClass = 'internal';
                                $slotLabel = 'Internal';
                                if ($p['type'] == 'external' || $p['type'] == 'eksternal') {
                                    $slotClass = 'eksternal';
                                    $slotLabel = 'Eksternal';
                                } elseif ($p['type'] == 'tergeser') {
                                    $slotClass = 'tergeser';
                                    $slotLabel = 'Tergeser';
                                }
                                ?>
                                <div class="p-slot <?= $slotClass ?>">
                                    <span class="p-slot-label"><?= $slotLabel ?>:
                                        <?= $p['jam_mulai'] ?>-<?= $p['jam_selesai'] ?></span>
                                    <span class="p-slot-sub"><?= htmlspecialchars($p['keterangan']) ?> - <span
                                            style="font-weight: 600;"><?= htmlspecialchars($p['peminjam']) ?></span></span>
                                </div>

                            <?php elseif ($slot['type'] == 'kosong'):
                                $k = $slot['data']; ?>
                                <div class="p-slot"
                                    style="background: #F8FAFC; border: 1px dashed #CBD5E1; color: #94A3B8; cursor: default;">
                                    <span class="p-slot-label" style="display:block; margin-bottom:4px;">Kosong</span>
                                    <span class="p-slot-sub"
                                        style="display:block; font-size:12px;"><?= $k['mulai'] ?>-<?= $k['selesai'] ?></span>
                                </div>

                            <?php endif; ?>

                        <?php endforeach; ?>

                        <?php if (empty($jadwalLab) && empty($peminjamanLab) && empty($slotKosong)): ?>
                            <div style="color: #64748b; font-size: 13px; font-style: italic;">Tidak ada jadwal</div>
                        <?php endif; ?>
                    </div>
                </div>



            <?php endforeach; ?>
        </div>

        <!-- Legend -->
        <div class="p-legend">
            <div class="p-legend-item praktikum-tetap">
                <span class="p-legend-color p-lg-praktikum"></span>
                Praktikum Tetap
            </div>
            <div class="p-legend-item peminjaman-internal">
                <span class="p-legend-color p-lg-internal"></span>
                Peminjaman Internal
            </div>
            <div class="p-legend-item peminjaman-eksternal">
                <span class="p-legend-color p-lg-eksternal"></span>
                Peminjaman Eksternal
            </div>
            <div class="p-legend-item jadwal-tergeser">
                <span class="p-legend-color p-lg-expired"></span>
                Jadwal Tergeser
            </div>
        </div>
    </div>
</section>

<script>
    function changeJadwalDate(date) {
        window.location.href = '<?= BASE_URL ?>/internal/jadwal?date=' + date;
    }
</script>