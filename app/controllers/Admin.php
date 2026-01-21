<?php

class Admin extends Controller
{
    public function index()
    {
        // Redirect to default admin page
        header("Location: " . BASE_URL . "/ruangan");
        exit;
    }

    public function pengajuan()
    {
        $data['judul'] = 'Data Pengajuan Peminjaman';
        $data['active_page'] = 'pengajuan'; // Kunci agar menu sidebar menyala

        // Ambil data dari Model
        $data['pengajuan'] = $this->model('PengajuanModel')->getAllPengajuan();

        // 1. Load Header (CSS)
        $this->view('components/admin_head', $data);

        // 2. Load Sidebar (Menu Kiri)
        // Pastikan nama filenya sesuai gambar Anda: 'admin_sidebar'
        $this->view('components/admin_sidebar', $data);

        // 3. Load Navbar (Menu Atas - Tombol Sign Out)
        // Pastikan nama filenya sesuai gambar Anda: 'admin_navbar'
        $this->view('components/admin_navbar', $data);

        // 4. Load Konten Utama (Tabel Pengajuan)
        $this->view('admin/pengajuan/index', $data);

        // 5. Load Footer (Penutup Div & JS)
        $this->view('components/admin_footer');
    }

    public function updateStatusPengajuan()
    {
        // Panggil Model untuk update
        if ($this->model('PengajuanModel')->updateStatus($_POST) > 0) {
            // Bisa tambah Flasher disini
            header('Location: ' . BASE_URL . '/admin/pengajuan');
            exit;
        } else {
            // Jika gagal atau tidak ada perubahan
            header('Location: ' . BASE_URL . '/admin/pengajuan');
            exit;
        }
    }
}
