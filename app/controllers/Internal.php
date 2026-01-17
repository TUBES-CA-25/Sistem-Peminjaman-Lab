<?php

class Internal extends Controller
{
    public function __construct()
    {
        // Cek login & role (sesuaikan dengan logic auth Anda)
        // if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'internal') {
        //     header('Location: ' . BASE_URL . '/auth/login');
        //     exit;
        // }
    }

    public function index()
    {
        // Redirect ke booking
        header('Location: ' . BASE_URL . '/internal/booking');
        exit;
    }

    public function booking()
    {
        $data['judul'] = 'Booking Laboratorium';
        
        // ============================================
        // DATA SEMENTARA - Nanti diganti dari database
        // ============================================
        
        // Data Laboratorium
        $data['labs'] = [
            [
                'id' => 1,
                'name' => 'Lab Start Up',
                'short_name' => 'Lab Start Up',
                'capacity' => 30,
                'building' => 'Gedung F, Lantai 3',
                'pic' => 'Dr. Budi Santoso',
                'image' => 'StartUp.jpg',
                'status' => 'tersedia'
            ],
            [
                'id' => 2,
                'name' => 'Lab Internet of Things',
                'short_name' => 'Lab IoT',
                'capacity' => 25,
                'building' => 'Gedung E, Lantai 1',
                'pic' => 'Dr. Budi Santoso',
                'image' => 'IoT.jpg',
                'status' => 'terpakai'
            ],
            [
                'id' => 3,
                'name' => 'Lab Multimedia',
                'short_name' => 'Lab Multimedia',
                'capacity' => 28,
                'building' => 'Gedung F, Lantai 2',
                'pic' => 'Dr. Budi Santoso',
                'image' => 'Mulmed.jpg',
                'status' => 'tersedia'
            ],
            [
                'id' => 4,
                'name' => 'Lab Computer Networking',
                'short_name' => 'Lab Networking',
                'capacity' => 30,
                'building' => 'Gedung F, Lantai 3',
                'pic' => 'Dr. Budi Santoso',
                'image' => 'comnet.png',
                'status' => 'tersedia'
            ],
            [
                'id' => 5,
                'name' => 'Lab Data Science',
                'short_name' => 'Lab Data Science',
                'capacity' => 32,
                'building' => 'Gedung F, Lantai 1',
                'pic' => 'Dr. Budi Santoso',
                'image' => 'DS.jpg',
                'status' => 'tersedia'
            ],
            [
                'id' => 6,
                'name' => 'Lab Computer Vision',
                'short_name' => 'Lab Computer Vision',
                'capacity' => 20,
                'building' => 'Gedung F, Lantai 2',
                'pic' => 'Dr. Budi Santoso',
                'image' => 'CV.jpg',
                'status' => 'tersedia'
            ],
            [
                'id' => 7,
                'name' => 'Lab Microcontroller',
                'short_name' => 'Lab Microcontroller',
                'capacity' => 25,
                'building' => 'Gedung E, Lantai 2',
                'pic' => 'Dr. Budi Santoso',
                'image' => 'Micro.jpg',
                'status' => 'tersedia'
            ],
            [
                'id' => 8,
                'name' => 'Riset 2',
                'short_name' => 'Riset 2',
                'capacity' => 28,
                'building' => 'Gedung F, Lantai 3',
                'pic' => 'Dr. Budi Santoso',
                'image' => 'Riset.jpg',
                'status' => 'tersedia'
            ]
        ];
        
        // Jadwal Praktikum Tetap (Pola Mingguan)
        // Ini akan di-generate berdasarkan hari yang dipilih user
        $data['jadwal_tetap'] = [
            // Lab Start Up - Rabu
            ['lab_id' => 1, 'hari' => 'Rabu', 'jam_mulai' => '10:30', 'jam_selesai' => '14:20', 'kelas' => 'A1', 'matkul' => 'P. Pemrograman'],
            ['lab_id' => 1, 'hari' => 'Rabu', 'jam_mulai' => '14:30', 'jam_selesai' => '18:20', 'kelas' => 'A2', 'matkul' => 'P. Pemrograman'],
            // Lab Start Up - Senin
            ['lab_id' => 1, 'hari' => 'Senin', 'jam_mulai' => '07:00', 'jam_selesai' => '09:30', 'kelas' => 'B1', 'matkul' => 'Algoritma'],
            ['lab_id' => 1, 'hari' => 'Senin', 'jam_mulai' => '10:00', 'jam_selesai' => '12:30', 'kelas' => 'B2', 'matkul' => 'Algoritma'],
            
            // Lab IoT - Rabu
            ['lab_id' => 2, 'hari' => 'Rabu', 'jam_mulai' => '14:30', 'jam_selesai' => '18:29', 'kelas' => 'A4', 'matkul' => 'P. Pemrograman'],
            // Lab IoT - Senin
            ['lab_id' => 2, 'hari' => 'Senin', 'jam_mulai' => '13:00', 'jam_selesai' => '15:30', 'kelas' => 'C1', 'matkul' => 'IoT Dasar'],
            
            // Lab Microcontroller - Rabu
            ['lab_id' => 7, 'hari' => 'Rabu', 'jam_mulai' => '07:00', 'jam_selesai' => '09:30', 'kelas' => 'B1', 'matkul' => 'Microcontroller'],
            ['lab_id' => 7, 'hari' => 'Rabu', 'jam_mulai' => '09:40', 'jam_selesai' => '12:10', 'kelas' => 'A7', 'matkul' => 'Microcontroller'],
            ['lab_id' => 7, 'hari' => 'Rabu', 'jam_mulai' => '13:00', 'jam_selesai' => '15:30', 'kelas' => 'A8', 'matkul' => 'Microcontroller'],
            // Lab Microcontroller - Selasa
            ['lab_id' => 7, 'hari' => 'Selasa', 'jam_mulai' => '07:00', 'jam_selesai' => '09:30', 'kelas' => 'D1', 'matkul' => 'Embedded System'],
            
            // Lab Computer Vision - Rabu
            ['lab_id' => 6, 'hari' => 'Rabu', 'jam_mulai' => '09:40', 'jam_selesai' => '12:10', 'kelas' => 'A7', 'matkul' => 'Struktur Data'],
            ['lab_id' => 6, 'hari' => 'Rabu', 'jam_mulai' => '13:00', 'jam_selesai' => '15:30', 'kelas' => 'A5', 'matkul' => 'Struktur Data'],
            
            // Lab Data Science - Rabu
            ['lab_id' => 5, 'hari' => 'Rabu', 'jam_mulai' => '07:00', 'jam_selesai' => '09:30', 'kelas' => 'B4', 'matkul' => 'Basis Data II'],
            ['lab_id' => 5, 'hari' => 'Rabu', 'jam_mulai' => '09:40', 'jam_selesai' => '12:15', 'kelas' => 'A8', 'matkul' => 'Struktur Data'],
            ['lab_id' => 5, 'hari' => 'Rabu', 'jam_mulai' => '13:00', 'jam_selesai' => '15:30', 'kelas' => 'A6', 'matkul' => 'Struktur Data'],
            // Lab Data Science - Senin
            ['lab_id' => 5, 'hari' => 'Senin', 'jam_mulai' => '07:00', 'jam_selesai' => '09:30', 'kelas' => 'E1', 'matkul' => 'Data Mining'],
        ];
        
        // Peminjaman (Internal, External, Tergeser)
        // Menggunakan tanggal dinamis untuk testing
        $today = date('Y-m-d');
        $data['peminjaman'] = [
            // Peminjaman Internal - hari ini
            ['lab_id' => 2, 'tanggal' => $today, 'jam_mulai' => '09:00', 'jam_selesai' => '11:00', 'type' => 'internal', 'keterangan' => 'Rapat Jurusan TI', 'peminjam' => 'Admin'],
            
            // Peminjaman External - hari ini
            ['lab_id' => 7, 'tanggal' => $today, 'jam_mulai' => '16:00', 'jam_selesai' => '18:00', 'type' => 'external', 'keterangan' => 'Pelatihan Polri Makassar', 'peminjam' => 'Polri Makassar'],
            
            // Jadwal Tergeser - hari ini
            ['lab_id' => 6, 'tanggal' => $today, 'jam_mulai' => '07:00', 'jam_selesai' => '09:30', 'type' => 'tergeser', 'keterangan' => 'Pindah dari Lab DS', 'peminjam' => 'Dosen A'],
            
            // Contoh untuk tanggal Rabu
            ['lab_id' => 2, 'tanggal' => '2026-01-15', 'jam_mulai' => '09:00', 'jam_selesai' => '11:00', 'type' => 'internal', 'keterangan' => 'Workshop Internal', 'peminjam' => 'Tim IT'],
        ];
        
        // Tanggal yang dipilih (default hari ini atau dari parameter)
        $data['selected_date'] = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
        $data['selected_day'] = $this->getDayName($data['selected_date']);
        
        // ============================================
        // END DATA SEMENTARA
        // ============================================
        
        $this->view('components/header', $data);
        $this->view('components/internal_navbar', $data);
        $this->view('/internal/booking/index', $data);
        $this->view('components/footer');
    }
    
    /**
     * Helper: Get Indonesian day name from date
     */
    private function getDayName($date)
    {
        $days = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        $dayEnglish = date('l', strtotime($date));
        return $days[$dayEnglish] ?? $dayEnglish;
    }
    
    /**
     * Helper: Get jadwal for specific lab and day
     */
    public function getJadwalByLabAndDay($jadwalTetap, $labId, $hari)
    {
        return array_filter($jadwalTetap, function($j) use ($labId, $hari) {
            return $j['lab_id'] == $labId && $j['hari'] == $hari;
        });
    }
    
    /**
     * Helper: Get peminjaman for specific lab and date
     */
    public function getPeminjamanByLabAndDate($peminjaman, $labId, $tanggal)
    {
        return array_filter($peminjaman, function($p) use ($labId, $tanggal) {
            return $p['lab_id'] == $labId && $p['tanggal'] == $tanggal;
        });
    }
}
