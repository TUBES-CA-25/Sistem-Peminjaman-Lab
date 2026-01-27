<?php 

class Flasher {
    
    // ✅ Method untuk SET pesan - Urutan parameter diperbaiki
    public static function setFlash($tipe, $pesan, $aksi)
    {
        $_SESSION['flash'] = [
            'tipe'  => $tipe,   // Judul (Berhasil, Gagal, Info)
            'pesan' => $pesan,  // Pesan detail
            'aksi'  => $aksi    // success, danger, warning, info
        ];
    }

    // ✅ Method untuk TAMPILKAN pesan - Dengan validasi
    public static function flash()
    {
        // Cek apakah flash message ada dan valid
        if (isset($_SESSION['flash']) && is_array($_SESSION['flash'])) {
            
            // Ambil data dengan default value (untuk menghindari undefined key)
            $tipe = $_SESSION['flash']['tipe'] ?? 'Info';
            $pesan = $_SESSION['flash']['pesan'] ?? '';
            $aksi = $_SESSION['flash']['aksi'] ?? 'info';
            
            // Skip jika pesan kosong
            if (empty($pesan)) {
                unset($_SESSION['flash']);
                return;
            }

            // Tampilkan alert
            echo '<div class="alert alert-' . htmlspecialchars($aksi) . ' alert-dismissible fade show" role="alert">
                    <strong>' . htmlspecialchars($tipe) . '</strong> ' . htmlspecialchars($pesan) . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
            
            // Hapus session setelah ditampilkan
            unset($_SESSION['flash']);

            // Auto-hide alert setelah 5 detik
            echo '<script>
                setTimeout(function() {
                    var alert = document.querySelector(".alert");
                    if (alert) { 
                        var bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                }, 5000);
            </script>';
        }
    }
}