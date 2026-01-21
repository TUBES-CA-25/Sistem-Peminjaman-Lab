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
        $this->db->query("SELECT matakuliah.*, jurusan.singkatan as nama_jurusan 
                          FROM " . $this->table_name . " 
                          LEFT JOIN jurusan ON matakuliah.jurusan_id = jurusan.id 
                          ORDER BY matakuliah.nama_matakuliah ASC");
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
                  (nama_matakuliah, kode_matakuliah, singkatan, semester, sks, jurusan_id) 
                  VALUES (:nama, :kode, :singkatan, :semester, :sks, :jurusan_id)";

        $this->db->query($query);

        $this->db->bind('nama', $data['nama']);
        $this->db->bind('kode', $data['kode']);
        $this->db->bind('singkatan', $data['singkatan']);
        $this->db->bind('semester', $data['semester']);
        $this->db->bind('sks', $data['sks']);
        $this->db->bind('jurusan_id', $data['jurusan_id']);

        $this->db->execute();

        return $this->db->rowCount();
    }

    public function update($id, $data)
    {
        $query = "UPDATE " . $this->table_name . " 
                  SET nama_matakuliah = :nama, kode_matakuliah = :kode,
                      singkatan = :singkatan, semester = :semester,
                      sks = :sks, jurusan_id = :jurusan_id
                  WHERE id = :id";

        $this->db->query($query);

        $this->db->bind('id', $id);
        $this->db->bind('nama', $data['nama']);
        $this->db->bind('kode', $data['kode']);
        $this->db->bind('singkatan', $data['singkatan']);
        $this->db->bind('semester', $data['semester']);
        $this->db->bind('sks', $data['sks']);
        $this->db->bind('jurusan_id', $data['jurusan_id']);

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