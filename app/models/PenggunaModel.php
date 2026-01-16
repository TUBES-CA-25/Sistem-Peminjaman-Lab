<?php
// app/models/PenggunaModel.php

require_once __DIR__ . '/../config/Database.php';

class PenggunaModel
{
    private $conn;
    private $table_name = "pengguna";

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll()
    {
        $query = "SELECT id, nama, email, status, role, nomor_hp FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    nama=:nama,
                    email=:email,
                    status=:status,
                    role=:role,
                    nomor_hp=:nomor_hp,
                    password=:password";

        $stmt = $this->conn->prepare($query);

        // Sanitize & Bind
        $stmt->bindValue(":nama", htmlspecialchars(strip_tags($data['nama'])));
        $stmt->bindValue(":email", htmlspecialchars(strip_tags($data['email'])));
        $stmt->bindValue(":status", htmlspecialchars(strip_tags($data['status'])));
        $stmt->bindParam(":role", $data['role']);
        $stmt->bindValue(":nomor_hp", htmlspecialchars(strip_tags($data['nomor_hp'])));

        // Hash password
        $password_hash = password_hash($data['password'], PASSWORD_BCRYPT);
        $stmt->bindParam(":password", $password_hash);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update($id, $data)
    {
        // If password is provided, update it. If empty, skip password update.
        if (!empty($data['password'])) {
            $query = "UPDATE " . $this->table_name . "
                    SET
                        nama=:nama,
                        email=:email,
                        status=:status,
                        role=:role,
                        nomor_hp=:nomor_hp,
                        password=:password
                    WHERE id = :id";
        } else {
            $query = "UPDATE " . $this->table_name . "
                    SET
                        nama=:nama,
                        email=:email,
                        status=:status,
                        role=:role,
                        nomor_hp=:nomor_hp
                    WHERE id = :id";
        }

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $id);
        $stmt->bindValue(":nama", htmlspecialchars(strip_tags($data['nama'])));
        $stmt->bindValue(":email", htmlspecialchars(strip_tags($data['email'])));
        $stmt->bindValue(":status", htmlspecialchars(strip_tags($data['status'])));
        $stmt->bindParam(":role", $data['role']);
        $stmt->bindValue(":nomor_hp", htmlspecialchars(strip_tags($data['nomor_hp'])));

        if (!empty($data['password'])) {
            $password_hash = password_hash($data['password'], PASSWORD_BCRYPT);
            $stmt->bindParam(":password", $password_hash);
        }

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

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
