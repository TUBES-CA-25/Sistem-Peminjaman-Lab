<?php
// views/admin/kelas/list.php
?>

<!-- NOTIFICATION -->
<?php if (isset($_GET['status']) && isset($_GET['msg'])): ?>
    <?php
    $alertClass = $_GET['status'] == 'success' ? 'alert-success' : 'alert-danger';
    $icon = $_GET['status'] == 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    ?>
    <div class="alert <?= $alertClass ?> alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
        <i class="fas <?= $icon ?> me-2"></i> <strong>
            <?= htmlspecialchars($_GET['msg']) ?>
        </strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- TABLE SECTION -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-dark">Daftar Kelas</h5>
        <span class="badge bg-secondary-subtle text-dark rounded-pill px-3">Total: <?= count($data['kelas']) ?></span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="kelasTable" width="100%" cellspacing="0">
            <thead class="table-light text-secondary">
                <tr>
                    <th class="px-4 py-3 border-0 rounded-start">No</th>
                    <th class="py-3 border-0">Nama Kelas</th>
                    <th class="py-3 border-0">Jurusan</th>
                    <th class="py-3 border-0">Angkatan</th>
                    <th class="px-4 py-3 border-0 text-end rounded-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($data['kelas'] as $row): ?>
                    <tr>
                        <td class="px-4 fw-bold text-secondary">
                            <?= $no++ ?>
                        </td>
                        <td class="fw-bold text-dark">
                            <?= htmlspecialchars($row['nama_kelas']) ?>
                        </td>
                        <td>
                            <span class="badge bg-info-subtle text-info-emphasis rounded-pill px-3">
                                <?= htmlspecialchars($row['nama_jurusan'] ?? '-') ?>
                            </span>
                        </td>
                        <td class="text-secondary">
                            <?= htmlspecialchars($row['angkatan'] ?? '-') ?>
                        </td>
                        <td class="text-end px-4">
                            <div class="d-flex justify-content-end gap-2">
                                <button class="btn btn-sm btn-primary fw-bold btn-edit" data-id="<?= $row['id'] ?>"
                                    data-nama="<?= htmlspecialchars($row['nama_kelas']) ?>"
                                    data-jurusan="<?= $row['jurusan_id'] ?>"
                                    data-angkatan="<?= htmlspecialchars($row['angkatan']) ?>" data-bs-toggle="modal"
                                    data-bs-target="#editModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="hapusKelas(<?= $row['id'] ?>)" class="btn btn-sm btn-danger fw-bold">
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