<?php

class Error extends Controller
{
    public function index()
    {
        $this->show404();
    }

    public function show404()
    {
        http_response_code(404);
        $data['judul'] = '404 - Halaman Tidak Ditemukan';
        $this->view('errors/404', $data);
    }

    public function show403()
    {
        http_response_code(403);
        $data['judul'] = '403 - Akses Ditolak';
        $this->view('errors/403', $data);
    }

    public function show500()
    {
        http_response_code(500);
        $data['judul'] = '500 - Server Error';
        $this->view('errors/500', $data);
    }

    public function show401()
    {
        http_response_code(401);
        $data['judul'] = '401 - Unauthorized';
        $this->view('errors/401', $data);  
    }

    public function show503()
    {
        http_response_code(503);
        $data['judul'] = '503 - Service Unavailable';
        $this->view('errors/503', $data);
    }
}