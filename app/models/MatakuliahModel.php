<?php
// app/models/MatakuliahModel.php

require_once __DIR__ . '/../config/Database.php';

class MatakuliahModel
{
    private $conn;
    private $table_name = "matakuliah";

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nama_matakuliah ASC";
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

    public function create($data)
    {
        $query = "INSERT INTO " . $this->table_name . " SET nama_matakuliah = :nama, kode_matakuliah = :kode";
        $stmt = $this->conn->prepare($query);

        $data['nama'] = htmlspecialchars(strip_tags($data['nama']));
        $data['kode'] = htmlspecialchars(strip_tags($data['kode']));

        $stmt->bindParam(':nama', $data['nama']);
        $stmt->bindParam(':kode', $data['kode']);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update($id, $data)
    {
        $query = "UPDATE " . $this->table_name . " SET nama_matakuliah = :nama, kode_matakuliah = :kode WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $data['nama'] = htmlspecialchars(strip_tags($data['nama']));
        $data['kode'] = htmlspecialchars(strip_tags($data['kode']));

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nama', $data['nama']);
        $stmt->bindParam(':kode', $data['kode']);

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
