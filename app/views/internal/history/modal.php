<?php
// views/internal/history/modal.php
?>

<!-- EDIT MODAL -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
            <div class="modal-header" style="border-bottom: 1px solid #e5e7eb; padding: 20px;">
                <h5 class="modal-title" style="font-weight: 700;">Edit Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding: 20px;">
                <form id="editForm">
                    <input type="hidden" id="editId">
                    
                    <div class="mb-3">
                        <label class="form-label" style="font-size: 0.85rem; font-weight: 600;">Laboratorium</label>
                        <input type="text" class="form-control" id="editLab" readonly style="background: #f8fafc;">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label" style="font-size: 0.85rem; font-weight: 600;">Tanggal</label>
                        <input type="date" class="form-control" id="editTanggal">
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label" style="font-size: 0.85rem; font-weight: 600;">Jam Mulai</label>
                            <input type="time" class="form-control" id="editJamMulai">
                        </div>
                        <div class="col-6">
                            <label class="form-label" style="font-size: 0.85rem; font-weight: 600;">Jam Selesai</label>
                            <input type="time" class="form-control" id="editJamSelesai">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label" style="font-size: 0.85rem; font-weight: 600;">Keterangan</label>
                        <textarea class="form-control" id="editKeterangan" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #e5e7eb; padding: 16px 20px;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="saveEdit()" style="background: #1E3A5F; border: none;">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- DELETE CONFIRMATION MODAL -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content" style="border-radius: 12px; border: none;">
            <div class="modal-body text-center" style="padding: 30px;">
                <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: #EF4444; margin-bottom: 16px;"></i>
                <h5 style="font-weight: 700; margin-bottom: 8px;">Hapus Peminjaman?</h5>
                <p style="color: #64748b; margin-bottom: 24px;">Peminjaman yang dihapus tidak dapat dikembalikan.</p>
                <input type="hidden" id="deleteId">
                <div style="display: flex; gap: 12px; justify-content: center;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="padding: 10px 24px;">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="confirmDelete()" style="padding: 10px 24px;">Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>
