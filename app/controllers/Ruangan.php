<?php

class Ruangan extends Controller
{
    public function index()
    {
        // Handle POST Requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost();
            return;
        }

        $model = $this->model('RuanganModel');

        $data = [
            'ruangan' => $model->getAll(),
            'asisten' => $model->getAssistants(),
            'active_page' => 'ruangan'
        ];

        // Load Views
        $this->view('components/admin_head', $data);
        $this->view('components/admin_navbar', $data);
        $this->view('components/admin_sidebar', $data);
        $this->view('admin/ruangan/index', $data);
        $this->view('components/admin_footer', $data);
    }

    private function handlePost()
    {
        $model = $this->model('RuanganModel');
        $action = $_POST['action'] ?? '';

        if ($action === 'create' || $action === 'update') {
            $status = isset($_POST['status']) ? 1 : 0;

            $data = [
                'nama_ruangan' => $_POST['nama_ruangan'] ?? '',
                'kapasitas' => $_POST['kapasitas'] ?? 0,
                'lokasi' => $_POST['lokasi'] ?? '',
                'pic' => $_POST['pic'] ?? '',
                'email_pic' => $_POST['email_pic'] ?? '',
                'fasilitas' => $_POST['fasilitas'] ?? '',
                'deskripsi' => $_POST['deskripsi'] ?? '',
                'gambar' => $_POST['gambar_base64'] ?? '',
                'status' => $status
            ];

            if ($action === 'create') {
                if ($model->create($data)) {
                    header("Location: " . BASE_URL . "ruangan?status=success&msg=Ditambahkan");
                    exit;
                }
            } elseif ($action === 'update') {
                $id = $_POST['id'] ?? 0;
                if ($model->update($id, $data)) {
                    header("Location: " . BASE_URL . "ruangan?status=success&msg=Diperbarui");
                    exit;
                }
            }
        } elseif ($action === 'delete') {
            $id = $_POST['id'] ?? 0;
            if ($model->delete($id)) {
                header("Location: " . BASE_URL . "ruangan?status=success&msg=Dihapus");
                exit;
            }
        }
    }
}
