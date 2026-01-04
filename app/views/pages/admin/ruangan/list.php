<?php
// app/views/pages/admin/ruangan/list.php
?>
<div class="row g-4 mb-5">
    <?php foreach ($labs as $lab): ?>
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100 lab-card">
                <div class="position-relative">
                    <?php $imgSrc = $lab['gambar'] ? $lab['gambar'] : '../../public/img/default-lab.jpg'; ?>
                    <img src="<?= htmlspecialchars($imgSrc) ?>" class="card-img-top"
                        alt="<?= htmlspecialchars($lab['nama_ruangan']) ?>" style="height: 220px; object-fit: cover;">
                    <span
                        class="position-absolute top-0 end-0 m-3 badge rounded-pill <?= $lab['status'] ? 'bg-success' : 'bg-danger' ?> shadow-sm">
                        <?= $lab['status'] ? 'Tersedia' : 'Tidak Tersedia' ?>
                    </span>
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
                            <i class="fas fa-map-marker-alt text-primary w-25px"></i>
                            <span>
                                <?= htmlspecialchars($lab['lokasi']) ?>
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
                        <form action="dashboard.php?page=ruangan" method="POST"
                            onsubmit="return confirm('Hapus ruangan ini?');" class="flex-grow-1 d-flex">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $lab['id'] ?>">
                            <button type="submit" class="btn btn-danger w-100 fw-bold">
                                <i class="fas fa-trash me-1"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>