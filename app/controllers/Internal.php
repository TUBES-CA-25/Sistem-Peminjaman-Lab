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
        header('Location: ' . BASE_URL . 'internal/booking');
        exit;
    }

    public function booking()
    {
        $data['judul'] = 'Booking Laboratorium';
        $this->view('components/header', $data);
        $this->view('components/internal_navbar', $data);
        $this->view('internal/booking', $data);
        $this->view('components/footer');
    }
}
