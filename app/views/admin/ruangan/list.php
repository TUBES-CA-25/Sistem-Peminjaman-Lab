<?php
// app/views/pages/admin/ruangan/list.php
?>
<div class="row g-4 mb-5">
    <?php foreach ($labs as $lab): ?>
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100 lab-card">
                <div class="position-relative">
                    <?php
                    $img = $lab['gambar'];

                    // Handle different path formats:
                    // New: /uploads/labs/lab_xxx.jpg
                    // Old: public/storage/images/xxx.jpg
                    // Old: filename only (xxx.jpg)
                
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
                        // Placeholder if no image
                        $imgSrc = BASE_URL . '/public/img/no-image.png';
                    }
                    ?>
                    <img src="<?= htmlspecialchars($imgSrc) ?>" class="card-img-top"
                        alt="<?= htmlspecialchars($lab['nama_ruangan']) ?>" style="height: 220px; object-fit: cover;">
                </div>
                <div class="card-body p-4 d-flex flex-column">
                    <h5 class="card-title fw-bold text-dark mb-3">
                        <?= htmlspecialchars($lab['nama_ruangan']) ?>
                    </h5>

                    <div class="vstack gap-2 text-secondary mb-4 flex-grow-1">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-users text-primary w-25px"></i>
                            <span>Kapasitas:
                                <?= htmlspecialchars($lab['kapasitas']) ?> orang
                            </span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-user-tie text-primary w-25px"></i>
                            <span>
                                <?= htmlspecialchars($lab['pic']) ?>
                            </span>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button onclick='editLab(<?= htmlspecialchars(json_encode($lab), ENT_QUOTES, "UTF-8") ?>)'
                            class="btn btn-primary flex-grow-1 fw-bold">
                            <i class="fas fa-edit me-1"></i> Edit
                        </button>
                        <div class="flex-grow-1 d-flex">
                            <button type="button" onclick="hapusRuangan(<?= $lab['id'] ?>)"
                                class="btn btn-danger w-100 fw-bold">
                                <i class="fas fa-trash me-1"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
