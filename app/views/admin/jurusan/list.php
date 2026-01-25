<?php
// app/views/pages/admin/jurusan/list.php
?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-dark">Daftar Jurusan</h5>
        <span class="badge bg-secondary-subtle text-dark rounded-pill px-3">Total:
            <?= count($jurusan) ?>
        </span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-secondary">
                <tr>
                    <th class="px-4 py-3 border-0 rounded-start" width="5%">No</th>
                    <th class="py-3 border-0">Nama Jurusan</th>
                    <th class="py-3 border-0">Singkatan</th>
                    <th class="px-4 py-3 border-0 text-end rounded-end" width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($jurusan)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">Belum ada data jurusan.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($jurusan as $index => $row): ?>
                        <tr>
                            <td class="px-4 fw-bold text-secondary">
                                <?= $index + 1 ?>
                            </td>
                            <td class="fw-bold text-dark">
                                <?= htmlspecialchars($row['nama_jurusan']) ?>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3">
                                    <?= htmlspecialchars($row['singkatan']) ?>
                                </span>
                            </td>
                            <td class="text-end px-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <button class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal"
                                        data-bs-target="#jurusanModal"
                                        onclick='prepareModal("edit", <?= htmlspecialchars(json_encode($row), ENT_QUOTES, "UTF-8") ?>)'>
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <button type="button" onclick="hapusJurusan(<?= $row['id'] ?>)"
                                        class="btn btn-sm btn-danger fw-bold">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>