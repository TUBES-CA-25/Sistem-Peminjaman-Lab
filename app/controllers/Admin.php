<?php

class Admin extends Controller
{
    public function index()
    {
        // Redirect to default admin page
        header("Location: " . BASE_URL . "ruangan");
        exit;
    }
}
