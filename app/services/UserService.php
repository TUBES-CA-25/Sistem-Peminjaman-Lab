<?php

/**
 * Service untuk mengelola data user dan file terkait user.
 */
class UserService
{
    private $userModel;

    public function __construct()
    {
        if (!class_exists('UserModel')) {
            require_once __DIR__ . '/../models/UserModel.php';
        }
        $this->userModel = new UserModel();
    }

    /**
     * Handle Update Profile
     * Termasuk upload foto dan update data ke database.
     * 
     * @param int $userId ID User yang sedang login
     * @param array $input Data POST dari form
     * @param array $files Data FILES dari form
     * @return array ['success' => bool, 'message' => string]
     */
    public function updateProfile($userId, $input, $files)
    {
        $userLama = $this->userModel->getUserById($userId);
        if (!$userLama) {
            return ['success' => false, 'message' => 'User tidak ditemukan.'];
        }

        $foto = $userLama['foto'];

        // 1. Handle Foto Upload
        $uploadResult = $this->handlePhotoUpload($userId, $input, $files);
        if ($uploadResult['success']) {
            // Hapus foto lama jika bukan default dan ada foto baru
            if ($uploadResult['fileName'] && $foto && $foto !== $uploadResult['fileName']) {
                $this->deleteOldPhoto($foto);
            }
            $foto = $uploadResult['fileName'];
        } elseif ($uploadResult['message']) {
            // Jika ada pesan error (bukan cuma 'no file uploaded')
            return ['success' => false, 'message' => $uploadResult['message']];
        }

        // 2. Siapkan Data Update
        $data = [
            'id' => $userId,
            'nama' => $input['nama'],
            'email' => $input['email'],
            'telepon' => $input['telepon'] ?? '',
            'password' => $input['password_baru'] ?? '', // Model yang akan handle hash jika tidak kosong
            'foto' => $foto
        ];

        // 3. Update ke Database
        if ($this->userModel->updateUserProfile($data) > 0) {
            return ['success' => true, 'message' => 'Profil berhasil diperbarui.', 'data' => $data];
        } else {
            // Cek jika hanya foto yang berubah (row affected 0 karena data teks sama, tapi foto beda)
            if ($foto !== $userLama['foto']) {
                return ['success' => true, 'message' => 'Foto profil berhasil diperbarui.', 'data' => $data];
            }
            return ['success' => true, 'message' => 'Tidak ada perubahan data.', 'data' => $data]; // Tetap success tapi info
        }
    }

    private function handlePhotoUpload($userId, $input, $files)
    {
        $targetDir = __DIR__ . '/../../public/storage/uploads/profile/';
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // A. Cek Base64 Cropped Image
        if (!empty($input['cropped_image'])) {
            $data_uri = $input['cropped_image'];
            $encoded_image = explode(",", $data_uri)[1];
            $decoded_image = base64_decode($encoded_image);

            $namaFileBaru = 'profile_' . $userId . '_' . time() . '.jpg';
            if (file_put_contents($targetDir . $namaFileBaru, $decoded_image)) {
                return ['success' => true, 'fileName' => $namaFileBaru];
            }
            return ['success' => false, 'message' => 'Gagal menyimpan gambar crop.'];
        }

        // B. Cek Standard File Upload
        if (isset($files['foto']) && $files['foto']['error'] !== 4) {
            $file = $files['foto'];
            $namaFile = $file['name'];
            $ukuranFile = $file['size'];
            $tmpName = $file['tmp_name'];

            // Validasi Ekstensi
            $ekstensiValid = ['jpg', 'jpeg', 'png'];
            $ekstensiFile = explode('.', $namaFile);
            $ekstensiFile = strtolower(end($ekstensiFile));

            if (!in_array($ekstensiFile, $ekstensiValid)) {
                return ['success' => false, 'message' => 'Format file tidak valid! Gunakan JPG/JPEG/PNG'];
            }

            // Validasi Ukuran (Max 2MB)
            if ($ukuranFile > 2 * 1024 * 1024) {
                return ['success' => false, 'message' => 'Ukuran file terlalu besar (Max 2MB).'];
            }

            $namaFileBaru = 'profile_' . $userId . '_' . time() . '.' . $ekstensiFile;
            if (move_uploaded_file($tmpName, $targetDir . $namaFileBaru)) {
                return ['success' => true, 'fileName' => $namaFileBaru];
            }
            return ['success' => false, 'message' => 'Gagal upload file.'];
        }

        // Tidak ada file diupload
        return ['success' => false, 'fileName' => null, 'message' => null];
    }

    private function deleteOldPhoto($filename)
    {
        $path = __DIR__ . '/../../public/storage/uploads/profile/' . $filename;
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
