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

    // ✅ Method untuk TAMPILKAN pesan - Dengan SweetAlert2
    public static function flash()
    {
        if (isset($_SESSION['flash']) && is_array($_SESSION['flash'])) {
            $tipe = $_SESSION['flash']['tipe'] ?? 'Info';
            $pesan = $_SESSION['flash']['pesan'] ?? '';
            $aksi = $_SESSION['flash']['aksi'] ?? 'info';
            
            if (empty($pesan)) {
                unset($_SESSION['flash']);
                return;
            }

            // Map Bootstrap classes to SweetAlert icons
            $icon = $aksi;
            if ($aksi === 'danger') $icon = 'error';
            if ($aksi === 'warning') $icon = 'warning';
            if ($aksi === 'info') $icon = 'info';
            if ($aksi === 'success') $icon = 'success';

            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: '" . addslashes($tipe) . "',
                        text: '" . addslashes($pesan) . "',
                        icon: '" . $icon . "',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        customClass: {
                            popup: 'rounded-4 shadow'
                        }
                    });
                });
            </script>";
            
            unset($_SESSION['flash']);
        }
    }
}