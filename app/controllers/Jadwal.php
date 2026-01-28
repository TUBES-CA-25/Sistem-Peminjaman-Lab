<?php

class Jadwal extends Controller
{
    public function index()
    {
        // Handle POST Requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost();
            return;
        }

        $jadwalModel = $this->model('JadwalModel');
        $ruanganModel = $this->model('RuanganModel');
        $kelasModel = $this->model('KelasModel');
        $matakuliahModel = $this->model('MatakuliahModel');

        // Fetch data for View
        $scheduleData = $jadwalModel->getAll();
        $labsData = $ruanganModel->getAll();
        $kelasData = $kelasModel->getAll();
        $matakuliahData = $matakuliahModel->getAll();

        $data = [
            'active_page' => 'jadwal',
            'title' => 'Jadwal Praktikum - Admin',
            'schedules' => $scheduleData,
            'labs' => $labsData,
            'kelas' => $kelasData,
            'matakuliah' => $matakuliahData
        ];

        // ... view calls ...
        $this->view('components/admin_head', $data);
        $this->view('components/admin_navbar', $data);
        $this->view('components/admin_sidebar', $data);
        $this->view('admin/jadwal/index', $data);
        $this->view('components/admin_footer', $data);
    }

    private function handlePost()
    {
        $model = $this->model('JadwalModel');
        $action = $_POST['action'] ?? '';

        if ($action === 'create' || $action === 'update') {

            $data = [
                'lab_id' => $_POST['lab'] ?? '',
                'hari' => $_POST['hari'] ?? '',
                'jam_mulai' => $_POST['jamMulai'] ?? '',
                'jam_selesai' => $_POST['jamSelesai'] ?? '',
                'matakuliah_id' => $_POST['mataKuliah'] ?? '', // This will now typically be an ID from select
                'kelas_id' => $_POST['kelas'] ?? '', // This will now typically be an ID from select
                'frekuensi' => $_POST['frekuensi'] ?? '' // Add Frequency
            ];

            // Check Conflict
            // Logic: Not implemented in details here, Model checkConflict can be used.
            // For now trusting model update/create or simple validation.
            // In real app, we should call $model->checkConflict(...) here.

            if ($action === 'create') {
                if ($model->checkConflict($data['lab_id'], $data['hari'], $data['jam_mulai'], $data['jam_selesai'])) {
                    header("Location: " . BASE_URL . "/jadwal?status=error&msg=Jadwal bentrok!");
                    exit;
                }

                if ($model->create($data)) {
                    header("Location: " . BASE_URL . "/jadwal?status=success&msg=Jadwal berhasil ditambahkan");
                    exit;
                }
            } elseif ($action === 'update') {
                $id = $_POST['id'] ?? 0;

                if ($model->checkConflict($data['lab_id'], $data['hari'], $data['jam_mulai'], $data['jam_selesai'], $id)) {
                    header("Location: " . BASE_URL . "/jadwal?status=error&msg=Jadwal bentrok!");
                    exit;
                }

                if ($model->update($id, $data)) {
                    header("Location: " . BASE_URL . "/jadwal?status=success&msg=Jadwal berhasil diperbarui");
                    exit;
                }
            }

        } elseif ($action === 'delete') {
            $id = $_POST['id'] ?? 0;
            if ($model->delete($id)) {
                header("Location: " . BASE_URL . "/jadwal?status=success&msg=Jadwal berhasil dihapus");
                exit;
            }
        }
    }
}