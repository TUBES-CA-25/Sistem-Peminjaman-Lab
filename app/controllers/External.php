<?php

class External extends Controller
{
    public function __construct()
    {
        // Cek login & role (sesuaikan dengan logic auth Anda)
        // if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'external') {
        //     header('Location: ' . BASE_URL . '/auth/login');
        //     exit;
        // }
    }

    public function index()
    {
        $data['judul'] = 'Ajukan Peminjaman';
        $this->view('components/header', $data);
        $this->view('components/external_navbar', $data);
        $this->view('external/index', $data);
        $this->view('components/footer');
    }

    public function detail()
    {
        $data['judul'] = 'Detail Pengajuan';

        // Data Dummy 
        $data['peminjaman'] = [
            'nama' => 'Bripka Ahmad Fauzi',
            'kegiatan' => 'Pelatihan Cyber Security untuk Personel Polri Wilayah Makassar',
            'email' => 'ahmad.fauzi@polri.go.id',
            'telepon' => '0812-3456-7890',
            'peserta' => '35',
            'tgl_mulai' => '2025-01-15',
            'tgl_selesai' => '2025-01-17',
            'status' => 'Disetujui',

            'file_proposal' => 'Pelatihan_CyberSecurity.pdf'
        ];

        $this->view('components/header', $data);
        $this->view('components/external_navbar', $data);
        $this->view('external/detail', $data);
        $this->view('components/footer');
    }
}
