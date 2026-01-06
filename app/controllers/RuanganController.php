<?php
// app/controllers/RuanganController.php

require_once __DIR__ . '/../models/RuanganModel.php';

class RuanganController
{
    private $model;

    public function __construct()
    {
        $this->model = new RuanganModel();
    }

    public function index()
    {
        return [
            'ruangan' => $this->model->getAll(),
            'asisten' => $this->model->getAssistants()
        ];
    }

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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
                    'gambar' => $_POST['gambar_base64'] ?? '', // Expecting Base64 string from FE
                    'status' => $status
                ];

                if ($action === 'create') {
                    if ($this->model->create($data)) {
                        header("Location: dashboard.php?page=ruangan&status=success&msg=Ditambahkan");
                        exit;
                    }
                } elseif ($action === 'update') {
                    $id = $_POST['id'] ?? 0;
                    if ($this->model->update($id, $data)) {
                        header("Location: dashboard.php?page=ruangan&status=success&msg=Diperbarui");
                        exit;
                    }
                }
            } elseif ($action === 'delete') {
                $id = $_POST['id'] ?? 0;
                if ($this->model->delete($id)) {
                    header("Location: dashboard.php?page=ruangan&status=success&msg=Dihapus");
                    exit;
                }
            }
        }
    }
}
