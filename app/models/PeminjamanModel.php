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
        $query = "SELECT p.id, p.user_id, p.lab_id, p.tanggal_peminjaman, p.jam_mulai, p.jam_selesai, 
                         p.nama_peminjam, p.kegiatan, p.tipe, p.status, p.catatan,
                         r.nama_ruangan as lab_nama, u.nama as user_nama, u.email as user_email
                  FROM " . $this->table_name . " p
                  LEFT JOIN ruangan r ON p.lab_id = r.id
                  LEFT JOIN pengguna u ON p.user_id = u.id
                  ORDER BY p.tanggal_peminjaman DESC, p.jam_mulai DESC";

        $this->db->query($query);
        return $this->db->resultSet();
    }

    // Get Single Booking by ID
    public function getById($id)
    {
        $query = "SELECT p.id, p.user_id, p.lab_id, p.tanggal_peminjaman, p.jam_mulai, p.jam_selesai, 
                         p.nama_peminjam, p.kegiatan, p.tipe, p.status, p.catatan,
                         r.nama_ruangan as lab_nama, u.nama as user_nama, u.email as user_email
                  FROM " . $this->table_name . " p
                  LEFT JOIN ruangan r ON p.lab_id = r.id
                  LEFT JOIN pengguna u ON p.user_id = u.id
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
                   nama_peminjam, kegiatan, tipe, status, catatan)
                  VALUES
                  (:user_id, :lab_id, :tanggal, :jam_mulai, :jam_selesai, 
                   :nama_peminjam, :kegiatan, :tipe, :status, :catatan)";

        $this->db->query($query);

        // Sanitize (Opsional)
        $data['nama_peminjam'] = htmlspecialchars(strip_tags($data['nama_peminjam']));
        $data['kegiatan'] = htmlspecialchars(strip_tags($data['kegiatan']));
        $data['catatan'] = htmlspecialchars(strip_tags($data['catatan'] ?? ''));

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
        $this->db->bind('catatan', $data['catatan']);

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

    // Check Conflict in Peminjaman Table
    public function checkConflict($labId, $tanggal, $start, $end, $excludeId = null)
    {
        // Conflict if: Same Lab, Same Date, Time Overlaps, Status includes 'disetujui' and 'menunggu' (active bookings)
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
        return $result['count'] > 0;
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