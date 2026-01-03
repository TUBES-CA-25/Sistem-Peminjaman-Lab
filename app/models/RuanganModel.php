<?php
// app/models/RuanganModel.php

require_once __DIR__ . '/../config/Database.php';

class RuanganModel
{
    private $conn;
    private $table_name = "ruangan";

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Get All Data
    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get Single Data
    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create Noe
    public function create($data)
    {
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    nama_ruangan=:nama_ruangan,
                    kapasitas=:kapasitas,
                    lokasi=:lokasi,
                    pic=:pic,
                    email_pic=:email_pic,
                    fasilitas=:fasilitas,
                    deskripsi=:deskripsi,
                    gambar=:gambar,
                    status=:status";

        $stmt = $this->conn->prepare($query);

        // sanitize
        $data['nama_ruangan'] = htmlspecialchars(strip_tags($data['nama_ruangan']));

        $stmt->bindParam(":nama_ruangan", $data['nama_ruangan']);
        $stmt->bindParam(":kapasitas", $data['kapasitas']);
        $stmt->bindParam(":lokasi", $data['lokasi']);
        $stmt->bindParam(":pic", $data['pic']);
        $stmt->bindParam(":email_pic", $data['email_pic']);
        $stmt->bindParam(":fasilitas", $data['fasilitas']);
        $stmt->bindParam(":deskripsi", $data['deskripsi']);
        $stmt->bindParam(":gambar", $data['gambar']);
        $stmt->bindParam(":status", $data['status']);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Update
    public function update($id, $data)
    {
        $query = "UPDATE " . $this->table_name . "
                SET
                    nama_ruangan=:nama_ruangan,
                    kapasitas=:kapasitas,
                    lokasi=:lokasi,
                    pic=:pic,
                    email_pic=:email_pic,
                    fasilitas=:fasilitas,
                    deskripsi=:deskripsi,
                    gambar=:gambar,
                    status=:status
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":nama_ruangan", $data['nama_ruangan']);
        $stmt->bindParam(":kapasitas", $data['kapasitas']);
        $stmt->bindParam(":lokasi", $data['lokasi']);
        $stmt->bindParam(":pic", $data['pic']);
        $stmt->bindParam(":email_pic", $data['email_pic']);
        $stmt->bindParam(":fasilitas", $data['fasilitas']);
        $stmt->bindParam(":deskripsi", $data['deskripsi']);
        $stmt->bindParam(":gambar", $data['gambar']);
        $stmt->bindParam(":status", $data['status']);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete
    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
