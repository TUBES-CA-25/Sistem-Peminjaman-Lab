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

    // Get All Schedules with Lab, Class, and Subject Names
    public function getAll()
    {
        $query = "SELECT j.id, j.lab_id, j.hari, j.jam_mulai, j.jam_selesai, 
                         j.matakuliah_id, j.kelas_id,
                         r.nama_ruangan as lab_nama,
                         m.nama_matakuliah, m.kode_matakuliah,
                         k.nama_kelas
                  FROM " . $this->table_name . " j
                  LEFT JOIN ruangan r ON j.lab_id = r.id
                  LEFT JOIN matakuliah m ON j.matakuliah_id = m.id
                  LEFT JOIN kelas k ON j.kelas_id = k.id
                  ORDER BY FIELD(j.hari, 'senin','selasa','rabu','kamis','jumat','sabtu','minggu'), j.jam_mulai ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get Schedule by Lab and Day
    public function getByLabAndDay($labId, $hari)
    {
        $query = "SELECT j.id, j.lab_id, j.hari, j.jam_mulai, j.jam_selesai, 
                         j.matakuliah_id, j.kelas_id,
                         m.nama_matakuliah, k.nama_kelas
                  FROM " . $this->table_name . " j
                  LEFT JOIN matakuliah m ON j.matakuliah_id = m.id
                  LEFT JOIN kelas k ON j.kelas_id = k.id
                  WHERE j.lab_id = :lab_id AND j.hari = :hari 
                  ORDER BY j.jam_mulai ASC";
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
                      matakuliah_id=:matakuliah_id, kelas_id=:kelas_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":lab_id", $data['lab_id']);
        $stmt->bindParam(":hari", $data['hari']);
        $stmt->bindParam(":jam_mulai", $data['jam_mulai']);
        $stmt->bindParam(":jam_selesai", $data['jam_selesai']);
        $stmt->bindParam(":matakuliah_id", $data['matakuliah_id']);
        $stmt->bindParam(":kelas_id", $data['kelas_id']);

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
                      matakuliah_id=:matakuliah_id, kelas_id=:kelas_id
                  WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":lab_id", $data['lab_id']);
        $stmt->bindParam(":hari", $data['hari']);
        $stmt->bindParam(":jam_mulai", $data['jam_mulai']);
        $stmt->bindParam(":jam_selesai", $data['jam_selesai']);
        $stmt->bindParam(":matakuliah_id", $data['matakuliah_id']);
        $stmt->bindParam(":kelas_id", $data['kelas_id']);

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
