<?php

class Pengajuan extends Controller
{
    public function __construct()
    {
        // Pastikan Session Login Admin aktif di sini
        // if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
        //     header('Location: ' . BASE_URL . '/login');
        //     exit;
        // }
    }

    // Halaman Utama Admin Pengajuan (index)
    public function index()
    {
        $data['judul'] = 'Admin - Verifikasi Pengajuan';

        // Mengambil SEMUA data (getAllPengajuan sama dengan getRiwayat tapi untuk admin)
        $data['pengajuan'] = $this->model('PengajuanModel')->getAllPengajuan();
        $data['active_page'] = 'pengajuan';

        // Load View Admin
        // Pastikan Anda sudah punya admin_sidebar, admin_navbar, dll
        $this->view('components/admin_head', $data);
        $this->view('components/admin_sidebar', $data);
        $this->view('components/admin_navbar', $data);
        $this->view('admin/pengajuan/index', $data); // Ini memuat folder views/admin/pengajuan/
        $this->view('components/admin_footer');
    }

    // Method Khusus Admin: Update Status & Tanggal
    // Ini yang dipanggil oleh form di modal.php Admin
    public function updateAdmin()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Validasi ID
            if (empty($_POST['id'])) {
                echo "<script>alert('Error: ID tidak ditemukan.'); window.history.back();</script>";
                return;
            }

            // Tangkap Data dari Form Admin
            $data = [
                'id' => $_POST['id'],
                'tgl_mulai' => $_POST['tgl_mulai'],
                'tgl_selesai' => $_POST['tgl_selesai'],
                'status' => $_POST['status'],
                // Ambil alasan hanya jika status Ditolak
                'alasan_penolakan' => ($_POST['status'] == 'Ditolak') ? $_POST['alasan_penolakan'] : null
            ];

            // Panggil Model (Pastikan method updatePengajuanAdmin ada di Model)
            if ($this->model('PengajuanModel')->updatePengajuanAdmin($data) > 0) {
                // Berhasil
                echo "<script>
                        alert('✅ Berhasil! Data pengajuan telah diperbarui.');
                        window.location.href='" . BASE_URL . "/pengajuan';
                      </script>";
                exit;
            } else {
                // Tidak ada perubahan / Gagal
                echo "<script>
                        alert('ℹ️ Data disimpan (Tidak ada perubahan detil).');
                        window.location.href='" . BASE_URL . "/pengajuan';
                      </script>";
                exit;
            }
        }
    }

    // Download Proposal (Secure Proxy dengan Access Control)
    public function downloadProposal($id)
    {
        // Check login
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        $pengajuanModel = $this->model('PengajuanModel');
        $proposal = $pengajuanModel->getById($id);

        if (!$proposal) {
            die('Proposal not found');
        }

        // Validasi akses: hanya admin atau pemilik proposal
        if ($_SESSION['role'] != 'admin' && isset($proposal['user_id']) && $proposal['user_id'] != $_SESSION['user_id']) {
            die('Unauthorized: Access denied');
        }

        if (empty($proposal['file_proposal'])) {
            die('File not found in database');
        }

        $filePath = __DIR__ . '/../../storage/uploads/proposals/' . $proposal['file_proposal'];

        if (!file_exists($filePath)) {
            die('File not found on server: ' . $proposal['file_proposal']);
        }

        // Determine MIME type
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];
        $contentType = $mimeTypes[$extension] ?? 'application/octet-stream';

        // Force download
        header('Content-Type: ' . $contentType);
        header('Content-Disposition: attachment; filename="proposal_' . $id . '.' . $extension . '"');
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: no-cache, must-revalidate');
        readfile($filePath);
        exit;
    }




    // FITUR EXPORT KE EXCEL (.XLS)
    public function export()
    {
        // 1. Ambil Data
        $data = $this->model('PengajuanModel')->getAllPengajuan();

        // 2. Set Nama File
        $filename = "Data_Pengajuan_" . date('Y-m-d_H-i') . ".xls";

        // 3. Header agar dibaca sebagai Excel
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        // 4. Mulai Output HTML Table
        // Tambahkan styling CSS inline agar rapi di Excel
        ?>
        <!DOCTYPE html>
        <html>

        <head>
            <meta charset="utf-8">
            <style>
                .header {
                    background-color: #1F45AC;
                    color: white;
                    font-weight: bold;
                    text-align: center;
                }

                .text-center {
                    text-align: center;
                }

                .text-left {
                    text-align: left;
                }

                table {
                    border-collapse: collapse;
                    width: 100%;
                }

                td,
                th {
                    border: 1px solid #000000;
                    padding: 5px;
                }
            </style>
        </head>

        <body>
            <h3>Laporan Data Pengajuan Peminjaman</h3>
            <table>
                <thead>
                    <tr>
                        <th class="header">No</th>
                        <th class="header">Nama Pemohon</th>
                        <th class="header">Email</th>
                        <th class="header">Telepon</th>
                        <th class="header">Nama Kegiatan</th>
                        <th class="header">Jml Peserta</th>
                        <th class="header">Tgl Mulai</th>
                        <th class="header">Tgl Selesai</th>
                        <th class="header">Status</th>
                        <th class="header">Alasan (Jika Ditolak)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($data as $row): ?>
                        <?php
                        // Logic Warna Status Sederhana
                        $bgStatus = '';
                        if ($row['status'] == 'Disetujui')
                            $bgStatus = '#d1e7dd'; // Hijau muda
                        elseif ($row['status'] == 'Ditolak')
                            $bgStatus = '#f8d7da'; // Merah muda
                        ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td><?= $row['nama_lengkap']; ?></td>
                            <td><?= $row['email']; ?></td>

                            <td style="mso-number-format:'\@'"><?= $row['telepon']; ?></td>

                            <td><?= $row['nama_kegiatan']; ?></td>
                            <td class="text-center"><?= $row['jumlah_peserta']; ?></td>
                            <td class="text-center"><?= $row['tgl_mulai']; ?></td>
                            <td class="text-center"><?= $row['tgl_selesai']; ?></td>

                            <td style="background-color: <?= $bgStatus; ?>; text-align:center;">
                                <?= $row['status']; ?>
                            </td>

                            <td><?= $row['alasan_penolakan'] ?? '-'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </body>

        </html>
        <?php
        exit;
    }
}