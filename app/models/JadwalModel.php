<?php
// app/models/JadwalModel.php

class JadwalModel
{
    private $table_name = "jadwal";
    private $db;

    public function __construct()
    {
        // Panggil Class Database dari Core
        $this->db = new Database;
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
        
        $this->db->query($query);
        return $this->db->resultSet();
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
        
        $this->db->query($query);
        $this->db->bind('lab_id', $labId);
        $this->db->bind('hari', $hari);
        
        return $this->db->resultSet();
    }
    
    // Add Method getById (Untuk fitur Edit, sering terlupakan)
    public function getById($id)
    {
        $this->db->query("SELECT * FROM " . $this->table_name . " WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    // Add Schedule
    public function create($data)
    {
        $query = "INSERT INTO " . $this->table_name . "
                  (lab_id, hari, jam_mulai, jam_selesai, matakuliah_id, kelas_id)
                  VALUES
                  (:lab_id, :hari, :jam_mulai, :jam_selesai, :matakuliah_id, :kelas_id)";

        $this->db->query($query);

        $this->db->bind('lab_id', $data['lab_id']);
        $this->db->bind('hari', $data['hari']);
        $this->db->bind('jam_mulai', $data['jam_mulai']);
        $this->db->bind('jam_selesai', $data['jam_selesai']);
        $this->db->bind('matakuliah_id', $data['matakuliah_id']);
        $this->db->bind('kelas_id', $data['kelas_id']);

        $this->db->execute();

        return $this->db->rowCount();
    }

    // Update Schedule
    public function update($id, $data)
    {
        $query = "UPDATE " . $this->table_name . "
                  SET lab_id=:lab_id, hari=:hari, jam_mulai=:jam_mulai, jam_selesai=:jam_selesai,
                      matakuliah_id=:matakuliah_id, kelas_id=:kelas_id
                  WHERE id=:id";

        $this->db->query($query);

        $this->db->bind('id', $id);
        $this->db->bind('lab_id', $data['lab_id']);
        $this->db->bind('hari', $data['hari']);
        $this->db->bind('jam_mulai', $data['jam_mulai']);
        $this->db->bind('jam_selesai', $data['jam_selesai']);
        $this->db->bind('matakuliah_id', $data['matakuliah_id']);
        $this->db->bind('kelas_id', $data['kelas_id']);

        $this->db->execute();

        return $this->db->rowCount();
    }

    // Delete Schedule
    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        
        $this->db->query($query);
        $this->db->bind('id', $id);

        $this->db->execute();

        return $this->db->rowCount();
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

        $this->db->query($query);

        $this->db->bind('lab_id', $labId);
        $this->db->bind('hari', $hari);
        $this->db->bind('start', $start);
        $this->db->bind('end', $end);

        if ($excludeId) {
            $this->db->bind('excludeId', $excludeId);
        }

        $result = $this->db->single();
        return $result['count'] > 0;
    }
}