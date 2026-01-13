<?php
// app/models/PeminjamanModel.php

require_once __DIR__ . '/../config/Database.php';

class PeminjamanModel
{
    private $conn;
    private $table_name = "peminjaman";

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Get All Bookings
    public function getAll()
    {
        $query = "SELECT p.*, r.nama_ruangan as lab_nama, u.nama as user_nama, u.email as user_email
                  FROM " . $this->table_name . " p
                  LEFT JOIN ruangan r ON p.lab_id = r.id
                  LEFT JOIN pengguna u ON p.user_id = u.id
                  ORDER BY p.tanggal_peminjaman DESC, p.jam_mulai DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get Single Booking by ID
    public function getById($id)
    {
        $query = "SELECT p.*, r.nama_ruangan as lab_nama, u.nama as user_nama, u.email as user_email
                  FROM " . $this->table_name . " p
                  LEFT JOIN ruangan r ON p.lab_id = r.id
                  LEFT JOIN pengguna u ON p.user_id = u.id
                  WHERE p.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create Booking
    // Auto-approve logic handles by Controller, Model just saves 'status' passed to it.
    public function create($data)
    {
        $query = "INSERT INTO " . $this->table_name . "
                  SET user_id=:user_id, lab_id=:lab_id, tanggal_peminjaman=:tanggal,
                      jam_mulai=:jam_mulai, jam_selesai=:jam_selesai,
                      nama_peminjam=:nama_peminjam, kegiatan=:kegiatan,
                      tipe=:tipe, status=:status, catatan=:catatan";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $data['nama_peminjam'] = htmlspecialchars(strip_tags($data['nama_peminjam']));
        $data['kegiatan'] = htmlspecialchars(strip_tags($data['kegiatan']));
        $data['catatan'] = htmlspecialchars(strip_tags($data['catatan'] ?? ''));

        $stmt->bindParam(":user_id", $data['user_id']); // Can be null
        $stmt->bindParam(":lab_id", $data['lab_id']);
        $stmt->bindParam(":tanggal", $data['tanggal_peminjaman']);
        $stmt->bindParam(":jam_mulai", $data['jam_mulai']);
        $stmt->bindParam(":jam_selesai", $data['jam_selesai']);
        $stmt->bindParam(":nama_peminjam", $data['nama_peminjam']);
        $stmt->bindParam(":kegiatan", $data['kegiatan']);
        $stmt->bindParam(":tipe", $data['tipe']);
        $stmt->bindParam(":status", $data['status']);
        $stmt->bindParam(":catatan", $data['catatan']);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Update Status (Approve/Reject)
    public function updateStatus($id, $status)
    {
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete Booking
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

    // Check Conflict in Peminjaman Table
    public function checkConflict($labId, $tanggal, $start, $end, $excludeId = null)
    {
        // Conflict if: Same Lab, Same Date, Time Overlaps, Status != 'ditolak'
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name . "
                  WHERE lab_id = :lab_id 
                  AND tanggal_peminjaman = :tanggal
                  AND status != 'ditolak'
                  AND jam_mulai < :end 
                  AND jam_selesai > :start";

        if ($excludeId) {
            $query .= " AND id != :excludeId";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lab_id', $labId);
        $stmt->bindParam(':tanggal', $tanggal);
        $stmt->bindParam(':start', $start);
        $stmt->bindParam(':end', $end);

        if ($excludeId) {
            $stmt->bindParam(':excludeId', $excludeId);
        }

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] > 0;
    }
}
