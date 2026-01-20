<?php

class User_model
{
    private $table = 'pengguna'; // Nama tabel di database
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // 1. Ambil data user berdasarkan ID (Untuk ditampilkan di form profil)
    public function getUserById($id)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    // 2. Update data profil (Nama, Email, Telepon)
    public function updateProfile($data)
    {
        $query = "UPDATE " . $this->table . " SET 
                    nama_lengkap = :nama, 
                    email = :email, 
                    telepon = :telepon 
                  WHERE id = :id";
        
        $this->db->query($query);
        $this->db->bind('nama', $data['nama']);
        $this->db->bind('email', $data['email']);
        $this->db->bind('telepon', $data['telepon']);
        $this->db->bind('id', $data['id']);

        $this->db->execute();
        
        // Mengembalikan jumlah baris yang berubah (agar kita tahu update berhasil/tidak)
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
        $this->db->query('SELECT * FROM pengguna WHERE email = :email');
        $this->db->bind('email', $email);
        return $this->db->single();
    }
}