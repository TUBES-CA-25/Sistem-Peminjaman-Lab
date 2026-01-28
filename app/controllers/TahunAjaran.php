<?php
// app/controllers/TahunAjaran.php

class TahunAjaran extends Controller
{
    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost();
            return;
        }

        $model = $this->model('TahunAjaranModel');
        $data = [
            'tahun_ajaran' => $model->getAll(),
            'active_page' => 'tahun_ajaran' // Need to update sidebar to highlight this
        ];

        // Ensure these views exist or update dashboard router to handle it
        // For now, using standard structure
        $this->view('components/admin_head', $data);
        $this->view('components/admin_navbar', $data);
        $this->view('components/admin_sidebar', $data);
        $this->view('admin/tahun_ajaran/index', $data); // We need to create this view
        $this->view('components/admin_footer', $data);
    }

    private function handlePost()
    {
        $model = $this->model('TahunAjaranModel');
        $action = $_POST['action'] ?? '';

        $data = [
            'nama' => $_POST['nama'] ?? ''
        ];

        if ($action === 'create') {
            if ($model->create($data)) {
                header("Location: " . BASE_URL . "/tahun_ajaran?status=success&msg=Tahun Ajaran berhasil ditambahkan");
                exit;
            }
        } elseif ($action === 'update') {
            $id = $_POST['id'] ?? 0;
            if ($model->update($id, $data)) {
                header("Location: " . BASE_URL . "/tahun_ajaran?status=success&msg=Tahun Ajaran berhasil diperbarui");
                exit;
            }
        } elseif ($action === 'delete') {
            $id = $_POST['id'] ?? 0;
            if ($model->delete($id)) {
                header("Location: " . BASE_URL . "/tahun_ajaran?status=success&msg=Tahun Ajaran berhasil dihapus");
                exit;
            }
        }
    }
}
