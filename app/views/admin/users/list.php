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
                    <th class="py-3 border-0">Status</th>
                    <th class="py-3 border-0">Role</th>
                    <th class="py-3 border-0">Nomor HP</th>
                    <th class="px-4 py-3 border-0 text-end rounded-end" data-sortable="false">Aksi</th>
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
                            <?= htmlspecialchars($u['status'] ?? '-') ?>
                        </td>
                        <td>
                            <?php
                            $badgeClass = match ($u['role']) {
                                'external' => 'bg-primary-subtle text-primary',
                                'internal' => 'bg-warning-subtle text-dark',
                                'admin' => 'bg-dark-subtle text-dark',
                                default => 'bg-secondary'
                            };
                            ?>
                            <span class="badge rounded-pill <?= $badgeClass ?>">
                                <?= ucfirst($u['role']) ?>
                            </span>
                        </td>
                        <td class="text-secondary u-hp">
                            <?= htmlspecialchars($u['telepon'] ?? '-') ?>
                        </td>
                        <td class="text-end px-4">
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn-icon btn-view btn-detail"
                                    data-user='<?= htmlspecialchars(json_encode($u), ENT_QUOTES, "UTF-8") ?>'>
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-icon btn-edit" data-bs-toggle="modal" data-bs-target="#userModal"
                                    onclick='prepareModal("edit", <?= htmlspecialchars(json_encode($u), ENT_QUOTES, "UTF-8") ?>)'>
                                    <i class="fas fa-edit"></i>
                                </button>

<<<<<<< HEAD
                                <?php /* 
               <form action="<?= BASE_URL ?>/user" method="POST"
                   onsubmit="return confirm('Hapus pengguna ini?');">
                   <input type="hidden" name="action" value="delete">
                   <input type="hidden" name="id" value="<?= $u['id'] ?>">
                   <button type="submit"
                       class="btn btn-sm btn-danger fw-bold d-flex align-items-center gap-1">
                       <i class="fas fa-trash"></i> <span class="d-none d-lg-inline">Hapus</span>
                   </button>
               </form> 
               */ ?>
=======

>>>>>>> 12856d23fcda0ca2e7b6339a8e62c71c7f32ee02
                                <button type="button" onclick="hapusUser(<?= $u['id'] ?>)" class="btn-icon btn-delete">
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