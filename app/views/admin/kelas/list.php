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
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kelas</th>
                        <th>Jurusan</th>
                        <th>Angkatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($data['kelas'] as $row): ?>
                        <tr>
                            <td>
                                <?= $no++ ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($row['nama_kelas']) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($row['nama_jurusan'] ?? '-') ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($row['angkatan'] ?? '-') ?>
                            </td>
                            <td>
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
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>