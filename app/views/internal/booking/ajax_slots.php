<?php
// app/views/internal/booking/ajax_slots.php
// View fragment untuk respons AJAX
?>

<?php foreach ($data['slots'] as $slot): ?>
    
    <?php if ($slot['type'] == 'praktikum'): $j = $slot['data']; ?>
        <div class="p-slot praktikum">
            <span class="p-slot-label">Praktikum: <?= substr($j['jam_mulai'], 0, 5) ?>-<?= substr($j['jam_selesai'], 0, 5) ?></span>
            <span class="p-slot-sub"><?= htmlspecialchars($j['matkul']) ?> (<?= $j['kelas'] ?>)</span>
        </div>

    <?php elseif ($slot['type'] == 'peminjaman'): $p = $slot['data']; ?>
        <?php 
            $slotClass = 'internal';
            $slotLabel = 'Internal';
            if ($p['type'] == 'external') { $slotClass = 'eksternal'; $slotLabel = 'Eksternal'; }
            elseif ($p['type'] == 'tergeser') { $slotClass = 'tergeser'; $slotLabel = 'Tergeser'; }
        ?>
        <div class="p-slot <?= $slotClass ?>">
            <span class="p-slot-label"><?= $slotLabel ?>: <?= $p['jam_mulai'] ?>-<?= $p['jam_selesai'] ?></span>
            <span class="p-slot-sub"><?= htmlspecialchars($p['keterangan']) ?> - <span style="font-weight: 600;"><?= htmlspecialchars($p['peminjam']) ?></span></span>
        </div>

    <?php elseif ($slot['type'] == 'kosong'): $k = $slot['data']; ?>
        <div class="p-slot available" onclick="openBookingModal('<?= htmlspecialchars($data['labName']) ?>', '<?= $k['mulai'] ?>', '<?= $k['selesai'] ?>')">
            <span class="p-slot-label">+ Pinjam</span>
            <span class="p-slot-sub">Kosong <?= $k['mulai'] ?>-<?= $k['selesai'] ?></span>
        </div>

    <?php endif; ?>

<?php endforeach; ?>

<?php if (empty($data['slots'])): ?>
    <div style="text-align: center; color: #64748b; padding: 20px; font-style: italic;">
        Tidak ada jadwal
    </div>
<?php endif; ?>
