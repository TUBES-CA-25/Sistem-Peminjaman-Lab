<?php

class User_model
{
    private $table = 'users'; // Nama tabel di database
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // 1. Ambil data user berdasarkan ID (Untuk ditampilkan di form profil)
    // Ambil 1 user berdasarkan ID
    public function getUserById($id)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    // Update Profile
    public function updateUserProfile($data)
    {
        // Cek apakah user ingin ganti password?
        if (!empty($data['password'])) {
            // Jika ada password baru
            $query = "UPDATE " . $this->table . " SET 
                        nama = :nama, 
                        email = :email, 
                        telepon = :telepon, 
                        password = :password 
                      WHERE id = :id";
        } else {
            // Jika tidak ganti password
            $query = "UPDATE " . $this->table . " SET 
                        nama = :nama, 
                        email = :email, 
                        telepon = :telepon 
                      WHERE id = :id";
        }

        $this->db->query($query);
        $this->db->bind('nama', $data['nama']);
        $this->db->bind('email', $data['email']);
        $this->db->bind('telepon', $data['telepon']);
        $this->db->bind('id', $data['id']);

        if (!empty($data['password'])) {
            $this->db->bind('password', password_hash($data['password'], PASSWORD_DEFAULT));
        }

        $this->db->execute();
        return $this->db->rowCount();
    }

    // 3. Update Password (Opsional)
    public function updatePassword($id, $newPassword)
    {
        $query = "UPDATE " . $this->table . " SET password = :password WHERE id = :id";
        
        $this->db->query($query);
        // Password wajib di-hash demi keamanan
        $this->db->bind('password', password_hash($newPassword, PASSWORD_DEFAULT)); 
        $this->db->bind('id', $id);
        
        $this->db->execute();
    }

    // LOGIN: Cari user berdasarkan email
    public function getUserByEmail($email)
    {
        // Pastikan nama tabel di database Anda benar ('users' atau 'user')
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE email = :email');
        $this->db->bind('email', $email);
        return $this->db->single();
    }

    // REGISTER: Tambah user baru
    public function tambahUser($data)
    {
        $query = "INSERT INTO " . $this->table . " 
                    (nama, email, password, role, telepon)
                  VALUES
                    (:nama, :email, :password, :role, :telepon)";
        
        $this->db->query($query);
        $this->db->bind('nama', $data['nama']);
        $this->db->bind('email', $data['email']);
        // Password sudah di-hash di Controller sebelum dikirim kesini
        $this->db->bind('password', $data['password']);
        $this->db->bind('role', 'external'); // Default role untuk pendaftar umum
        $this->db->bind('telepon', $data['telepon']); // Opsional, jika ada kolom telepon

        $this->db->execute();

        return $this->db->rowCount();
    }
}