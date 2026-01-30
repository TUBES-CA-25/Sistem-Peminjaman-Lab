<?php

if (!session_id())
    session_start();

// Check Maintenance Mode
if (file_exists(__DIR__ . '/.maintenance')) {
    http_response_code(503);
    require_once __DIR__ . '/../app/views/errors/503.php';
    exit;
}

require_once 'app/init.php';

$app = new App();
