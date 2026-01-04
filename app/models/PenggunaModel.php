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
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
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
                    instansi=:instansi,
                    role=:role,
                    username=:username,
                    password=:password,
                    status=:status";

        $stmt = $this->conn->prepare($query);

        // Sanitize & Bind
        $stmt->bindParam(":nama", htmlspecialchars(strip_tags($data['nama'])));
        $stmt->bindParam(":email", htmlspecialchars(strip_tags($data['email'])));
        $stmt->bindParam(":instansi", htmlspecialchars(strip_tags($data['instansi'])));
        $stmt->bindParam(":role", $data['role']);
        $stmt->bindParam(":username", htmlspecialchars(strip_tags($data['username'])));

        // Hash password
        $password_hash = password_hash($data['password'], PASSWORD_BCRYPT);
        $stmt->bindParam(":password", $password_hash);

        $stmt->bindParam(":status", $data['status']);

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
                        instansi=:instansi,
                        role=:role,
                        username=:username,
                        password=:password,
                        status=:status
                    WHERE id = :id";
        } else {
            $query = "UPDATE " . $this->table_name . "
                    SET
                        nama=:nama,
                        email=:email,
                        instansi=:instansi,
                        role=:role,
                        username=:username,
                        status=:status
                    WHERE id = :id";
        }

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":nama", htmlspecialchars(strip_tags($data['nama'])));
        $stmt->bindParam(":email", htmlspecialchars(strip_tags($data['email'])));
        $stmt->bindParam(":instansi", htmlspecialchars(strip_tags($data['instansi'])));
        $stmt->bindParam(":role", $data['role']);
        $stmt->bindParam(":username", htmlspecialchars(strip_tags($data['username'])));
        $stmt->bindParam(":status", $data['status']);

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
