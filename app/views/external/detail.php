<?php $status = $data['peminjaman']['status']; ?>

<style>
    /* Hero Section */
    .hero-bg {
        background: linear-gradient(135deg, #0d3b66 0%, #1a5f99 100%);
        color: white;
        padding-top: 80px;
        padding-bottom: 80px;
        text-align: center;
        margin-top: 0px;
    }

    .detail-container {
        margin-top: 50px;
        z-index: 10;
        position: relative;
    }

    .card-custom {
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        border: none;
        margin-bottom: 25px;
        padding: 30px;
    }

    /* --- STYLING STATUS (DINAMIS) --- */
    
    /* 1. KUNING (Menunggu) */
    .badge-pending {
        background-color: #ffc107;
        color: #000;
    }
    .info-box-pending {
        background-color: #fff8e1;
        border-left: 4px solid #ffc107;
        color: #856404;
    }

    /* 2. HIJAU (Disetujui) - Sesuai Gambar Baru */
    .badge-success-custom {
        background-color: #b1fedaff; /* Hijau muda pucat */
        color: #0f5132; /* Hijau tua teks */
        font-weight: 700;
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-top: 10px;
        margin-bottom: 20px;
        text-transform: uppercase;
    }

    .info-box-success {
        background-color: #b1fedaff;
        border: 2px solid #20d584ff;
        padding: 20px;
        border-radius: 8px;
        color: #0f5132;
        font-size: 0.9rem;
        display: flex;
        align-items: flex-start;
        gap: 15px;
    }

    /* --- WARNING BOX BAWAH (ORANGE) --- */
    .warning-box-bottom {
        background-color: #fcf3bdff; 
        border: 2px solid #fedb73ff;
        border-radius: 8px;
        padding: 20px;
        margin-top:25px;
        margin-bottom: 50px;
        color: #664d03;
        display: flex;
        gap: 15px;
        align-items: center;
    }
    .warning-icon {
        font-size: 1.5rem;
        color: #d68c08;
    }

    /* Styling Detail Text */
    .detail-row { padding: 15px 0; border-bottom: 1px solid #eee; }
    .detail-row:last-child { border-bottom: none; }
    .detail-label { font-weight: 600; color: #666; font-size: 0.95rem; }
    .detail-value { font-weight: 500; color: #333; font-size: 0.95rem; }
    .link-email { color: #0d6efd; text-decoration: none; }
    
    .btn-download {
        background-color: #1a2e44;
        color: white;
        font-size: 0.85rem;
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        width: 100%; /* Full width seperti desain */
        justify-content: center;
        font-weight: 600;
    }
    .btn-download:hover { background-color: #132233; color: white; }
</style>

<div class="container-fluid hero-bg">
    <div class="container">
        <h2 class="fw-bold">Ajukan Peminjaman Ruangan</h2>
    </div>
</div>

<div class="container detail-container">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            
            <div class="card-custom">
                <h4 class="fw-bold mb-0">Pengajuan Anda Sedang Diproses</h4>
                
                <?php if ($status == 'Disetujui') : ?>
                    
                    <div>
                        <span class="badge-success-custom">
                            <i class="bi bi-check-lg"></i> DISETUJUI
                        </span>
                    </div>
                    <div class="info-box-success">
                        <i class="bi bi-info-circle-fill fs-4"></i>
                        <div>
                            <strong>Selamat! Peminjaman ruangan Anda telah disetujui oleh Admin.</strong><br>
                            Anda dapat menggunakan laboratorium sesuai dengan jadwal yang telah ditentukan di bawah ini.
                            Pastikan untuk datang tepat waktu dan mengikuti semua aturan penggunaan laboratorium.
                        </div>
                    </div>

                <?php else : ?>

                    <div style="margin-top: 10px; margin-bottom: 20px;">
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                            Menunggu Konfirmasi dari Admin
                        </span>
                    </div>
                    <div class="alert alert-warning border-start border-4 border-warning bg-warning-subtle text-dark" role="alert">
                        <strong>Admin akan menghubungi Anda</strong> untuk jadwal pertemuan melalui email atau telepon.
                        Setelah pertemuan, pengajuan akan diproses lebih lanjut.
                    </div>

                <?php endif; ?>
            </div>

            <div class="card-custom">
                <h5 class="fw-bold mb-4">Detail Pengajuan</h5>

                <div class="row detail-row">
                    <div class="col-md-4 detail-label">Nama Lengkap</div>
                    <div class="col-md-8 detail-value"><?= $data['peminjaman']['nama']; ?></div>
                </div>

                <div class="row detail-row">
                    <div class="col-md-4 detail-label">Nama Kegiatan</div>
                    <div class="col-md-8 detail-value"><?= $data['peminjaman']['kegiatan']; ?></div>
                </div>

                <div class="row detail-row">
                    <div class="col-md-4 detail-label">Email</div>
                    <div class="col-md-8 detail-value">
                        <a href="mailto:<?= $data['peminjaman']['email']; ?>" class="link-email">
                            <?= $data['peminjaman']['email']; ?>
                        </a>
                    </div>
                </div>

                <div class="row detail-row">
                    <div class="col-md-4 detail-label">Nomor Telepon</div>
                    <div class="col-md-8 detail-value link-email"><?= $data['peminjaman']['telepon']; ?></div>
                </div>

                <div class="row detail-row">
                    <div class="col-md-4 detail-label">Jumlah Peserta</div>
                    <div class="col-md-8 detail-value"><?= $data['peminjaman']['peserta']; ?> Orang</div>
                </div>

                <div class="row detail-row">
                    <div class="col-md-4 detail-label">Tanggal Mulai</div>
                    <div class="col-md-8 detail-value"><?= $data['peminjaman']['tgl_mulai']; ?></div>
                </div>

                <div class="row detail-row">
                    <div class="col-md-4 detail-label">Tanggal Selesai</div>
                    <div class="col-md-8 detail-value"><?= $data['peminjaman']['tgl_selesai']; ?></div>
                </div>

                <div class="row detail-row border-0 align-items-center">
                    <div class="col-md-4 detail-label">Proposal Kegiatan</div>
                    <div class="col-md-6">
                        <a href="#" class="btn-download">
                            Download Proposal - <?= $data['peminjaman']['file_proposal']; ?>
                        </a>
                    </div>
                </div>

            </div>

            <?php if ($status == 'Disetujui') : ?>
                
                <div class="warning-box-bottom">
                    <div class="warning-icon">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div>
                        <strong class="d-block mb-1" style="color: #997404;">Perhatian Penting</strong>
                        <span style="font-size: 0.9rem;">
                            Harap tiba 15 menit sebelum jadwal dimulai. Bawa kartu identitas dan surat pengantar resmi. 
                            Patuhi semua peraturan penggunaan laboratorium yang berlaku.
                        </span>
                    </div>
                </div>

            <?php else : ?>

                <div class="row mt-4">
                    <div class="col-md-6 mb-2">
                        <a href="#" class="btn btn-primary d-block py-2 fw-bold" style="background-color: #1a2e44; border:none;">
                            Hubungi Admin
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="<?= BASE_URL; ?>/external" class="btn btn-outline-secondary d-block py-2 fw-bold bg-white">
                            <i class="bi bi-arrow-left me-2"></i> Kembali ke Dashboard
                        </a>
                    </div>
                </div>

            <?php endif; ?>

        </div>
    </div>
</div>