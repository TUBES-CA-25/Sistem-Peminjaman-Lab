
<?php 

class Flasher {
    
    // Method untuk SET pesan (Simpan pesan ke session)
    public static function setFlash($pesan, $aksi, $tipe)
    {
        $_SESSION['flash'] = [
            'pesan' => $pesan,
            'aksi'  => $aksi,
            'tipe'  => $tipe
        ];
    }

    // Method untuk TAMPILKAN pesan (Baca session lalu hapus)
    public static function flash()
    {
        if( isset($_SESSION['flash']) ) {
            echo '<div class="alert alert-' . $_SESSION['flash']['tipe'] . ' alert-dismissible fade show" role="alert">
                    Data <strong>' . $_SESSION['flash']['pesan'] . '</strong> ' . $_SESSION['flash']['aksi'] . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
            
            // Hapus session setelah ditampilkan agar tidak muncul terus
            unset($_SESSION['flash']);
        }
    }
}