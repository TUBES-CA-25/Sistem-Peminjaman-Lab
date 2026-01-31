<?php
// app/models/PeminjamanModel.php

class PeminjamanModel
{
    private $table_name = "peminjaman";
    private $db;

    public function __construct()
    {
        // Panggil Class Database dari Core
        $this->db = new Database;
    }

    // Get All Bookings
    public function getAll()
    {
        $query = "SELECT p.id, p.user_id, p.lab_id, 
                         p.tanggal_peminjaman as tanggal, 
                         p.jam_mulai, p.jam_selesai, 
                         p.nama_peminjam, p.kegiatan, p.tipe, p.status,
                         r.nama_ruangan as lab_nama, u.nama as user_nama, u.email as user_email
                  FROM " . $this->table_name . " p
                  LEFT JOIN ruangan r ON p.lab_id = r.id
                  LEFT JOIN users u ON p.user_id = u.id
                  ORDER BY p.tanggal_peminjaman DESC, p.jam_mulai DESC";

        $this->db->query($query);
        return $this->db->resultSet();
    }

    // Get Single Booking by ID
    public function getById($id)
    {
        $query = "SELECT p.id, p.user_id, p.lab_id, p.tanggal_peminjaman, p.jam_mulai, p.jam_selesai, 
                         p.nama_peminjam, p.kegiatan, p.tipe, p.status,
                         r.nama_ruangan as lab_nama, u.nama as user_nama, u.email as user_email
                  FROM " . $this->table_name . " p
                  LEFT JOIN ruangan r ON p.lab_id = r.id
                  LEFT JOIN users u ON p.user_id = u.id
                  WHERE p.id = :id";

        $this->db->query($query);
        $this->db->bind('id', $id);

        return $this->db->single();
    }

    // Create Booking
    public function create($data)
    {
        $query = "INSERT INTO " . $this->table_name . "
                  (user_id, lab_id, tanggal_peminjaman, jam_mulai, jam_selesai, 
                   nama_peminjam, kegiatan, tipe, status)
                  VALUES
                  (:user_id, :lab_id, :tanggal, :jam_mulai, :jam_selesai, 
                   :nama_peminjam, :kegiatan, :tipe, :status)";

        $this->db->query($query);

        // Sanitize (Opsional)
        $data['nama_peminjam'] = htmlspecialchars(strip_tags($data['nama_peminjam']));
        $data['kegiatan'] = htmlspecialchars(strip_tags($data['kegiatan']));

        // Bind Data
        $this->db->bind('user_id', $data['user_id']);
        $this->db->bind('lab_id', $data['lab_id']);
        $this->db->bind('tanggal', $data['tanggal_peminjaman']);
        $this->db->bind('jam_mulai', $data['jam_mulai']);
        $this->db->bind('jam_selesai', $data['jam_selesai']);
        $this->db->bind('nama_peminjam', $data['nama_peminjam']);
        $this->db->bind('kegiatan', $data['kegiatan']);
        $this->db->bind('tipe', $data['tipe']);
        $this->db->bind('status', $data['status']);

        $this->db->execute();

        return $this->db->rowCount();
    }

    // Update Status (Approve/Reject)
    public function updateStatus($id, $status)
    {
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id = :id";

        $this->db->query($query);
        $this->db->bind('status', $status);
        $this->db->bind('id', $id);

        $this->db->execute();

        return $this->db->rowCount();
    }

    // Delete Booking
    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $this->db->query($query);
        $this->db->bind('id', $id);

        $this->db->execute();

        return $this->db->rowCount();
    }

    // Get Bookings by User ID (for user history)
    public function getByUserId($userId)
    {
        $query = "SELECT p.id, p.user_id, p.lab_id, p.tanggal_peminjaman as tanggal, 
                         p.jam_mulai, p.jam_selesai, p.nama_peminjam, p.kegiatan as keterangan, 
                         p.tipe, p.status,
                         r.nama_ruangan
                  FROM " . $this->table_name . " p
                  LEFT JOIN ruangan r ON p.lab_id = r.id
                  WHERE p.user_id = :user_id
                  ORDER BY p.tanggal_peminjaman DESC, p.jam_mulai DESC";

        $this->db->query($query);
        $this->db->bind('user_id', $userId);

        return $this->db->resultSet();
    }

    // Update Booking
    public function update($id, $data)
    {
        $query = "UPDATE " . $this->table_name . " SET 
                  lab_id = :lab_id,
                  tanggal_peminjaman = :tanggal,
                  jam_mulai = :jam_mulai,
                  jam_selesai = :jam_selesai,
                  nama_peminjam = :nama_peminjam,
                  kegiatan = :kegiatan
                  WHERE id = :id";

        $this->db->query($query);
        $this->db->bind('lab_id', $data['lab_id']);
        $this->db->bind('tanggal', $data['tanggal']);
        $this->db->bind('jam_mulai', $data['jam_mulai']);
        $this->db->bind('jam_selesai', $data['jam_selesai']);
        $this->db->bind('nama_peminjam', htmlspecialchars(strip_tags($data['nama_peminjam'])));
        $this->db->bind('kegiatan', htmlspecialchars(strip_tags($data['kegiatan'])));
        $this->db->bind('id', $id);

        $this->db->execute();

        return $this->db->rowCount();
    }


    // Check Conflict in Peminjaman Table AND Jadwal Tetap
    public function checkConflict($labId, $tanggal, $start, $end, $excludeId = null)
    {
        // 1. Check Table Peminjaman (Existing Bookings)
        // Ignored status: 'ditolak', 'tergeser'
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name . "
                  WHERE lab_id = :lab_id 
                  AND tanggal_peminjaman = :tanggal
                  AND status NOT IN ('ditolak', 'tergeser')
                  AND jam_mulai < :end 
                  AND jam_selesai > :start";

        if ($excludeId) {
            $query .= " AND id != :excludeId";
        }

        $this->db->query($query);
        $this->db->bind('lab_id', $labId);
        $this->db->bind('tanggal', $tanggal);
        $this->db->bind('start', $start);
        $this->db->bind('end', $end);

        if ($excludeId) {
            $this->db->bind('excludeId', $excludeId);
        }

        $result = $this->db->single();
        if ($result['count'] > 0) {
            return true; // Conflict with existing booking
        }

        // 2. Check Table Jadwal (Fixed Schedule / Praktikum)
        // Convert Date to Day Name (Indonesian)
        $dayEnglish = date('l', strtotime($tanggal));
        $daysMap = [
            'Sunday' => 'minggu',
            'Monday' => 'senin',
            'Tuesday' => 'selasa',
            'Wednesday' => 'rabu',
            'Thursday' => 'kamis',
            'Friday' => 'jumat',
            'Saturday' => 'sabtu'
        ];
        $hari = $daysMap[$dayEnglish] ?? '';

        $queryJadwal = "SELECT COUNT(*) as count FROM jadwal
                        WHERE lab_id = :lab_id
                        AND hari = :hari
                        AND jam_mulai < :end
                        AND jam_selesai > :start";

        $this->db->query($queryJadwal);
        $this->db->bind('lab_id', $labId);
        $this->db->bind('hari', $hari);
        $this->db->bind('start', $start);
        $this->db->bind('end', $end);

        $resultJadwal = $this->db->single();
        if ($resultJadwal['count'] > 0) {
            return true; // Conflict with Fixed Schedule
        }

        return false;
    }

    // Shift Conflicting Bookings (Override)
    // Ubah status booking yang bentrok menjadi 'tergeser'
    public function shiftConflictingBookings($labId, $tanggal, $start, $end)
    {
        $query = "UPDATE " . $this->table_name . " 
                  SET status = 'tergeser'
                  WHERE lab_id = :lab_id 
                  AND tanggal_peminjaman = :tanggal
                  AND status NOT IN ('ditolak', 'tergeser')
                  AND jam_mulai < :end 
                  AND jam_selesai > :start";

        $this->db->query($query);
        $this->db->bind('lab_id', $labId);
        $this->db->bind('tanggal', $tanggal);
        $this->db->bind('start', $start);
        $this->db->bind('end', $end);

        $this->db->execute();
        return $this->db->rowCount();
    }
}