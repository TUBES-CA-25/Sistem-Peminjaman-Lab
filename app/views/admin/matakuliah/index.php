<?php
$matakuliah = $data['matakuliah'] ?? [];
?>

<!-- HEADER SECTION -->
<div class="card border-0 shadow-sm mb-4 bg-gradient-primary-custom text-white overflow-hidden">
    <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h1 class="h2 fw-bold mb-1 text-white">Data Mata Kuliah</h1>
            <p class="mb-0 opacity-75">Kelola data mata kuliah, sks, dan semester.</p>
        </div>
        <button type="button" class="btn btn-light fw-bold d-flex align-items-center gap-2 shadow-sm"
            data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus text-primary"></i>
            Tambah Mata Kuliah
        </button>
    </div>
</div>

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
                        <th>Kode</th>
                        <th>Nama Mata Kuliah</th>
                        <th>Singkatan</th>
                        <th>Smt / SKS</th>
                        <th>Jurusan</th>
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
                                <?= htmlspecialchars($row['singkatan'] ?? '-') ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($row['semester'] ?? '-') ?> /
                                <?= htmlspecialchars($row['sks'] ?? '-') ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($row['nama_jurusan'] ?? '-') ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary fw-bold btn-edit" data-id="<?= $row['id'] ?>"
                                    data-nama="<?= htmlspecialchars($row['nama_matakuliah']) ?>"
                                    data-kode="<?= htmlspecialchars($row['kode_matakuliah']) ?>"
                                    data-singkatan="<?= htmlspecialchars($row['singkatan'] ?? '') ?>"
                                    data-semester="<?= htmlspecialchars($row['semester'] ?? '') ?>"
                                    data-sks="<?= htmlspecialchars($row['sks'] ?? '') ?>"
                                    data-jurusan="<?= htmlspecialchars($row['jurusan_id'] ?? '') ?>" data-bs-toggle="modal"
                                    data-bs-target="#editModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="hapusMatakuliah(<?= $row['id'] ?>)" class="btn btn-sm btn-danger fw-bold">
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

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= BASE_URL ?>/matakuliah/store" method="POST">
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
                    <div class="mb-3">
                        <label for="singkatan" class="form-label">Singkatan</label>
                        <input type="text" class="form-control" id="singkatan" name="singkatan" required maxlength="20">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="semester" class="form-label">Semester</label>
                            <select class="form-select" id="semester" name="semester" required>
                                <option value="Ganjil">Ganjil</option>
                                <option value="Genap">Genap</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="sks" class="form-label">SKS</label>
                            <input type="number" class="form-control" id="sks" name="sks" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="jurusan_id" class="form-label">Jurusan</label>
                        <select class="form-select" id="jurusan_id" name="jurusan_id" required>
                            <option value="">Pilih Jurusan</option>
                            <?php foreach ($data['jurusan_list'] as $j): ?>
                                <option value="<?= $j['id'] ?>"><?= $j['nama_jurusan'] ?> (<?= $j['singkatan'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
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
        <form action="<?= BASE_URL ?>/matakuliah/update" method="POST">
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
                    <div class="mb-3">
                        <label for="edit_singkatan" class="form-label">Singkatan</label>
                        <input type="text" class="form-control" id="edit_singkatan" name="singkatan" required
                            maxlength="20">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_semester" class="form-label">Semester</label>
                            <select class="form-select" id="edit_semester" name="semester" required>
                                <option value="Ganjil">Ganjil</option>
                                <option value="Genap">Genap</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_sks" class="form-label">SKS</label>
                            <input type="number" class="form-control" id="edit_sks" name="sks" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_jurusan_id" class="form-label">Jurusan</label>
                        <select class="form-select" id="edit_jurusan_id" name="jurusan_id" required>
                            <option value="">Pilih Jurusan</option>
                            <?php foreach ($data['jurusan_list'] as $j): ?>
                                <option value="<?= $j['id'] ?>"><?= $j['nama_jurusan'] ?> (<?= $j['singkatan'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
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
            document.getElementById('edit_singkatan').value = this.dataset.singkatan;
            document.getElementById('edit_semester').value = this.dataset.semester;
            document.getElementById('edit_sks').value = this.dataset.sks;
            document.getElementById('edit_jurusan_id').value = this.dataset.jurusan;
        });
    });

    window.hapusMatakuliah = function (id) {
        Swal.fire({
            title: 'Hapus Mata Kuliah?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?= BASE_URL ?>/matakuliah/delete/' + id;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>