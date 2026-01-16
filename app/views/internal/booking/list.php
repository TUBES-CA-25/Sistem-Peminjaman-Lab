<?php
// app/views/internal/booking/list.php
// Render lab cards
?>

<section class="labs-section py-5">
    <div class="container">
        <div class="row g-4">
            <?php foreach ($data['labs'] as $lab): ?>
            <div class="col-md-6 col-lg-4">
                <div class="lab-card">
                    <div class="lab-image">
                        <img src="<?= BASE_URL ?>public/img/<?= htmlspecialchars($lab['image']) ?>" 
                             alt="<?= htmlspecialchars($lab['name']) ?>"
                             onerror="this.src='https://images.unsplash.com/photo-1497366216548-37526070297c?w=400&h=250&fit=crop'">
                        <span class="badge-status badge-<?= $lab['status'] ?>">
                            <?= ucfirst($lab['status']) ?>
                        </span>
                    </div>
                    <div class="lab-card-body">
                        <h5 class="lab-name"><?= htmlspecialchars($lab['name']) ?></h5>
                        <p class="lab-info"><i class="bi bi-people-fill"></i> Kapasitas: <?= $lab['capacity'] ?> orang</p>
                        <p class="lab-info"><i class="bi bi-building"></i> <?= htmlspecialchars($lab['building']) ?></p>
                        <p class="lab-info"><i class="bi bi-person-badge"></i> <?= htmlspecialchars($lab['pic']) ?></p>
                        <button class="btn btn-primary w-100" onclick="openScheduleModal(<?= $lab['id'] ?>, '<?= htmlspecialchars($lab['short_name']) ?>')">Booking</button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
