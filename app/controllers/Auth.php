<?php

class Auth extends Controller
{
    public function index()
    {
        $this->login();
    }

    public function login()
    {
        $data['title'] = 'Login';
        $this->view('components/header', $data);
        $this->view('auth/login', $data);
        $this->view('components/footer', $data);
    }

    public function register()
    {
        $data['title'] = 'Register';
        $this->view('components/header', $data);
        $this->view('auth/register', $data);
        $this->view('components/footer', $data);
    }
}
