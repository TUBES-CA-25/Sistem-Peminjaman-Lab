<?php
// app/views/pages/admin/pengguna/list.php
?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-dark">Daftar Pengguna</h5>
        <span class="badge bg-secondary-subtle text-dark rounded-pill px-3">Total:
            <?= count($users) ?>
        </span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="usersTable">
            <thead class="table-light text-secondary">
                <tr>
                    <th class="px-4 py-3 border-0 rounded-start">No</th>
                    <th class="py-3 border-0">Nama</th>
                    <th class="py-3 border-0">Email</th>
                    <th class="py-3 border-0">Posisi</th>
                    <th class="py-3 border-0">Role</th>
                    <th class="py-3 border-0">Status</th>
                    <th class="px-4 py-3 border-0 text-end rounded-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $index => $u): ?>
                    <tr class="user-row" data-role="<?= $u['role'] ?>">
                        <td class="px-4 fw-bold text-secondary">
                            <?= $index + 1 ?>
                        </td>
                        <td class="fw-bold text-dark u-name">
                            <?= htmlspecialchars($u['nama']) ?>
                        </td>
                        <td class="text-secondary u-email">
                            <?= htmlspecialchars($u['email']) ?>
                        </td>
                        <td class="text-secondary u-posisi">
                            <?= htmlspecialchars($u['posisi'] ?? '-') ?>
                        </td>
                        <td>
                            <?php
                            $badgeClass = match ($u['role']) {
                                'eksternal' => 'bg-primary-subtle text-primary',
                                'internal' => 'bg-success-subtle text-success',
                                'admin' => 'bg-warning-subtle text-warning',
                                default => 'bg-secondary'
                            };
                            ?>
                            <span class="badge rounded-pill <?= $badgeClass ?>">
                                <?= ucfirst($u['role']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($u['status'] == 'aktif'): ?>
                                <span class="badge rounded-pill bg-success-subtle text-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge rounded-pill bg-danger-subtle text-danger">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end px-4">
                            <div class="d-flex justify-content-end gap-2">
                                <button class="btn btn-sm btn-primary fw-bold d-flex align-items-center gap-1"
                                    data-bs-toggle="modal" data-bs-target="#userModal"
                                    onclick='prepareModal("edit", <?= htmlspecialchars(json_encode($u), ENT_QUOTES, "UTF-8") ?>)'>
                                    <i class="fas fa-edit"></i> <span class="d-none d-lg-inline">Edit</span>
                                </button>

                                <form action="dashboard.php?page=pengguna" method="POST"
                                    onsubmit="return confirm('Hapus pengguna ini?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                    <button type="submit"
                                        class="btn btn-sm btn-danger fw-bold d-flex align-items-center gap-1">
                                        <i class="fas fa-trash"></i> <span class="d-none d-lg-inline">Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>