<?php

class Home extends Controller
{
    public function index()
    {
        $data['title'] = 'Home';
        $this->view('components/header', $data);
        $this->view('home/index', $data);
        $this->view('components/footer', $data);
    }
}
