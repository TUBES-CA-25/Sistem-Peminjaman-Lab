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
            // "Posisi" form input maps to "Status" DB column (Dosen/Asisten)
            $statusPosisi = $_POST['posisi'] ?? '';

            // Auto-assign role 'internal' if status is Dosen/Asisten
            // If we allow other logic later, we can adjust. For now, strictly internal.
            $role = 'internal';

            $data = [
                'nama' => $_POST['nama'] ?? '',
                'email' => $_POST['email'] ?? '',
                'status' => $statusPosisi,
                'role' => $role,
                'nomor_hp' => $_POST['nomor_hp'] ?? '',
                'password' => $_POST['password'] ?? ''
            ];

            try {
                if ($action === 'create') {
                    if ($model->create($data)) {
                        header("Location: " . BASE_URL . "/pengguna?status=success&msg=Pengguna ditambahkan");
                        exit;
                    }
                } elseif ($action === 'update') {
                    $id = $_POST['id'] ?? 0;
                    if ($model->update($id, $data)) {
                        header("Location: " . BASE_URL . "/pengguna?status=success&msg=Pengguna diperbarui");
                        exit;
                    }
                }
            } catch (PDOException $e) {
                if ($e->getCode() == '23000') {
                    // Duplicate entry
                    header("Location: " . BASE_URL . "/pengguna?status=error&msg=Email sudah terdaftar!");
                    exit;
                } else {
                    throw $e;
                }
            }
        } elseif ($action === 'delete') {
            $id = $_POST['id'] ?? 0;
            if ($model->delete($id)) {
                header("Location: " . BASE_URL . "/pengguna?status=success&msg=Pengguna dihapus");
                exit;
            }
        }
    }
}
