<?php

header("Access-Control-Allow-Origin: http://localhost:3000");

header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Content-Length: 0');
    header('HTTP/1.1 204 No Content');
    exit;
}

require_once '/var/www/html/vendor/autoload.php';

use Api\Application;

$app = new Application();
$app->run();