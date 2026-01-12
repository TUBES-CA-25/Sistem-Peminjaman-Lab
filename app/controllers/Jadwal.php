<?php

class Jadwal extends Controller
{
    public function index()
    {
        $data['active_page'] = 'jadwal';
        $data['title'] = 'Tambah Jadwal - Admin';
        
        $this->view('components/admin_head', $data);
        $this->view('components/admin_navbar', $data);
        $this->view('components/admin_sidebar', $data);
        $this->view('admin/jadwal/index', $data);
        $this->view('components/admin_footer', $data);
    }
}