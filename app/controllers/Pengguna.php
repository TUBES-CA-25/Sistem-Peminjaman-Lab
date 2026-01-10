<?php

class Pengguna extends Controller
{
    public function index()
    {
        // Handle POST Requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost();
            return;
        }

        $model = $this->model('PenggunaModel');

        $data = [
            'pengguna' => $model->getAll(),
            'active_page' => 'pengguna'
        ];

        // Load Views
        $this->view('components/admin_head', $data);
        $this->view('components/admin_navbar', $data);
        $this->view('components/admin_sidebar', $data);
        $this->view('admin/pengguna/index', $data);
        $this->view('components/admin_footer', $data);
    }

    private function handlePost()
    {
        $model = $this->model('PenggunaModel');
        $action = $_POST['action'] ?? '';

        if ($action === 'create' || $action === 'update') {
            $status = isset($_POST['status']) ? 'aktif' : 'nonaktif';

            $data = [
                'nama' => $_POST['nama'] ?? '',
                'email' => $_POST['email'] ?? '',
                'posisi' => $_POST['posisi'] ?? '',
                'role' => $_POST['role'] ?? 'eksternal',
                'username' => $_POST['username'] ?? '',
                'password' => $_POST['password'] ?? '',
                'status' => $status
            ];

            if ($action === 'create') {
                if ($model->create($data)) {
                    header("Location: " . BASE_URL . "pengguna?status=success&msg=Pengguna ditambahkan");
                    exit;
                }
            } elseif ($action === 'update') {
                $id = $_POST['id'] ?? 0;
                if ($model->update($id, $data)) {
                    header("Location: " . BASE_URL . "pengguna?status=success&msg=Pengguna diperbarui");
                    exit;
                }
            }
        } elseif ($action === 'delete') {
            $id = $_POST['id'] ?? 0;
            if ($model->delete($id)) {
                header("Location: " . BASE_URL . "pengguna?status=success&msg=Pengguna dihapus");
                exit;
            }
        }
    }
}
