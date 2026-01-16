<?php
// app/models/KelasModel.php

require_once __DIR__ . '/../config/Database.php';

class KelasModel
{
    private $conn;
    private $table_name = "kelas";

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nama_kelas ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($nama_kelas)
    {
        $query = "INSERT INTO " . $this->table_name . " SET nama_kelas = :nama_kelas";
        $stmt = $this->conn->prepare($query);
        $nama_kelas = htmlspecialchars(strip_tags($nama_kelas));
        $stmt->bindParam(':nama_kelas', $nama_kelas);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update($id, $nama_kelas)
    {
        $query = "UPDATE " . $this->table_name . " SET nama_kelas = :nama_kelas WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $nama_kelas = htmlspecialchars(strip_tags($nama_kelas));

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nama_kelas', $nama_kelas);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
