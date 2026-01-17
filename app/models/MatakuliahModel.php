<?php
// app/models/MatakuliahModel.php

class MatakuliahModel
{
    private $table_name = "matakuliah";
    private $db;

    public function __construct()
    {
        // Panggil Class Database dari Core
        $this->db = new Database;
    }

    public function getAll()
    {
        $this->db->query("SELECT * FROM " . $this->table_name . " ORDER BY nama_matakuliah ASC");
        return $this->db->resultSet();
    }

    public function getById($id)
    {
        $this->db->query("SELECT * FROM " . $this->table_name . " WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function create($data)
    {
        $query = "INSERT INTO " . $this->table_name . " 
                  (nama_matakuliah, kode_matakuliah) 
                  VALUES (:nama, :kode)";
        
        $this->db->query($query);

        // Sanitize & Bind
        $nama = htmlspecialchars(strip_tags($data['nama']));
        $kode = htmlspecialchars(strip_tags($data['kode']));

        $this->db->bind('nama', $nama);
        $this->db->bind('kode', $kode);

        $this->db->execute();

        return $this->db->rowCount();
    }

    public function update($id, $data)
    {
        $query = "UPDATE " . $this->table_name . " 
                  SET nama_matakuliah = :nama, kode_matakuliah = :kode 
                  WHERE id = :id";
        
        $this->db->query($query);

        // Sanitize & Bind
        $nama = htmlspecialchars(strip_tags($data['nama']));
        $kode = htmlspecialchars(strip_tags($data['kode']));

        $this->db->bind('id', $id);
        $this->db->bind('nama', $nama);
        $this->db->bind('kode', $kode);

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