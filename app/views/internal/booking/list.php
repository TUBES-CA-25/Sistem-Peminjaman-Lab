<?php
// app/views/internal/booking/list.php
// Render lab cards
?>

<section class="labs-section py-2">
    <div class="row g-4">
        <?php foreach ($data['labs'] as $lab): ?>
            <div class="col-md-6 col-lg-4">
                <div class="lab-card">
                    <div class="lab-image">
                        <?php
                        $img = $lab['image'];

                        // Handle different path formats (same logic as admin)
                        if (!empty($img)) {
                            // If starts with /, already absolute path (new format)
                            if ($img[0] === '/') {
                                $imgSrc = BASE_URL . $img;
                            }
                            // If contains 'public/', use as-is
                            elseif (strpos($img, 'public/') === 0) {
                                $imgSrc = BASE_URL . '/' . $img;
                            }
                            // Filename only, assume old location
                            else {
                                $imgSrc = BASE_URL . '/public/storage/images/' . $img;
                            }
                        } else {
                            $imgSrc = 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=400&h=250&fit=crop';
                        }
                        ?>
                        <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($lab['name']) ?>"
                            onerror="this.src='https://images.unsplash.com/photo-1497366216548-37526070297c?w=400&h=250&fit=crop'">
                    </div>
                    <div class="lab-card-body">
                        <h5 class="lab-name"><?= htmlspecialchars($lab['name']) ?></h5>
                        <p class="lab-info"><i class="bi bi-people-fill"></i> Kapasitas: <?= $lab['capacity'] ?> orang
                        </p>
                        <p class="lab-info"><i class="bi bi-person-badge"></i> <?= htmlspecialchars($lab['pic']) ?></p>
                        <button class="btn btn-primary w-100"
                            onclick="openScheduleModal(<?= $lab['id'] ?>, '<?= htmlspecialchars($lab['short_name']) ?>')">Booking</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>