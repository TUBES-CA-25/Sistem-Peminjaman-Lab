<?php

class Seeder extends Controller
{
    public function index()
    {
        echo "<h1>Seeder Access</h1>";
        echo "<p>Use /seeder/admin to seed admin account.</p>";
    }

    public function admin()
    {
        $userModel = $this->model('UserModel');

        // Data Admin Baru
        $data = [
            'nama' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => 'admin123', // Password default
            'role' => 'admin',
            'status' => 'aktif',
            'telepon' => '081234567890'
        ];

        // Cek apakah email sudah ada
        if ($userModel->getUserByEmail($data['email'])) {
            echo "<h1>Gagal!</h1>";
            echo "<p>Admin dengan email <strong>" . $data['email'] . "</strong> sudah ada.</p>";
        } else {
            if ($userModel->create($data) > 0) {
                echo "<h1>Berhasil!</h1>";
                echo "<p>Akun Admin berhasil dibuat.</p>";
                echo "<ul>";
                echo "<li>Email: <strong>" . $data['email'] . "</strong></li>";
                echo "<li>Password: <strong>" . $data['password'] . "</strong></li>";
                echo "</ul>";
                echo "<p><a href='" . BASE_URL . "/auth'>Login Sekarang</a></p>";
            } else {
                echo "<h1>Error!</h1>";
                echo "<p>Gagal membuat akun admin.</p>";
            }
        }
    }
}
