<?php
// app/controllers/MatakuliahController.php

require_once __DIR__ . '/../models/MatakuliahModel.php';

class Matakuliah extends Controller
{
    private $matakuliahModel;

    public function __construct()
    {
        $this->matakuliahModel = new MatakuliahModel();
    }

    public function index()
    {
        $matakuliah = $this->matakuliahModel->getAll();
        $data = [
            'title' => 'Data Mata Kuliah',
            'active_page' => 'matakuliah',
            'matakuliah' => $matakuliah
        ];

        $this->view('components/admin_head', $data);
        $this->view('components/admin_navbar', $data);
        $this->view('components/admin_sidebar', $data);
        $this->view('admin/matakuliah/index', $data);
        $this->view('components/admin_footer', $data);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nama' => $_POST['nama_matakuliah'],
                'kode' => $_POST['kode_matakuliah']
            ];

            if ($this->matakuliahModel->create($data)) {
                header("Location: " . BASE_URL . "matakuliah?status=success&msg=Mata Kuliah berhasil ditambahkan");
            } else {
                header("Location: " . BASE_URL . "matakuliah?status=error&msg=Gagal menambahkan mata kuliah");
            }
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'nama' => $_POST['nama_matakuliah'],
                'kode' => $_POST['kode_matakuliah']
            ];

            if ($this->matakuliahModel->update($id, $data)) {
                header("Location: " . BASE_URL . "matakuliah?status=success&msg=Mata Kuliah berhasil diupdate");
            } else {
                header("Location: " . BASE_URL . "matakuliah?status=error&msg=Gagal update mata kuliah");
            }
        }
    }

    public function delete($id)
    {
        if ($this->matakuliahModel->delete($id)) {
            header("Location: " . BASE_URL . "matakuliah?status=success&msg=Mata Kuliah berhasil dihapus");
        } else {
            header("Location: " . BASE_URL . "matakuliah?status=error&msg=Gagal menghapus mata kuliah");
        }
    }
}
