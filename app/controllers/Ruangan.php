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

            // Handle File Upload
            $gambarPath = null;
            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
                $gambarPath = $this->handleFileUpload($_FILES['gambar']);
                if (!$gambarPath) {
                    header("Location: " . BASE_URL . "/ruangan?status=error&msg=Gagal Upload Gambar");
                    exit;
                }
            } else {
                // If updating and no new file, set to existing if passed, or null
                $gambarPath = $_POST['existing_gambar'] ?? null;
            }

            $data = [
                'nama_ruangan' => $_POST['nama_ruangan'] ?? '',
                'kapasitas' => $_POST['kapasitas'] ?? 0,
                'lokasi' => $_POST['lokasi'] ?? '',
                'pic' => $_POST['pic'] ?? '',
                'email_pic' => $_POST['email_pic'] ?? '',
                'fasilitas' => $_POST['fasilitas'] ?? '',
                'deskripsi' => $_POST['deskripsi'] ?? '',
                'gambar' => $gambarPath,
                'status' => $status
            ];

            if ($action === 'create') {
                if ($model->create($data)) {
                    header("Location: " . BASE_URL . "/ruangan?status=success&msg=Ditambahkan");
                    exit;
                }
            } elseif ($action === 'update') {
                $id = $_POST['id'] ?? 0;
                // If no new image uploaded, we might want to keep the old one. 
                // The model might handle 'update' by overwriting everything. 
                // Checks on model logic would be good, but assuming we pass what we have.
                // If $gambarPath is null/empty and it's update, logic depends on model.
                // Usually we pass the existing one in a hidden field 'existing_gambar'.

                if ($model->update($id, $data)) {
                    header("Location: " . BASE_URL . "/ruangan?status=success&msg=Diperbarui");
                    exit;
                }
            }
        } elseif ($action === 'delete') {
            $id = $_POST['id'] ?? 0;
            if ($model->delete($id)) {
                header("Location: " . BASE_URL . "/ruangan?status=success&msg=Dihapus");
                exit;
            }
        }
    }

    private function handleFileUpload($file)
    {
        $targetDir = "public/storage/images/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = basename($file["name"]);
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $newFileName = uniqid() . '.' . $fileType;
        $targetFilePath = $targetDir . $newFileName;

        // Allow certain file formats
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'webp');
        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
                // Return path relative to index.php or handling logic? 
                // Usually storing 'storage/images/filename.jpg' or just filename.
                // App seems to expect full public path or mapped path. 
                // Let's store 'public/storage/images/filename.jpg' to be safe with current setup,
                // or 'storage/images/filename.jpg'. 
                return "public/storage/images/" . $newFileName;
            }
        }
        return false;
    }
}
