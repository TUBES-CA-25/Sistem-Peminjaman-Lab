<?php

class Peminjaman extends Controller
{
    public function index()
    {
        $data['active_page'] = 'peminjaman'; // For sidebar highlighting

        $this->view('components/admin_head', $data);
        $this->view('components/admin_navbar', $data);
        $this->view('components/admin_sidebar', $data);

        // Load the existing content file
        // Note: The file is in app/views/admin/data_peminjaman_content.php
        // The view loader looks in app/views/
        $this->view('admin/data_peminjaman_content', $data);

        $this->view('components/admin_footer', $data);
    }
}
