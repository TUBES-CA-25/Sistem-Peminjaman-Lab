<?php
// app/controllers/KelasController.php

require_once __DIR__ . '/../models/KelasModel.php';

class Kelas extends Controller
{
    private $kelasModel;

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
        $this->kelasModel = new KelasModel();
    }

    public function index()
    {
        $kelas = $this->kelasModel->getAll();
        $jurusanModel = $this->model('JurusanModel'); // Ensure Controller base class has model() method or use standard instantiation

        // Fallback if model() is not available in parent (based on line 12 use of new KelasModel())
        if (!method_exists($this, 'model')) {
            require_once __DIR__ . '/../models/JurusanModel.php';
            $jurusanModel = new JurusanModel();
        } else {
            $jurusanModel = $this->model('JurusanModel');
        }

        $data = [
            'title' => 'Data Kelas',
            'active_page' => 'kelas',
            'kelas' => $kelas,
            'jurusan_list' => $jurusanModel->getAll()
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
            $data = [
                'nama_kelas' => $_POST['nama_kelas'],
                'jurusan_id' => $_POST['jurusan_id'],
                'angkatan' => $_POST['angkatan']
            ];

            if ($this->kelasModel->create($data)) {
                header("Location: " . BASE_URL . "/kelas?status=success&msg=Kelas berhasil ditambahkan");
            } else {
                header("Location: " . BASE_URL . "/kelas?status=error&msg=Gagal menambahkan kelas");
            }
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'nama_kelas' => $_POST['nama_kelas'],
                'jurusan_id' => $_POST['jurusan_id'],
                'angkatan' => $_POST['angkatan']
            ];

            if ($this->kelasModel->update($id, $data)) {
                header("Location: " . BASE_URL . "/kelas?status=success&msg=Kelas berhasil diupdate");
            } else {
                header("Location: " . BASE_URL . "/kelas?status=error&msg=Gagal update kelas");
            }
        }
    }

    public function delete($id)
    {
        if ($this->kelasModel->delete($id)) {
            header("Location: " . BASE_URL . "/kelas?status=success&msg=Kelas berhasil dihapus");
        } else {
            header("Location: " . BASE_URL . "/kelas?status=error&msg=Gagal menghapus kelas");
        }
    }
}
