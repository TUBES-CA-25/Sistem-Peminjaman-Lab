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
        $this->db->query("SELECT kelas.id, kelas.nama_kelas, kelas.jurusan_id, kelas.angkatan, jurusan.singkatan as nama_jurusan 
                          FROM " . $this->table_name . " 
                          LEFT JOIN jurusan ON kelas.jurusan_id = jurusan.id 
                          ORDER BY kelas.nama_kelas ASC");
        return $this->db->resultSet();
    }

    public function getById($id)
    {
        $this->db->query("SELECT id, nama_kelas, jurusan_id, angkatan FROM " . $this->table_name . " WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function create($data)
    {
        $query = "INSERT INTO " . $this->table_name . " (nama_kelas, jurusan_id, angkatan) VALUES (:nama_kelas, :jurusan_id, :angkatan)";

        $this->db->query($query);

        // Sanitize & Bind
        $nama_kelas = htmlspecialchars(strip_tags($data['nama_kelas']));
        $jurusan_id = htmlspecialchars(strip_tags($data['jurusan_id']));
        $angkatan = htmlspecialchars(strip_tags($data['angkatan']));

        $this->db->bind('nama_kelas', $nama_kelas);
        $this->db->bind('jurusan_id', $jurusan_id);
        $this->db->bind('angkatan', $angkatan);

        $this->db->execute();

        return $this->db->rowCount();
    }

    public function update($id, $data)
    {
        $query = "UPDATE " . $this->table_name . " SET nama_kelas = :nama_kelas, jurusan_id = :jurusan_id, angkatan = :angkatan WHERE id = :id";

        $this->db->query($query);

        // Sanitize & Bind
        $nama_kelas = htmlspecialchars(strip_tags($data['nama_kelas']));
        $jurusan_id = htmlspecialchars(strip_tags($data['jurusan_id']));
        $angkatan = htmlspecialchars(strip_tags($data['angkatan']));

        $this->db->bind('nama_kelas', $nama_kelas);
        $this->db->bind('jurusan_id', $jurusan_id);
        $this->db->bind('angkatan', $angkatan);
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