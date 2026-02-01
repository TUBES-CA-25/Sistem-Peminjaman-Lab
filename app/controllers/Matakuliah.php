<?php
// app/controllers/MatakuliahController.php

require_once __DIR__ . '/../models/MatakuliahModel.php';

class Matakuliah extends Controller
{
    private $matakuliahModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            require_once __DIR__ . '/../views/errors/401.php';
            exit;
        }

        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            require_once __DIR__ . '/../views/errors/403.php';
            exit;
        }
        $this->matakuliahModel = new MatakuliahModel();
    }

    public function index()
    {
        $matakuliah = $this->matakuliahModel->getAll();

        // Load Jurusan Model
        if (!method_exists($this, 'model')) {
            require_once __DIR__ . '/../models/JurusanModel.php';
            $jurusanModel = new JurusanModel();
        } else {
            $jurusanModel = $this->model('JurusanModel');
        }

        $data = [
            'title' => 'Data Mata Kuliah',
            'active_page' => 'matakuliah',
            'matakuliah' => $matakuliah,
            'jurusan_list' => $jurusanModel->getAll()
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
                'kode' => $_POST['kode_matakuliah'],
                'singkatan' => $_POST['singkatan'],
                'semester' => $_POST['semester'],
                'sks' => $_POST['sks'],
                'jurusan_id' => $_POST['jurusan_id']
            ];

            try {
                if ($this->matakuliahModel->create($data)) {
                    header("Location: " . BASE_URL . "/matakuliah?status=success&msg=Mata Kuliah berhasil ditambahkan");
                } else {
                    header("Location: " . BASE_URL . "/matakuliah?status=error&msg=Gagal menambahkan mata kuliah");
                }
            } catch (PDOException $e) {
                if ($e->getCode() == '23000') {
                    header("Location: " . BASE_URL . "/matakuliah?status=error&msg=Gagal: Kode Mata Kuliah sudah ada!");
                } else {
                    header("Location: " . BASE_URL . "/matakuliah?status=error&msg=Error Database: " . $e->getMessage());
                }
            }
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'nama' => $_POST['nama_matakuliah'],
                'kode' => $_POST['kode_matakuliah'],
                'singkatan' => $_POST['singkatan'],
                'semester' => $_POST['semester'],
                'sks' => $_POST['sks'],
                'jurusan_id' => $_POST['jurusan_id']
            ];

            try {
                if ($this->matakuliahModel->update($id, $data)) {
                    header("Location: " . BASE_URL . "/matakuliah?status=success&msg=Mata Kuliah berhasil diupdate");
                } else {
                    header("Location: " . BASE_URL . "/matakuliah?status=success&msg=Data disimpan (Tidak ada perubahan)");
                }
            } catch (PDOException $e) {
                if ($e->getCode() == '23000') {
                    header("Location: " . BASE_URL . "/matakuliah?status=error&msg=Gagal: Kode Mata Kuliah sudah ada!");
                } else {
                    header("Location: " . BASE_URL . "/matakuliah?status=error&msg=Error Database: " . $e->getMessage());
                }
            }
        }
    }

    public function delete($id)
    {
        if ($this->matakuliahModel->delete($id)) {
            header("Location: " . BASE_URL . "/matakuliah?status=success&msg=Mata Kuliah berhasil dihapus");
        } else {
            header("Location: " . BASE_URL . "/matakuliah?status=error&msg=Gagal menghapus mata kuliah");
        }
    }
}
