<?php
// app/controllers/KelasController.php

require_once __DIR__ . '/../models/KelasModel.php';

class Kelas extends Controller
{
    private $kelasModel;

    public function __construct()
    {
        $this->kelasModel = new KelasModel();
    }

    public function index()
    {
        $kelas = $this->kelasModel->getAll();
        $data = [
            'title' => 'Data Kelas',
            'active_page' => 'kelas',
            'kelas' => $kelas
        ];

        $this->view('components/admin_head', $data);
        $this->view('components/admin_navbar', $data);
        $this->view('components/admin_sidebar', $data);
        $this->view('admin/kelas/index', $data);
        $this->view('components/admin_footer', $data);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_kelas = $_POST['nama_kelas'];

            if ($this->kelasModel->create($nama_kelas)) {
                header("Location: " . BASE_URL . "kelas?status=success&msg=Kelas berhasil ditambahkan");
            } else {
                header("Location: " . BASE_URL . "kelas?status=error&msg=Gagal menambahkan kelas");
            }
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $nama_kelas = $_POST['nama_kelas'];

            if ($this->kelasModel->update($id, $nama_kelas)) {
                header("Location: " . BASE_URL . "kelas?status=success&msg=Kelas berhasil diupdate");
            } else {
                header("Location: " . BASE_URL . "kelas?status=error&msg=Gagal update kelas");
            }
        }
    }

    public function delete($id)
    {
        if ($this->kelasModel->delete($id)) {
            header("Location: " . BASE_URL . "kelas?status=success&msg=Kelas berhasil dihapus");
        } else {
            header("Location: " . BASE_URL . "kelas?status=error&msg=Gagal menghapus kelas");
        }
    }
}
