<?php
// app/models/JurusanModel.php

class JurusanModel
{
    private $table_name = "jurusan";
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAll()
    {
        $this->db->query("SELECT * FROM " . $this->table_name . " ORDER BY nama_jurusan ASC");
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
        $query = "INSERT INTO " . $this->table_name . " (nama_jurusan, singkatan) VALUES (:nama_jurusan, :singkatan)";

        $this->db->query($query);
        $this->db->bind('nama_jurusan', $data['nama_jurusan']);
        $this->db->bind('singkatan', $data['singkatan']);

        $this->db->execute();

        return $this->db->rowCount();
    }

    public function update($id, $data)
    {
        $query = "UPDATE " . $this->table_name . " SET nama_jurusan = :nama_jurusan, singkatan = :singkatan WHERE id = :id";

        $this->db->query($query);
        $this->db->bind('nama_jurusan', $data['nama_jurusan']);
        $this->db->bind('singkatan', $data['singkatan']);
        $this->db->bind('id', $id);

        $this->db->execute();

        return $this->db->rowCount();
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $this->db->query($query);
        $this->db->bind('id', $id);
        return $this->db->execute();
    }
}
