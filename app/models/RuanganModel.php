<?php
// app/models/RuanganModel.php

class RuanganModel
{
    private $table_name = "ruangan";
    private $db;

    public function __construct()
    {
        // Panggil Class Database dari Core (Wrapper baru)
        $this->db = new Database;
    }

    // Get All Data
    public function getAll()
    {
        $this->db->query("SELECT id, nama_ruangan, kapasitas, pic, email_pic, gambar FROM " . $this->table_name . " ORDER BY id DESC");
        return $this->db->resultSet();
    }

    // Get Single Data
    /**
     * @return array|false
     */
    public function getById($id)
    {
        $this->db->query("SELECT id, nama_ruangan, kapasitas, pic, email_pic, gambar FROM " . $this->table_name . " WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    // Create New
    public function create($data)
    {
        $query = "INSERT INTO " . $this->table_name . "
                (nama_ruangan, kapasitas, pic, email_pic, gambar)
                VALUES
                (:nama_ruangan, :kapasitas, :pic, :email_pic, :gambar)";

        $this->db->query($query);

        // Sanitize (Opsional, tapi bagus dipertahankan)
        $data['nama_ruangan'] = htmlspecialchars(strip_tags($data['nama_ruangan']));

        // Bind Data
        $this->db->bind('nama_ruangan', $data['nama_ruangan']);
        $this->db->bind('kapasitas', $data['kapasitas']);
        $this->db->bind('pic', $data['pic']);
        $this->db->bind('email_pic', $data['email_pic']);
        $this->db->bind('gambar', $data['gambar']);

        $this->db->execute();

        return $this->db->rowCount();
    }

    // Update
    public function update($id, $data)
    {
        $query = "UPDATE " . $this->table_name . "
                  SET
                    nama_ruangan = :nama_ruangan,
                    kapasitas = :kapasitas,
                    pic = :pic,
                    email_pic = :email_pic,
                    gambar = :gambar
                  WHERE id = :id";

        $this->db->query($query);

        $this->db->bind('id', $id);
        $this->db->bind('nama_ruangan', $data['nama_ruangan']);
        $this->db->bind('kapasitas', $data['kapasitas']);
        $this->db->bind('pic', $data['pic']);
        $this->db->bind('email_pic', $data['email_pic']);
        $this->db->bind('gambar', $data['gambar']);

        $this->db->execute();

        return $this->db->rowCount();
    }

    // Delete
    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $this->db->query($query);
        $this->db->bind('id', $id);

        $this->db->execute();
        return $this->db->rowCount();
    }

    // Get Assistants
    public function getAssistants()
    {
        $query = "SELECT nama, email FROM users WHERE role = 'internal' AND status = 'Asisten' ORDER BY nama ASC";
        $this->db->query($query);
        return $this->db->resultSet();
    }
}