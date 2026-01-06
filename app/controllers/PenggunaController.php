<?php
// app/controllers/PenggunaController.php

require_once __DIR__ . '/../models/PenggunaModel.php';

class PenggunaController
{
    private $model;

    public function __construct()
    {
        $this->model = new PenggunaModel();
    }

    public function index()
    {
        return $this->model->getAll();
    }

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $action = $_POST['action'] ?? '';

            if ($action === 'create' || $action === 'update') {
                $status = isset($_POST['status']) ? 'aktif' : 'nonaktif';

                $data = [
                    'nama' => $_POST['nama'] ?? '',
                    'email' => $_POST['email'] ?? '',
                    'posisi' => $_POST['posisi'] ?? '', // Refactored from instansi
                    'role' => $_POST['role'] ?? 'eksternal',
                    'username' => $_POST['username'] ?? '',
                    'password' => $_POST['password'] ?? '', // Will be hashed in Model
                    'status' => $status
                ];

                if ($action === 'create') {
                    if ($this->model->create($data)) {
                        header("Location: dashboard.php?page=pengguna&status=success&msg=Pengguna ditambahkan");
                        exit;
                    }
                } elseif ($action === 'update') {
                    $id = $_POST['id'] ?? 0;
                    if ($this->model->update($id, $data)) {
                        header("Location: dashboard.php?page=pengguna&status=success&msg=Pengguna diperbarui");
                        exit;
                    }
                }
            } elseif ($action === 'delete') {
                $id = $_POST['id'] ?? 0;
                if ($this->model->delete($id)) {
                    header("Location: dashboard.php?page=pengguna&status=success&msg=Pengguna dihapus");
                    exit;
                }
            }
        }
    }
}
