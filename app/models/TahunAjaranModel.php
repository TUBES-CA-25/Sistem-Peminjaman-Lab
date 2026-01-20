<?php
// app/models/TahunAjaranModel.php

class TahunAjaranModel
{
    private $table_name = "tahun_ajaran";
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAll()
    {
        $this->db->query("SELECT * FROM " . $this->table_name . " ORDER BY id DESC");
        return $this->db->resultSet();
    }

    public function getActive()
    {
        $this->db->query("SELECT * FROM " . $this->table_name . " WHERE status = 'Aktif' LIMIT 1");
        return $this->db->single();
    }

    public function getById($id)
    {
        $this->db->query("SELECT * FROM " . $this->table_name . " WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function create($data)
    {
        $query = "INSERT INTO " . $this->table_name . " (nama, status) VALUES (:nama, :status)";

        $this->db->query($query);
        $this->db->bind('nama', $data['nama']);
        $this->db->bind('status', $data['status']);

        $this->db->execute();



        return $this->db->rowCount();
    }

    public function update($id, $data)
    {
        $query = "UPDATE " . $this->table_name . " SET nama = :nama, status = :status WHERE id = :id";

        $this->db->query($query);
        $this->db->bind('nama', $data['nama']);
        $this->db->bind('status', $data['status']);
        $this->db->bind('id', $id);

        $this->db->execute();

        // If this one is Active, deactivate others
        if ($data['status'] == 'Aktif') {
            $this->deactivateOthers($id);
        }

        return $this->db->rowCount();
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $this->db->query($query);
        $this->db->bind('id', $id);
        return $this->db->execute();
    }

    private function deactivateOthers($activeId)
    {
        $query = "UPDATE " . $this->table_name . " SET status = 'Tidak Aktif' WHERE id != :id";
        $this->db->query($query);
        $this->db->bind('id', $activeId);
        $this->db->execute();
    }
}
