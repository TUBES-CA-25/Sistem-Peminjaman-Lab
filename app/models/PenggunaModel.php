<?php

class PenggunaModel
{
    private $table_name = "users";
    private $db;

    public function __construct()
    {
        // Panggil Class Database dari Core
        $this->db = new Database;
    }

    public function getAll()
    {
        $this->db->query("SELECT id, nama, email, status, role, telepon FROM " . $this->table_name . " ORDER BY id DESC");
        return $this->db->resultSet();
    }

    // Tambahkan method ini (biasanya dibutuhkan untuk fitur Edit)
    public function getById($id)
    {
        $this->db->query("SELECT * FROM " . $this->table_name . " WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function create($data)
    {
        $query = "INSERT INTO " . $this->table_name . "
                  (nama, email, status, role, telepon, password)
                  VALUES
                  (:nama, :email, :status, :role, :telepon, :password)";

        $this->db->query($query);

        // Bind Data
        $this->db->bind('nama', htmlspecialchars(strip_tags($data['nama'])));
        $this->db->bind('email', htmlspecialchars(strip_tags($data['email'])));
        $this->db->bind('status', htmlspecialchars(strip_tags($data['status'])));
        $this->db->bind('role', $data['role']);
        $this->db->bind('telepon', htmlspecialchars(strip_tags($data['telepon'])));

        // Hash password
        $password_hash = password_hash($data['password'], PASSWORD_BCRYPT);
        $this->db->bind('password', $password_hash);

        $this->db->execute();
        
        return $this->db->rowCount();
    }

    public function update($id, $data)
    {
        // Cek apakah password diisi atau tidak
        if (!empty($data['password'])) {
            $query = "UPDATE " . $this->table_name . "
                      SET nama=:nama, email=:email, status=:status, 
                          role=:role, telepon=:telepon, password=:password
                      WHERE id = :id";
        } else {
            $query = "UPDATE " . $this->table_name . "
                      SET nama=:nama, email=:email, status=:status, 
                          role=:role, telepon=:telepon
                      WHERE id = :id";
        }

        $this->db->query($query);

        // Bind Data Umum
        $this->db->bind('id', $id);
        $this->db->bind('nama', htmlspecialchars(strip_tags($data['nama'])));
        $this->db->bind('email', htmlspecialchars(strip_tags($data['email'])));
        $this->db->bind('status', htmlspecialchars(strip_tags($data['status'])));
        $this->db->bind('role', $data['role']);
        $this->db->bind('telepon', htmlspecialchars(strip_tags($data['telepon'])));

        // Bind Password (Jika ada)
        if (!empty($data['password'])) {
            $password_hash = password_hash($data['password'], PASSWORD_BCRYPT);
            $this->db->bind('password', $password_hash);
        }

        $this->db->execute();
        
        return $this->db->rowCount();
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        
        $this->db->query($query);
        $this->db->bind('id', $id);
        $this->db->execute();

        return $this->db->rowCount();
    }
}