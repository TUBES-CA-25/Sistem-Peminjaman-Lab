<?php

class Pengajuan_model {
    private $table = 'pengajuan_external';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // 1. GET ALL (Riwayat)
    public function getRiwayat() {
        $this->db->query('SELECT * FROM ' . $this->table . ' ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    // 2. GET BY ID (Detail & Edit)
    public function getById($id) {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    // 3. TAMBAH (Create)
    public function tambahPengajuan($data) {
        $query = "INSERT INTO " . $this->table . " 
                    (nama_lengkap, email, telepon, jumlah_peserta, nama_kegiatan, tgl_mulai, tgl_selesai, file_proposal, status)
                  VALUES
                    (:nama, :email, :telepon, :jumlah, :kegiatan, :mulai, :selesai, :proposal, 'Menunggu Konfirmasi')";

        $this->db->query($query);
        $this->db->bind('nama', $data['nama_lengkap']);
        $this->db->bind('email', $data['email']);
        $this->db->bind('telepon', $data['telepon']);
        $this->db->bind('jumlah', $data['jumlah_peserta']);
        $this->db->bind('kegiatan', $data['nama_kegiatan']);
        $this->db->bind('mulai', $data['tgl_mulai']);
        $this->db->bind('selesai', $data['tgl_selesai']);
        $this->db->bind('proposal', $data['file_proposal']);

        $this->db->execute();
        return $this->db->rowCount();
    }

    // 4. UPDATE DATA (Edit User)
    public function updatePengajuan($data) {
        $query = "UPDATE " . $this->table . " 
                  SET nama_kegiatan = :kegiatan,
                      jumlah_peserta = :jumlah,
                      tgl_mulai = :mulai, 
                      tgl_selesai = :selesai 
                  WHERE id = :id";

        $this->db->query($query);
        $this->db->bind('kegiatan', $data['nama_kegiatan']);
        $this->db->bind('jumlah', $data['jumlah_peserta']);
        $this->db->bind('mulai', $data['tgl_mulai']);
        $this->db->bind('selesai', $data['tgl_selesai']);
        $this->db->bind('id', $data['id']);

        $this->db->execute();
        return $this->db->rowCount();
    }

    // 5. HAPUS
    public function hapusPengajuan($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $this->db->query($query);
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    // 6. UPDATE STATUS (Khusus Admin)
    public function updateStatus($data) {
        $query = "UPDATE " . $this->table . " SET status = :status, alasan_penolakan = :alasan WHERE id = :id";
        $this->db->query($query);
        $this->db->bind('status', $data['status']);
        $this->db->bind('id', $data['id']);
        
        $alasan = ($data['status'] == 'Ditolak') ? $data['alasan_penolakan'] : null;
        $this->db->bind('alasan', $alasan);

        $this->db->execute();
        return $this->db->rowCount();
    }
    
    // Admin: Ambil Semua (Bisa sama dengan Riwayat jika tidak ada filter user)
    public function getAllPengajuan() {
        return $this->getRiwayat();
    }

    // QUERY UNTUK ADMIN (Update Status & Waktu)
    public function updatePengajuanAdmin($data) 
    {
        $query = "UPDATE " . $this->table . " 
                  SET tgl_mulai = :mulai,
                      tgl_selesai = :selesai,
                      status = :status,
                      alasan_penolakan = :alasan
                  WHERE id = :id";

        $this->db->query($query);
        $this->db->bind('mulai',   $data['tgl_mulai']);
        $this->db->bind('selesai', $data['tgl_selesai']);
        $this->db->bind('status',  $data['status']);
        $this->db->bind('alasan',  $data['alasan_penolakan']);
        $this->db->bind('id',      $data['id']);

        $this->db->execute();
        return $this->db->rowCount();
    }
}