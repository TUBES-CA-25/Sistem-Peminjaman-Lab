<?php

class App
{
    protected $controller = 'Home'; // Default controller
    protected $method = 'index';    // Default method
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseURL();

        // --- PERBAIKAN LOGIC (FIX) ---
        // Jika URL pertama terbaca sebagai 'public', kita buang karena itu nama folder
        if (isset($url[0]) && $url[0] == 'public') {
            array_shift($url);
        }
        // -----------------------------

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

        // Fallback for XAMPP/Windows if .htaccess is ignored or not working
        $request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $script_name = $_SERVER['SCRIPT_NAME']; // /TUBES_CA/public/index.php or /TUBES_CA/index.php

        // dirname might include backslashes on Windows
        $dirname = str_replace('\\', '/', dirname($script_name));

        // Remove trailing slash
        $dirname = rtrim($dirname, '/');

        // Check if request_uri starts with dirname
        if (strpos($request_uri, $dirname) === 0) {
            $url_path = substr($request_uri, strlen($dirname));
        } else {
            $url_path = $request_uri;
        }

        $url = trim($url_path, '/');

        if (!empty($url)) {
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }

        return [];
    }
}