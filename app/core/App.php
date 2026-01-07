<?php

class App
{
    protected $controller = 'Home'; // Default controller
    protected $method = 'index';    // Default method
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseURL();

        // 1. Check Controller
        // 1. Check Controller
        if (isset($url[0])) {
            if (file_exists(__DIR__ . '/../controllers/' . ucfirst($url[0]) . '.php')) {
                $this->controller = ucfirst($url[0]);
                unset($url[0]);
            }
        }

        require_once __DIR__ . '/../controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // 2. Check Method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // 3. Params
        if (!empty($url)) {
            $this->params = array_values($url);
        }

        // Run Controller & Method, sending params
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseURL()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }

        // Fallback for PHP Native Server (php -S) which doesn't use .htaccess
        $request_uri = $_SERVER['REQUEST_URI'];
        $script_name = dirname($_SERVER['SCRIPT_NAME']);

        // Remove script path from URI if it exists (for subdirectory installation)
        if ($script_name !== '/' && $script_name !== '\\') {
            $request_uri = str_replace($script_name, '', $request_uri);
        }

        $url = trim($request_uri, '/');
        if (!empty($url)) {
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);

            // Remove query string from the last element if present
            if (isset($url[count($url) - 1])) {
                $parts = explode('?', $url[count($url) - 1]);
                $url[count($url) - 1] = $parts[0];
            }

            return $url;
        }

        return [];
    }
}
