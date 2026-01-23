<?php
/**
 * app/views/internal/booking/modal.php
 * 
 * File ini berisi 3 modal yang berbeda:
 * 1. MODAL 1: Schedule Modal (Tambah Peminjaman) - Custom modal for per-lab schedule view
 * 2. MODAL 2: Booking Form Modal - Bootstrap modal for submitting booking
 * 
 * Styling: internal-booking.css
 */
?>

<!-- ========================================================================= -->
<!-- MODAL 1: SCHEDULE MODAL (TAMBAH PEMINJAMAN)                              -->
<!-- Purpose: Menampilkan jadwal satu lab + slot kosong untuk booking         -->
<!-- Type: Custom modal (CSS di internal-booking.css)                         -->
<!-- ========================================================================= -->
<div id="scheduleModal" class="p-modal">
    <div class="p-modal-card">
        <div class="p-modal-head">
            <h2 style="margin:0; font-size:20px; font-weight:900; color:#0f172a;">Tambah Peminjaman</h2>
            <button type="button" class="p-x" onclick="closeScheduleModal()">&times;</button>
        </div>

        <div class="p-modal-body">
            <div class="p-form-head"
                style="align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                <div class="p-date-picker" style="flex-grow:1;">
                    <label for="scheduleDate">Tanggal</label>
                    <input type="date" id="scheduleDate" value="<?= htmlspecialchars($data['selected_date']) ?>"
                        onchange="changeDate(this.value)" />
                </div>
            </div>


            <div class="p-labs-grid" id="singleLabGrid">
                <!-- Content will be populated by JavaScript -->
            </div>

            <!-- Hidden template for all labs data -->
            <div style="display: none;" id="allLabsData">
                <?php foreach ($data['labs'] as $lab):
                    $jadwalLab = getJadwalLab($data['jadwal_tetap'], $lab['id'], $data['selected_day']);
                    $peminjamanLab = getPeminjamanLab($data['peminjaman'], $lab['id'], $data['selected_date']);
                    $slotKosong = getSlotKosong($jadwalLab, $peminjamanLab);
                    ?>
                    <div class="lab-data" data-lab-id="<?= $lab['id'] ?>"
                        data-lab-name="<?= htmlspecialchars($lab['short_name']) ?>">
                        <div class="p-lab-card">
                            <h3><?= htmlspecialchars($lab['short_name']) ?></h3>
                            <div class="p-slot-list">
                                <?php
                                // Gunakan helper function untuk menggabungkan dan mengurutkan slot
                                $allSlots = getSortedSlots($jadwalLab, $peminjamanLab, $slotKosong);
                                ?>

                                <?php foreach ($allSlots as $slot): ?>

                                    <?php if ($slot['type'] == 'praktikum'): $j = $slot['data']; ?>
                                        <div class="p-slot praktikum">
                                            <span class="p-slot-label">Praktikum:
                                                <?= $j['jam_mulai'] ?>-<?= $j['jam_selesai'] ?></span>
                                            <span class="p-slot-sub"><?= htmlspecialchars($j['matkul']) ?>
                                                (<?= $j['kelas'] ?>)</span>
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
                                        <?php if (isPastSlot($data['selected_date'], $k['mulai'])): ?>
                                            <div class="p-slot read-only" style="opacity: 0.6; cursor: not-allowed;">
                                                <span class="p-slot-label">Terlewati</span>
                                                <span class="p-slot-sub">Kosong <?= $k['mulai'] ?>-<?= $k['selesai'] ?></span>
                                            </div>
                                        <?php else: ?>
                                            <div class="p-slot available"
                                                onclick="openBookingModal('<?= htmlspecialchars($lab['short_name']) ?>', '<?= $k['mulai'] ?>', '<?= $k['selesai'] ?>')">
                                                <span class="p-slot-label">+ Pinjam</span>
                                                <span class="p-slot-sub">Kosong <?= $k['mulai'] ?>-<?= $k['selesai'] ?></span>
                                            </div>
                                        <?php endif; ?>

                                    <?php endif; ?>

                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

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
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL 2: BOOKING FORM MODAL (INTERNAL)                                   -->
<!-- Purpose: Form untuk submit booking laboratorium                          -->
<!-- Type: Bootstrap modal                                                     -->
<!-- ========================================================================= -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
            <div class="modal-body" style="padding: 20px 18px;">
                <h5 style="font-size: 1rem; font-weight: 700; color: #1A202C; margin-bottom: 14px;">Tambah Peminjaman
                    (Internal)</h5>

                <form id="bookingForm">
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-6" style="padding-right: 6px;">
                            <label
                                style="font-size: 0.7rem; color: #4A5568; margin-bottom: 4px; display: block;">Tanggal</label>
                            <input type="text" class="form-control" id="bookingDate"
                                value="<?= htmlspecialchars($data['selected_date']) ?>" readonly
                                style="border: 1px solid #E2E8F0; border-radius: 6px; font-size: 0.8rem; padding: 8px 10px; background: #fff;">
                        </div>
                        <div class="col-6" style="padding-left: 6px;">
                            <label
                                style="font-size: 0.7rem; color: #4A5568; margin-bottom: 4px; display: block;">Hari</label>
                            <input type="text" class="form-control" id="bookingDay"
                                value="<?= strtoupper($data['selected_day']) ?>" readonly
                                style="border: 1px solid #E2E8F0; border-radius: 6px; font-size: 0.8rem; padding: 8px 10px; background: #fff;">
                        </div>
                    </div>

                    <div style="margin-bottom: 10px;">
                        <label
                            style="font-size: 0.7rem; color: #4A5568; margin-bottom: 4px; display: block;">Laboratorium</label>
                        <input type="text" class="form-control" id="bookingLab" value="" readonly
                            style="border: 1px solid #E2E8F0; border-radius: 6px; font-size: 0.8rem; padding: 8px 10px; background: #fff;">
                    </div>

                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-6" style="padding-right: 6px;">
                            <label style="font-size: 0.7rem; color: #4A5568; margin-bottom: 4px; display: block;">Jam
                                Mulai</label>
                            <input type="time" class="form-control" id="jamMulai" value="07:00" step="60"
                                style="border: 1px solid #E2E8F0; border-radius: 6px; font-size: 0.8rem; padding: 8px 10px;">
                        </div>
                        <div class="col-6" style="padding-left: 6px;">
                            <label style="font-size: 0.7rem; color: #4A5568; margin-bottom: 4px; display: block;">Jam
                                Selesai</label>
                            <input type="time" class="form-control" id="jamSelesai" value="08:00" step="60"
                                style="border: 1px solid #E2E8F0; border-radius: 6px; font-size: 0.8rem; padding: 8px 10px;">
                        </div>
                    </div>

                    <div id="slotInfoBox"
                        style="background: #F7FAFC; border-radius: 6px; padding: 10px 12px; border: 1px solid #E2E8F0; margin-bottom: 10px;">
                        <div id="slotInfoText" style="font-weight: 600; font-size: 0.75rem; color: #2D3748;">Slot
                            kosong: 07:00-18:25</div>
                        <div style="font-size: 0.65rem; color: #718096;">Pilih jam mulai/selesai di dalam slot kosong.
                        </div>
                    </div>

                    <div style="margin-bottom: 10px;">
                        <label style="font-size: 0.7rem; color: #4A5568; margin-bottom: 4px; display: block;">Nama
                            Peminjam</label>
                        <input type="text" class="form-control" id="namaPeminjam"
                            value="<?= htmlspecialchars($data['current_user']['nama'] ?? '') ?>" readonly
                            style="border: 1px solid #E2E8F0; border-radius: 6px; font-size: 0.8rem; padding: 8px 10px; background: #F7FAFC; cursor: not-allowed;">
                        <small style="font-size: 0.65rem; color: #718096; display: block; margin-top: 4px;">
                            <i class="fas fa-info-circle"></i> Nama otomatis dari akun yang login
                        </small>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="font-size: 0.7rem; color: #4A5568; margin-bottom: 4px; display: block;">Nama
                            Kegiatan</label>
                        <input type="text" class="form-control" id="namaKegiatan" value="" placeholder="Nama kegiatan"
                            style="border: 1px solid #E2E8F0; border-radius: 6px; font-size: 0.8rem; padding: 8px 10px;">
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 10px;">
                        <button type="button" class="btn" data-bs-dismiss="modal"
                            style="background: #fff; border: 1px solid #E2E8F0; color: #4A5568; padding: 8px 20px; border-radius: 6px; font-size: 0.75rem;">Batal</button>
                        <button type="button" class="btn" onclick="submitBooking()"
                            style="background: #1E3A5F; color: #fff; border: none; padding: 8px 16px; border-radius: 6px; font-size: 0.75rem;">Simpan
                            Peminjaman</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>