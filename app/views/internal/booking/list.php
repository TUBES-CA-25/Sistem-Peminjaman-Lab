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
                            <?php
                            $img = $lab['image'];
                            // Use stored path if it contains '/', otherwise assume it's in storage/images
                            $path = (strpos($img, '/') !== false) ? $img : 'public/storage/images/' . $img;
                            $imgSrc = BASE_URL . '/' . $path;
                            ?>
                            <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($lab['name']) ?>"
                                onerror="this.src='https://images.unsplash.com/photo-1497366216548-37526070297c?w=400&h=250&fit=crop'">
                        </div>
                        <div class="lab-card-body">
                            <h5 class="lab-name"><?= htmlspecialchars($lab['name']) ?></h5>
                            <p class="lab-info"><i class="bi bi-people-fill"></i> Kapasitas: <?= $lab['capacity'] ?> orang
                            </p>
                            <p class="lab-info"><i class="bi bi-building"></i> <?= htmlspecialchars($lab['building']) ?></p>
                            <p class="lab-info"><i class="bi bi-person-badge"></i> <?= htmlspecialchars($lab['pic']) ?></p>
                            <button class="btn btn-primary w-100"
                                onclick="openScheduleModal(<?= $lab['id'] ?>, '<?= htmlspecialchars($lab['short_name']) ?>')">Booking</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>