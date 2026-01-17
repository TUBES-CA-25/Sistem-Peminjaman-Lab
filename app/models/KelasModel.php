<?php
// app/models/KelasModel.php

class KelasModel
{
    private $table_name = "kelas";
    private $db;

    public function __construct()
    {
        // Panggil Class Database dari Core
        $this->db = new Database;
    }

    public function getAll()
    {
        $this->db->query("SELECT * FROM " . $this->table_name . " ORDER BY nama_kelas ASC");
        return $this->db->resultSet();
    }

    public function getById($id)
    {
        $this->db->query("SELECT * FROM " . $this->table_name . " WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function create($nama_kelas)
    {
        $query = "INSERT INTO " . $this->table_name . " (nama_kelas) VALUES (:nama_kelas)";
        
        $this->db->query($query);

        // Sanitize & Bind
        $nama_kelas = htmlspecialchars(strip_tags($nama_kelas));
        $this->db->bind('nama_kelas', $nama_kelas);

        $this->db->execute();

        return $this->db->rowCount();
    }

    public function update($id, $nama_kelas)
    {
        $query = "UPDATE " . $this->table_name . " SET nama_kelas = :nama_kelas WHERE id = :id";
        
        $this->db->query($query);

        // Sanitize & Bind
        $nama_kelas = htmlspecialchars(strip_tags($nama_kelas));
        $this->db->bind('nama_kelas', $nama_kelas);
        $this->db->bind('id', $id);

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