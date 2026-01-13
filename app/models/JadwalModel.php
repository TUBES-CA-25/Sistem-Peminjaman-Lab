<?php
// app/models/JadwalModel.php

require_once __DIR__ . '/../config/Database.php';

class JadwalModel
{
    private $conn;
    private $table_name = "jadwal";

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Get All Schedules with Lab Name
    public function getAll()
    {
        $query = "SELECT j.*, r.nama_ruangan as lab_nama 
                  FROM " . $this->table_name . " j
                  LEFT JOIN ruangan r ON j.lab_id = r.id
                  ORDER BY FIELD(j.hari, 'senin','selasa','rabu','kamis','jumat','sabtu','minggu'), j.jam_mulai ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get Schedule by Lab and Day
    public function getByLabAndDay($labId, $hari)
    {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE lab_id = :lab_id AND hari = :hari 
                  ORDER BY jam_mulai ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lab_id', $labId);
        $stmt->bindParam(':hari', $hari);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add Schedule
    public function create($data)
    {
        $query = "INSERT INTO " . $this->table_name . "
                  SET lab_id=:lab_id, hari=:hari, jam_mulai=:jam_mulai, jam_selesai=:jam_selesai,
                      mata_kuliah=:mata_kuliah, kelas=:kelas";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $data['mata_kuliah'] = htmlspecialchars(strip_tags($data['mata_kuliah']));
        $data['kelas'] = htmlspecialchars(strip_tags($data['kelas']));

        $stmt->bindParam(":lab_id", $data['lab_id']);
        $stmt->bindParam(":hari", $data['hari']);
        $stmt->bindParam(":jam_mulai", $data['jam_mulai']);
        $stmt->bindParam(":jam_selesai", $data['jam_selesai']);
        $stmt->bindParam(":mata_kuliah", $data['mata_kuliah']);
        $stmt->bindParam(":kelas", $data['kelas']);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Update Schedule
    public function update($id, $data)
    {
        $query = "UPDATE " . $this->table_name . "
                  SET lab_id=:lab_id, hari=:hari, jam_mulai=:jam_mulai, jam_selesai=:jam_selesai,
                      mata_kuliah=:mata_kuliah, kelas=:kelas
                  WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $data['mata_kuliah'] = htmlspecialchars(strip_tags($data['mata_kuliah']));
        $data['kelas'] = htmlspecialchars(strip_tags($data['kelas']));

        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":lab_id", $data['lab_id']);
        $stmt->bindParam(":hari", $data['hari']);
        $stmt->bindParam(":jam_mulai", $data['jam_mulai']);
        $stmt->bindParam(":jam_selesai", $data['jam_selesai']);
        $stmt->bindParam(":mata_kuliah", $data['mata_kuliah']);
        $stmt->bindParam(":kelas", $data['kelas']);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete Schedule
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

    // Check Conflict
    public function checkConflict($labId, $hari, $start, $end, $excludeId = null)
    {
        // Check if overlaps with any existing schedule
        // (StartA < EndB) and (EndA > StartB)
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name . "
                  WHERE lab_id = :lab_id 
                  AND hari = :hari
                  AND jam_mulai < :end 
                  AND jam_selesai > :start";

        if ($excludeId) {
            $query .= " AND id != :excludeId";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lab_id', $labId);
        $stmt->bindParam(':hari', $hari);
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
