<?php
// app/controllers/Jurusan.php

class Jurusan extends Controller
{
    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost();
            return;
        }

        $model = $this->model('JurusanModel');
        $data = [
            'jurusan' => $model->getAll(),
            'active_page' => 'jurusan'
        ];

        $this->view('components/admin_head', $data);
        $this->view('components/admin_navbar', $data);
        $this->view('components/admin_sidebar', $data);
        $this->view('admin/jurusan/index', $data);
        $this->view('components/admin_footer', $data);
    }

    private function handlePost()
    {
        $model = $this->model('JurusanModel');
        $action = $_POST['action'] ?? '';

        $data = [
            'nama_jurusan' => $_POST['nama_jurusan'] ?? '',
            'singkatan' => $_POST['singkatan'] ?? ''
        ];

        if ($action === 'create') {
            if ($model->create($data)) {
                header("Location: " . BASE_URL . "/jurusan?status=success&msg=Jurusan berhasil ditambahkan");
                exit;
            }
        } elseif ($action === 'update') {
            $id = $_POST['id'] ?? 0;
            if ($model->update($id, $data)) {
                header("Location: " . BASE_URL . "/jurusan?status=success&msg=Jurusan berhasil diperbarui");
                exit;
            }
        } elseif ($action === 'delete') {
            $id = $_POST['id'] ?? 0;
            if ($model->delete($id)) {
                header("Location: " . BASE_URL . "/jurusan?status=success&msg=Jurusan berhasil dihapus");
                exit;
            }
        }
    }
}
