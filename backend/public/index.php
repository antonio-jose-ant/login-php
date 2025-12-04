<?php
require_once __DIR__ . '/../src/includes/displayErrors.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../vendor/autoload.php';

use App\Router\Router;
require_once __DIR__ . '/../src/Routes/web.php';
$uri = str_replace('/LOGIN-PHP/backend/public/index.php', '', $_SERVER['REQUEST_URI']);

Router::dispatch($uri);
