<?php
// views/admin/matakuliah/modal.php
?>

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
                                <option value="<?= $j['id'] ?>">
                                    <?= $j['nama_jurusan'] ?> (
                                    <?= $j['singkatan'] ?>)
                                </option>
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
                                <option value="<?= $j['id'] ?>">
                                    <?= $j['nama_jurusan'] ?> (
                                    <?= $j['singkatan'] ?>)
                                </option>
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