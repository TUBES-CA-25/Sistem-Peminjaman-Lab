<?php

class Controller
{
    public function view($view, $data = [])
    {
        // Extract array menjadi variabel
        extract($data);
        
        require_once __DIR__ . '/../views/' . $view . '.php';
    }

    public function model($model)
    {
        require_once __DIR__ . '/../models/' . $model . '.php';
        return new $model;
    }

    public function service($service)
    {
        require_once __DIR__ . '/../services/' . $service . '.php';
        return new $service;
    }
}
