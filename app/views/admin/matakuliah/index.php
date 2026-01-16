<!-- app/views/admin/matakuliah/index.php -->
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">Data Mata Kuliah</h2>

        <?php if (isset($_GET['status']) && isset($_GET['msg'])): ?>
            <div class="alert alert-<?= ($_GET['status'] == 'success') ? 'success' : 'danger' ?> alert-dismissible fade show"
                role="alert">
                <?= htmlspecialchars($_GET['msg']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Mata Kuliah</h6>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fas fa-plus fa-sm"></i> Tambah Mata Kuliah
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Mata Kuliah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($data['matakuliah'] as $row): ?>
                                <tr>
                                    <td>
                                        <?= $no++ ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($row['kode_matakuliah']) ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($row['nama_matakuliah']) ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-warning btn-sm btn-edit" data-id="<?= $row['id'] ?>"
                                            data-nama="<?= htmlspecialchars($row['nama_matakuliah']) ?>"
                                            data-kode="<?= htmlspecialchars($row['kode_matakuliah']) ?>"
                                            data-bs-toggle="modal" data-bs-target="#editModal">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="<?= BASE_URL ?>matakuliah/delete/<?= $row['id'] ?>" method="POST"
                                            class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= BASE_URL ?>matakuliah/store" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Mata Kuliah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kode_matakuliah" class="form-label">Kode Mata Kuliah</label>
                        <input type="text" class="form-control" id="kode_matakuliah" name="kode_matakuliah" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_matakuliah" class="form-label">Nama Mata Kuliah</label>
                        <input type="text" class="form-control" id="nama_matakuliah" name="nama_matakuliah" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= BASE_URL ?>matakuliah/update" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Mata Kuliah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label for="edit_kode_matakuliah" class="form-label">Kode Mata Kuliah</label>
                        <input type="text" class="form-control" id="edit_kode_matakuliah" name="kode_matakuliah"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nama_matakuliah" class="form-label">Nama Mata Kuliah</label>
                        <input type="text" class="form-control" id="edit_nama_matakuliah" name="nama_matakuliah"
                            required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Handle Edit Param Population
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('edit_id').value = this.dataset.id;
            document.getElementById('edit_nama_matakuliah').value = this.dataset.nama;
            document.getElementById('edit_kode_matakuliah').value = this.dataset.kode;
        });
    });
</script>