<?php
// views/admin/matakuliah/list.php
?>

<!-- TABLE SECTION -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-dark">Daftar Mata Kuliah</h5>
        <span class="badge bg-secondary-subtle text-dark rounded-pill px-3">Total:
            <?= count($data['matakuliah']) ?>
        </span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="mataKuliahTable" width="100%" cellspacing="0">
            <thead class="table-light text-secondary">
                <tr>
                    <th class="px-4 py-3 border-0 rounded-start">No</th>
                    <th class="py-3 border-0">Kode</th>
                    <th class="py-3 border-0">Nama Mata Kuliah</th>
                    <th class="py-3 border-0">Singkatan</th>
                    <th class="py-3 border-0">Smt / SKS</th>
                    <th class="py-3 border-0">Jurusan</th>
                    <th class="px-4 py-3 border-0 text-end rounded-end" data-sortable="false">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($data['matakuliah'] as $row): ?>
                    <tr>
                        <td class="px-4 fw-bold text-secondary">
                            <?= $no++ ?>
                        </td>
                        <td class="font-monospace text-primary">
                            <?= htmlspecialchars($row['kode_matakuliah']) ?>
                        </td>
                        <td class="fw-bold text-dark">
                            <?= htmlspecialchars($row['nama_matakuliah']) ?>
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary-emphasis rounded-pill px-3">
                                <?= htmlspecialchars($row['singkatan'] ?? '-') ?>
                            </span>
                        </td>
                        <td class="text-secondary">
                            Smt
                            <?= htmlspecialchars($row['semester'] ?? '-') ?> /
                            <?= htmlspecialchars($row['sks'] ?? '-') ?> SKS
                        </td>
                        <td>
                            <span class="badge bg-info-subtle text-info-emphasis rounded-pill px-3">
                                <?= htmlspecialchars($row['nama_jurusan'] ?? '-') ?>
                            </span>
                        </td>
                        <td class="text-end px-4">
                            <div class="d-flex justify-content-end gap-2">
                                <button class="btn-icon btn-edit btn-edit" data-id="<?= $row['id'] ?>"
                                    data-nama="<?= htmlspecialchars($row['nama_matakuliah']) ?>"
                                    data-kode="<?= htmlspecialchars($row['kode_matakuliah']) ?>"
                                    data-singkatan="<?= htmlspecialchars($row['singkatan'] ?? '') ?>"
                                    data-semester="<?= htmlspecialchars($row['semester'] ?? '') ?>"
                                    data-sks="<?= htmlspecialchars($row['sks'] ?? '') ?>"
                                    data-jurusan="<?= htmlspecialchars($row['jurusan_id'] ?? '') ?>" data-bs-toggle="modal"
                                    data-bs-target="#editModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="hapusMatakuliah(<?= $row['id'] ?>)" class="btn-icon btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>