<style>
    /* Background Biru Gradasi (Hero Section) */
    .hero-bg {
        background: linear-gradient(135deg, #0d3b66 0%, #0916c9ff 100%);
        color: white;
        padding-top: 80px;
        padding-bottom: 80px;
        /* Ruang agar kartu bisa 'numpang' di atasnya */
        text-align: center;
        margin-bottom: 0;
        /* Pastikan tidak ada margin bawah bawaan */
    }

    /* Kartu Form yang 'Mengambang' */
    .floating-card {
        margin-top: 50px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        padding: 40px;
        margin-bottom: 50px;
        position: relative;
        /* Agar z-index bekerja jika diperlukan */
        z-index: 10;
    }

    /* Styling Input Form */
    .form-control {
        border-radius: 8px;
        padding: 12px 15px;
        border: 1px solid #dee2e6;
    }

    .form-label {
        font-weight: 600;
        font-size: 0.9rem;
        color: #333;
    }

    .btn-submit {
        background-color: #1a2e44;
        color: white;
        padding: 12px;
        font-weight: 600;
        border-radius: 8px;
        width: 100%;
        border: none;
    }

    .btn-submit:hover {
        background-color: #132233;
        color: white;
    }

    .text-danger {
        color: red !important;
    }

    .btn-primary {
        background-color: #1a2e44;
        border: none;
        font-weight: bold;
        padding: 0.5rem 1.2rem;
        border-radius: 8px;
    }

    .btn-primary:hover {
        background-color: #132233;
    }
</style>

<div class="container-fluid hero-bg">
    <div class="container">
        <h2 class="fw-bold">Ajukan Peminjaman Ruangan</h2>
    </div>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="floating-card">

                <h4 class="fw-bold mb-3">Form Pengajuan Peminjaman Ruangan</h4>
                <p class="text-muted mb-4" style="font-size: 0.95rem;">
                    Silakan lengkapi formulir di bawah ini untuk mengajukan peminjaman ruangan laboratorium.
                    Admin kami akan menghubungi Anda untuk konfirmasi lebih lanjut.
                </p>
                <hr class="mb-4">

                <form action="<?= BASE_URL; ?>/external/detail" method="POST" enctype="multipart/form-data">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama lengkap Anda" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="nama@email.com" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="telepon" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="telepon" name="telepon" placeholder="08xxxxxxxxxx" required>
                        </div>
                        <div class="col-md-6">
                            <label for="peserta" class="form-label">Jumlah Peserta <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="peserta" name="jumlah_peserta" placeholder="Contoh: 30" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="kegiatan" class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="kegiatan" name="nama_kegiatan" placeholder="Contoh: Pelatihan Cyber Security" required>
                    </div>

                    <div class="mb-3">
                        <label for="proposal" class="form-label">
                            Upload Proposal Kegiatan <span class="text-danger">*</span>
                        </label>

                        <div class="d-flex align-items-center gap-3">
                            <!-- Tombol Pilih File -->
                            <label class="btn btn-primary mb-0">
                                Pilih File
                                <input type="file" id="proposal" name="proposal" accept=".pdf,.doc,.docx" hidden>
                            </label>

                            <div id="file-name" class="form-text mb-0">
                                Belum ada file dipilih (PDF, DOC, DOCX)
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="tgl_mulai" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tgl_mulai" name="tgl_mulai" required>
                        </div>
                        <div class="col-md-6">
                            <label for="tgl_selesai" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tgl_selesai" name="tgl_selesai" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-submit">Ajukan Peminjaman</button>

                </form>
            </div>
        </div>
    </div>
</div>